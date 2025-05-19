<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'correo'                => "required|email",
            'telefono'              => "required",
            'pantalon'              => "required",
            'camisa'                => "required",
            'zapato'                => "required",
            'sexo'                  => "required",
            'area_trabajo'          => "required|exists:area_trabajo,id",
            'tipo_calzado'          => "required|exists:tipo_calzado,id",
            'prenda_extra'          => "required|exists:tipo_prenda,id",
        ];
    }

}
