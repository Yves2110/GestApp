<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreObjectiveRequest extends FormRequest
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
            'label' => 'required|string|max:255|unique:objectives,label',
        ];
    }

    public function messages(): array
    {
        return [
            'label.required' => 'Le libellé de l\'objectif est obligatoire.',
            'label.unique'   => 'Un objectif avec ce libellé existe déjà.',
            'label.max'      => 'Le libellé ne peut pas dépasser 255 caractères.',
        ];
    }
}
