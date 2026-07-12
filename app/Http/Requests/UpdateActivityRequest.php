<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_id'        => 'required|exists:services,id',
            'objective_id'      => 'required|exists:objectives,id',
            'under_objective_id'=> 'required|exists:under_objectives,id',
            'periode_id'        => 'required|exists:periodes,id',
            'label'             => 'required|string|max:255',
            'indicator'         => 'nullable|string|max:255',
            'target'            => 'nullable|string|max:255',
            'price'             => 'nullable|integer|min:0',
            'source_of_funding' => 'nullable|string|max:255',
            'structure'         => 'nullable|string|max:255',
            'commentary'        => 'nullable|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required'        => 'Le service est obligatoire.',
            'objective_id.required'      => 'L\'objectif est obligatoire.',
            'under_objective_id.required'=> 'Le sous-objectif est obligatoire.',
            'periode_id.required'        => 'La période est obligatoire.',
            'label.required'             => 'Le libellé de l\'activité est obligatoire.',
            'label.max'                  => 'Le libellé ne peut pas dépasser 255 caractères.',
            'price.integer'              => 'Le budget doit être un nombre entier.',
            'price.min'                  => 'Le budget ne peut pas être négatif.',
        ];
    }
}
