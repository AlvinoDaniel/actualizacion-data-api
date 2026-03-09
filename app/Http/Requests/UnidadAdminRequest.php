<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnidadAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'codigo'    => [
                'required', 'max:19',
                Rule::unique('unidades_administrativas', 'codigo_unidad')->where(function($query){
                    return $query->where('codigo_unidad', strtoupper($this->codigo));
                })->ignore($this->route('id'))],
            'nombre'    => [
                "required",
                Rule::unique('unidades_administrativas', 'descripcion')->where(function($query){
                    return $query->where('descripcion', strtoupper($this->nombre));
                })->ignore($this->route('id'))
            ],
            'cod_nucleo'            => "required|exists:nucleo,codigo_concatenado",
            'cod_unidad_padre'      => "exists:unidades_administrativas,codigo_unidad",
            'id_unidad_ejec'        => "exists:unidades_ejecutoras,id",
            'cod_escuela'           => "exists:escuelas,codigo",
            'activo'                => 'boolean',
        ];
    }
}
