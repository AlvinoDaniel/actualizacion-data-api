<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoPersonal extends Model
{
    use HasFactory;

    protected $table = 'cargos_personal';
    protected $fillable=[
        'codigo',
        'descripcion',
    ];
}
