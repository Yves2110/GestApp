<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUnderObjectiveRequest extends FormRequest
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
            'objective_id' => 'required|exists:objectives,id',
            'label'        => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'objective_id.required' => 'L\'objectif parent est obligatoire.',
            'objective_id.exists'   => 'L\'objectif sélectionné est invalide.',
            'label.required'        => 'Le libellé du sous-objectif est obligatoire.',
            'label.max'             => 'Le libellé ne peut pas dépasser 255 caractères.',
        ];
    }
}
