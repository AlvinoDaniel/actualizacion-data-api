<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalMigracion extends Model
{
    use HasFactory;

    protected $table = 'personal_migracion';
    protected $fillable=[
        'nombres',
        'cedula_identidad',
        'cargo',
        'cod_nucleo',
        'correo',
        'grado_instruccion',
    ];
}
