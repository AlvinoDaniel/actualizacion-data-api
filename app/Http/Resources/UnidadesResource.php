<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UnidadesResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(function($item){
            return [
                "id"                    => $item?->id,
                "cedula_identidad"      => $item?->cedula_identidad,
                "id_unidad_admin"       => $item?->id_unidad_admin,
                "entidad"               => [
                    "id_unidad_admin"           => $item->entidad?->id,
                    "codigo_unidad_admin"       => $item->entidad?->codigo_unidad,
                    "descripcion_unidad_admin"  => $item->entidad?->descripcion,
                    "correo_dependencia"        => $item->entidad?->correo_dependencia,
                    "codigo_unidad_ejec"        => $item->entidad?->unidad_ejecutora?->codigo_unidad,
                    "id_unidad_ejec"            => $item->entidad?->unidad_ejecutora?->id,
                    "descripcion_unidad_ejec"   => $item->entidad?->unidad_ejecutora?->descripcion,
                    "descripcion_escuela"       => $item->entidad?->escuela?->descripcion,
                ]
            ];
        });
    }
}
