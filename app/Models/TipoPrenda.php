<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPrenda extends Model
{
    use HasFactory;

    protected $table = 'tipo_prenda';
    protected $fillable=[
        'descripcion',
    ];
}
