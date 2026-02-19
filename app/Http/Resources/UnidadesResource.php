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
        $subUnidades = collect([]);
        $dataSubUnidades = [];
        $unidades = $this->collection->map(function($item) use($subUnidades) {
            if($item->entidad?->subunidades->count() > 0){
                foreach ($item->entidad?->subunidades as $value) {
                    $subUnidades->push($value);
                }
            }
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
                    "subunidades"               => $subUnidades,
                ]
            ];
        });

        if($subUnidades->count() > 0){
            $dataSubUnidades = $subUnidades->map(function($item) {
                return [
                    "id"                    => null,
                    "cedula_identidad"      => null,
                    "id_unidad_admin"       => $item?->id,
                    "entidad"               => [
                        "id_unidad_admin"           => $item?->id,
                        "codigo_unidad_admin"       => $item?->codigo_unidad,
                        "descripcion_unidad_admin"  => $item?->descripcion,
                        "correo_dependencia"        => $item?->correo_dependencia,
                        "codigo_unidad_ejec"        => $item?->unidad_ejecutora?->codigo_unidad,
                        "id_unidad_ejec"            => $item?->unidad_ejecutora?->id,
                        "descripcion_unidad_ejec"   => $item?->unidad_ejecutora?->descripcion,
                        "descripcion_escuela"       => null,
                    ]
                ];
            });
        }

        return $unidades->concat($dataSubUnidades);
    }
}
