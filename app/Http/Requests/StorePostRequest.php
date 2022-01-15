<?php

namespace App\Http\Requests;
use App\Rules\FullName;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'nome'=>['required',new FullName],
            'tel'=>'required',
            'data_nasci'=>'required',
            'endereco'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'nome.required' => 'O Nome é obrigatório',
            'tel.required' => 'O Telefone é obrigatório',
            'data_nasci.required' => 'A data de nasimento é obrigatória',
            'endereco.required' => 'O endereço é obrigatório',
        ];
    }
}
