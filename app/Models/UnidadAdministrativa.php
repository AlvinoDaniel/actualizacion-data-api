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
        'cod_escuela',
        'cod_unidad_padre',
        'año',
        'cod_ejec_anterior',
        'jefe'
    ];

    protected $casts = [
        'jefe' => 'boolean',
    ];

    public function nucleo()
    {
        return $this->hasOne(Nucleo::class, 'codigo_concatenado', 'cod_nucleo');
    }

    public function unidad_ejecutora()
    {
        return $this->hasOne(UnidadEjecutora::class, 'id', 'id_unidad_ejec');
    }

    public function escuela()
    {
        return $this->hasOne(Escuela::class, 'id', 'cod_escuela');
    }
}
