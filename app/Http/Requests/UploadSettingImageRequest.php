<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadSettingImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'key' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,gif,webp|max:2048',
            'group' => 'nullable|in:branding,ui,system',
            'is_public' => 'sometimes|boolean',
        ];
    }
}
