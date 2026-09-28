<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReclamationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'client';
    }

    public function rules(): array
    {
        return [
            'obj_reclam' => 'required|string|max:255',
            'des_reclam' => 'required|string',
            'id_typ_rec' => 'required|exists:types_reclamation,id',
            'pieces_jointes' => 'nullable|array',
            'pieces_jointes.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120', // 5120 Ko = 5 Mo
        ];
    }

    public function messages(): array
    {
        return [
            'pieces_jointes.*.mimes' => 'Seuls les fichiers PDF, JPG et PNG sont acceptés.',
            'pieces_jointes.*.max' => 'Chaque fichier ne doit pas dépasser 5 Mo.',
        ];
    }
}