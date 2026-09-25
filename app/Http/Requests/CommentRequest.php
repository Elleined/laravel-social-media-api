<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isRequired = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'content' => ['bail', $isRequired, 'string'],
            'attachment' => ['bail', 'required', 'file', 'image', 'extensions:jpeg,png,jpg,gif', 'mimes:jpeg,png,jpg,gif', 'max:3072'],
        ];
    }
}
