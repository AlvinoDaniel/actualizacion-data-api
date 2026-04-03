<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CargoPersonalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('id');

        return [
            'codigo' => [
                'required',
                'string',
                'max:19',
                Rule::unique('cargos_personal', 'codigo')
                    ->ignore($id)
                    ->where(function ($query) {
                        return $query->where('codigo', strtoupper($this->codigo));
                    }),
            ],
            'descripcion' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cargos_personal', 'descripcion')
                    ->ignore($id)
                    ->where(function ($query) {
                        return $query->where('descripcion', strtoupper($this->descripcion));
                    }),
            ],
        ];
    }
}
