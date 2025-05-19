<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Unidad extends Model
{
    use HasFactory;

    protected $table = 'unidades_fisicas_ejecutoras';
    protected $fillable=[
        'cod_nucleo',
        'codigo_unidad_admin',
        'descripcion_unidad_admin',
        'codigo_unidad_ejec',
        'descripcion_unidad_ejec',
    ];

    public function nucleo()
    {
        return $this->hasOne(Nucleo::class, 'codigo_concatenado', 'cod_nucleo');
    }

}
