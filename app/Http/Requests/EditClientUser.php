<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditClientUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_client' => 'required|integer|exists:client,id_cli',
            'gender' => 'required|in:male,female',
            'name_user' => 'required|string|max:255',
            // 'email' => 'nullable|email|unique:users,email,' . $this->route('id'),
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('id'))
            ],
            'id_cabang' => 'required|integer|exists:cabang,id_ca',
            'user_phone' => 'required|string',

            'password' => 'nullable|string|min:6',
            'photo' => 'nullable|string',
            'p_type' => 'nullable|string',
            'level' => 'nullable',
            'alamat_client' => 'nullable|string',
        ];
    }
}
