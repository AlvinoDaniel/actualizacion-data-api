<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadAdministrativa extends Model
{
    use HasFactory;

    protected $table = 'unidades_administrativas';
    protected $fillable=[
        'cod_nucleo',
        'codigo_unidad',
        'codigo_unidad_anterior',
        'descripcion',
        'correo_dependencia',
        'activo',
        'id_unidad_ejec',
        'id_escuela',
        'id_unidad_padre',
        'año',
    ];

    public function nucleo()
    {
        return $this->hasOne(Nucleo::class, 'codigo_concatenado', 'cod_nucleo');
    }

    public function unidad_ejecutora()
    {
        return $this->hasOne(UnidadEjecutora::class, 'id_unidad_ejec');
    }

    public function escuela()
    {
        return $this->hasOne(Escuela::class, 'id_escuela');
    }
}
