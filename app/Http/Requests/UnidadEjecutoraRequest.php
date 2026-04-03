<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnidadEjecutoraRequest extends FormRequest
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
                'required', 'max:10',
                Rule::unique('unidades_ejecutoras', 'codigo_unidad')->where(function($query){
                    return $query->where('codigo_unidad', strtoupper($this->codigo));
                })->ignore($this->route('id'))],
            'nombre'    => [
                "required",
                Rule::unique('unidades_ejecutoras', 'descripcion')->where(function($query){
                    return $query->where('descripcion', strtoupper($this->nombre));
                })->ignore($this->route('id'))
            ],
        ];
    }
}
