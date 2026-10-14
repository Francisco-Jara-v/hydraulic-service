<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasIndex('equipo_especificacions', 'equipo_especificacions_equipo_id_index')) {
            Schema::table('equipo_especificacions', function (Blueprint $table) {
                $table->index('equipo_id', 'equipo_especificacions_equipo_id_index');
            });
        }

        if (! Schema::hasColumn('equipo_especificacions', 'clave')) {
            Schema::table('equipo_especificacions', function (Blueprint $table) {
                $table->string('clave')->nullable()->after('nombre');
            });
        }

        if (Schema::hasIndex('equipo_especificacions', 'equipo_especificacions_equipo_id_valor_unique')) {
            Schema::table('equipo_especificacions', function (Blueprint $table) {
                $table->dropUnique('equipo_especificacions_equipo_id_valor_unique');
            });
        }

        Schema::table('equipo_especificacions', function (Blueprint $table) {
            $table->string('valor')->nullable()->change();
        });

        $mappingByEquipo = [];

        DB::table('equipo_especificacions')
            ->orderBy('id')
            ->get()
            ->each(function (object $especificacion) use (&$mappingByEquipo): void {
                $clave = "spec_{$especificacion->id}";

                DB::table('equipo_especificacions')
                    ->where('id', $especificacion->id)
                    ->update(['clave' => $clave]);

                $mappingByEquipo[$especificacion->equipo_id][] = [
                    'legacy' => $this->generarClaveAnterior($especificacion->nombre),
                    'stable' => $clave,
                ];
            });

        foreach ($mappingByEquipo as $equipoId => $mapping) {
            DB::table('equipo_items')
                ->where('equipo_id', $equipoId)
                ->orderBy('id')
                ->chunkById(250, function (Collection $items) use ($mapping): void {
                    foreach ($items as $item) {
                        if ($item->especificaciones === null) {
                            continue;
                        }

                        $especificaciones = json_decode(
                            (string) $item->especificaciones,
                            true,
                            flags: JSON_THROW_ON_ERROR,
                        ) ?? [];

                        if (! is_array($especificaciones)) {
                            continue;
                        }

                        $especificaciones = $this->remapearClaves($especificaciones, $mapping, toStable: true);

                        DB::table('equipo_items')
                            ->where('id', $item->id)
                            ->update(['especificaciones' => json_encode($especificaciones, JSON_THROW_ON_ERROR)]);
                    }
                });
        }

        Schema::table('equipo_especificacions', function (Blueprint $table) {
            $table->string('clave')->nullable(false)->change();
        });

        if (! Schema::hasIndex('equipo_especificacions', 'equipo_especificacions_equipo_id_clave_unique')) {
            Schema::table('equipo_especificacions', function (Blueprint $table) {
                $table->unique(['equipo_id', 'clave']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasIndex('equipo_especificacions', 'equipo_especificacions_equipo_id_clave_unique')) {
            Schema::table('equipo_especificacions', function (Blueprint $table) {
                $table->dropUnique('equipo_especificacions_equipo_id_clave_unique');
            });
        }

        $mappingByEquipo = [];

        DB::table('equipo_especificacions')
            ->orderBy('id')
            ->get()
            ->each(function (object $especificacion) use (&$mappingByEquipo): void {
                $mappingByEquipo[$especificacion->equipo_id][] = [
                    'legacy' => $this->generarClaveAnterior($especificacion->nombre),
                    'stable' => $especificacion->clave,
                ];
            });

        foreach ($mappingByEquipo as $equipoId => $mapping) {
            DB::table('equipo_items')
                ->where('equipo_id', $equipoId)
                ->orderBy('id')
                ->chunkById(250, function (Collection $items) use ($mapping): void {
                    foreach ($items as $item) {
                        if ($item->especificaciones === null) {
                            continue;
                        }

                        $especificaciones = json_decode(
                            (string) $item->especificaciones,
                            true,
                            flags: JSON_THROW_ON_ERROR,
                        ) ?? [];

                        if (! is_array($especificaciones)) {
                            continue;
                        }

                        $especificaciones = $this->remapearClaves($especificaciones, $mapping, toStable: false);

                        DB::table('equipo_items')
                            ->where('id', $item->id)
                            ->update(['especificaciones' => json_encode($especificaciones, JSON_THROW_ON_ERROR)]);
                    }
                });
        }

        Schema::table('equipo_especificacions', function (Blueprint $table) {
            if (Schema::hasColumn('equipo_especificacions', 'clave')) {
                $table->dropColumn('clave');
            }
        });

        if (! Schema::hasIndex('equipo_especificacions', 'equipo_especificacions_equipo_id_valor_unique')) {
            Schema::table('equipo_especificacions', function (Blueprint $table) {
                $table->unique(['equipo_id', 'valor']);
            });
        }

        if (Schema::hasIndex('equipo_especificacions', 'equipo_especificacions_equipo_id_index')) {
            Schema::table('equipo_especificacions', function (Blueprint $table) {
                $table->dropIndex('equipo_especificacions_equipo_id_index');
            });
        }
    }

    /**
     * @param  array<string, mixed>  $especificaciones
     * @param  array<int, array{legacy: string, stable: string}>  $mapping
     * @return array<string, mixed>
     */
    private function remapearClaves(array $especificaciones, array $mapping, bool $toStable): array
    {
        $original = $especificaciones;

        foreach ($mapping as $claves) {
            $origen = $toStable ? $claves['legacy'] : $claves['stable'];
            $destino = $toStable ? $claves['stable'] : $claves['legacy'];

            if ($origen === $destino || ! array_key_exists($origen, $original)) {
                continue;
            }

            if (! array_key_exists($destino, $original)) {
                $especificaciones[$destino] = $original[$origen];
            }
        }

        $clavesDestino = array_map(
            fn (array $claves): string => $toStable ? $claves['stable'] : $claves['legacy'],
            $mapping,
        );

        foreach ($mapping as $claves) {
            $origen = $toStable ? $claves['legacy'] : $claves['stable'];

            if (! in_array($origen, $clavesDestino, true)) {
                unset($especificaciones[$origen]);
            }
        }

        return $especificaciones;
    }

    private function generarClaveAnterior(string $nombre): string
    {
        return Str::of($nombre)
            ->ascii()
            ->lower()
            ->replace(' ', '_')
            ->toString();
    }
};
