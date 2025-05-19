<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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

    public function messages()
    {
        return [
            'cedula.unique' => 'El Funcionario seleccionado ya tiene un usuario registrado.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd(Rule::unique('users', 'personal_id')->ignore($this->route('id')));
        return [
            'password'      => 'required|min:4',
            'cedula'  => [
                "required",
                "numeric",
                "min:5",
                Rule::unique('users')
            ],
            'personal_id'   => 'required|exists:personal,id'
        ];
    }

}
