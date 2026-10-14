<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arriendo extends Model
{
    /** @use HasFactory<\Database\Factories\ArriendoFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [

    ];

    
}
