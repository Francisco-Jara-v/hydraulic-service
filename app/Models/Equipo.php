<?php

namespace App\Models;

use Database\Factories\EquipoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    /** @use HasFactory<EquipoFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'equipos';

    protected $fillable = [
        'tipo',
        'descripcion',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(EquipoItem::class, 'equipo_id', 'id');
    }

    public function especificaciones(): HasMany
    {
        return $this->hasMany(EquipoEspecificacion::class);
    }
}
