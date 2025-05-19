<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaTrabajo extends Model
{
    use HasFactory;

    protected $table = 'area_trabajo';
    protected $fillable=[
        'descripcion',
        'tipo_personal',
    ];

    public function tipo_personal()
    {
        return $this->belongsTo(TipoPersonal::class);
    }
}
