<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArriendoDetalle extends Model
{
    /** @use HasFactory<\Database\Factories\ArriendoDetalleFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        
    ];
}
