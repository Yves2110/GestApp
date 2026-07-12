<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role_id === 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstname'  => 'required|string|max:100',
            'lastname'   => 'required|string|max:100',
            'email'      => 'required|email|max:255|unique:users,email',
            'role_id'    => 'required|exists:roles,id',
            'service_id' => 'nullable|exists:services,id',
            'password'   => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required'  => 'Le prénom est obligatoire.',
            'lastname.required'   => 'Le nom est obligatoire.',
            'email.required'      => 'L\'email est obligatoire.',
            'email.unique'        => 'Cette adresse email est déjà utilisée.',
            'email.email'         => 'L\'adresse email n\'est pas valide.',
            'role_id.required'    => 'Le rôle est obligatoire.',
            'password.required'   => 'Le mot de passe est obligatoire.',
            'password.min'        => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'  => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
