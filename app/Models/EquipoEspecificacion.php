<?php

namespace App\Models;

use Database\Factories\EquipoEspecificacionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EquipoEspecificacion extends Model
{
    /** @use HasFactory<EquipoEspecificacionFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'equipo_id',
        'nombre',
        'unidad',
        'tipo',
    ];

    protected static function booted(): void
    {
        static::creating(function (EquipoEspecificacion $especificacion): void {
            if (blank($especificacion->clave)) {
                $especificacion->clave = 'spec_'.Str::lower((string) Str::ulid());
            }
        });
    }

    public static function normalizarNombre(string $nombre): string
    {
        return Str::slug($nombre, '_') ?: 'especificacion';
    }

    /**
     * @param  array<array<string, mixed>>  $especificaciones
     */
    public static function tienenNombresNormalizadosUnicos(array $especificaciones): bool
    {
        $nombres = [];

        foreach ($especificaciones as $especificacion) {
            $nombre = $especificacion['nombre'] ?? null;

            if (! is_string($nombre) || blank($nombre)) {
                continue;
            }

            $nombreNormalizado = self::normalizarNombre($nombre);

            if (isset($nombres[$nombreNormalizado])) {
                return false;
            }

            $nombres[$nombreNormalizado] = true;
        }

        return true;
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }
}
