<?php

namespace App\Models;

use Database\Factories\ClienteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    /** @use HasFactory<ClienteFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'id',
        'company_name',
        'giro',
        'rut',
        'address',
        'city',
        'email',
        'phone',
    ];
}
