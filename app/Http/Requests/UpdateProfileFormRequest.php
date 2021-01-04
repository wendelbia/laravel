<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileFormRequest extends FormRequest
{
    //42 mudo para true
    public function authorize()
    {
        return true;
    }

    //42 para aceitar o mesmo email passamos o id do usuário logado
    public function rules()
    {
        $id = auth()->user()->id;
        return [
            //42
            'name' => 'required|string|max:255',
            //42 e acresencto emial, {$id}, id
            'email' => "required|string|email|max:255|unique:users,email,{$id},id",
            'password' => 'max:20|',
            //42 só garante imagem
            'image' => 'image'
            //42 e vou no UserController

        ];
    }
}
