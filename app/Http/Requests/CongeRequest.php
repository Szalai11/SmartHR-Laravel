<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CongeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'type' => 'required|in:congé,permission',
            'motif' => 'nullable|string|max:500',
            'justificatif' => 'required_if:type,permission|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'date_fin.after_or_equal' => 'La date de fin doit être après la date de début.',
            'justificatif.required_if' => 'Un justificatif est obligatoire pour une permission.',
            'justificatif.mimes' => 'Le justificatif doit être au format PDF, JPG ou PNG.',
        ];
    }
}