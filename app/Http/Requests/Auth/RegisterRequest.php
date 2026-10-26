<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/u', // Pas de chiffres, seulement lettres, espaces et tirets
            ],
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Le nom ne doit contenir que des lettres, espaces ou tirets.',
            'password.regex' => 'Le mot de passe doit contenir : une majuscule, une minuscule, un chiffre et un caractère spécial.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ];
    }
}