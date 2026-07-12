<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('id') ?? $this->route('user')?->id;

        return [
            'firstname'  => 'required|string|max:100',
            'lastname'   => 'required|string|max:100',
            'email'      => 'required|email|max:255|unique:users,email,' . $userId,
            'role_id'    => 'required|exists:roles,id',
            'service_id' => 'nullable|exists:services,id',
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required' => 'Le prénom est obligatoire.',
            'lastname.required'  => 'Le nom est obligatoire.',
            'email.required'     => 'L\'email est obligatoire.',
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'email.email'        => 'L\'adresse email n\'est pas valide.',
            'role_id.required'   => 'Le rôle est obligatoire.',
        ];
    }
}
