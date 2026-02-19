<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "nombres_apellidos" => $this->nombres_apellidos,
            "cedula_identidad" => $this->cedula_identidad,
            "tipo_personal" => [
                "id" => $this->tipoPersonal?->id,
                "descripcion" => $this->tipoPersonal?->descripcion,
            ],
            "cargo_opsu" => $this->cargo_opsu,
            "cod_nucleo" => $this->cod_nucleo,
            "jefe" => $this->jefe,
            "cargo_jefe" => $this->cargo_jefe,
            "cargo_personla_jefe" => $this->cargoPersonal,
            "correo" => $this->correo,
            "telefono" => $this->telefono,
            "pantalon" => $this->pantalon,
            "camisa" => $this->camisa,
            "zapato" => $this->zapato,
            "sexo" => $this->sexo,
            "area_trabajo" => $this->area_trabajo,
            "tipo_calzado" => $this->tipo_calzado,
            "prenda_extra" => $this->prenda_extra,
            "updated_at" => $this->updated_at,
            "has_update" => $this->has_update,
            "codigo_nucleo" => $this->codigo_nucleo,
            "nucleo" => $this->nucleo,
            "unidades"            => new UnidadesResource($this->unidades),
        ];
    }
}
