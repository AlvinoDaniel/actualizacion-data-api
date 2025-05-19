<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;

class Nucleo extends Model
{
    use HasFactory;

    protected $table = 'nucleo';
    protected $fillable=[
        'nombre',
        'codigo_1',
        'codigo_2',
        'codigo_concatenado',
        'direccion',
    ];

}
