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
        'descripcion',
        'correo_dependencia',
        'activo',
        'id_unidad_ejec',
        'cod_escuela',
        'cod_unidad_padre',
        'año',
        'jefe'
    ];

    protected $casts = [
        'jefe' => 'boolean',
    ];
    protected $with = ['unidad_ejecutora'];

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

    public function unidad_padre()
    {
        return $this->belongsTo(UnidadAdministrativa::class, 'codigo_unidad', 'cod_unidad_padre');
    }

    public function subunidades()
    {
        return $this->hasMany(UnidadAdministrativa::class, 'cod_unidad_padre', 'codigo_unidad')->where('jefe', 0);
    }
}
