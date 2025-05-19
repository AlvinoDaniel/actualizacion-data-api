<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCalzado extends Model
{
    use HasFactory;

    protected $table = 'tipo_calzado';
    protected $fillable=[
        'descripcion',
    ];
}
