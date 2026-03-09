<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NucleoRequest extends FormRequest
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
                'required', 'min:2','max:2',
                Rule::unique('nucleo', 'codigo_concatenado')->ignore($this->route('id'))],
            'nombre'    => [
                "required",
                Rule::unique('nucleo')->where(function($query){
                    return $query->where('nombre', strtoupper($this->nombre));
                })->ignore($this->route('id'))
            ],
        ];
    }


}
