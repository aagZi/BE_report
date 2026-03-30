<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateSettingRequest extends FormRequest
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
            'key' => 'required|string|max:255',
            'value' => 'nullable',
            'type' => 'required|in:string,image,json,boolean',
            'group' => 'nullable|in:branding,ui,system',
            'is_public' => 'sometimes|boolean',
        ];
    }
}
