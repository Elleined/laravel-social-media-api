<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class LoginRequest extends FormRequest
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
        return [
            'email' => ['required', 'string', 'max:50', 'ends_with:@gmail.com', 'exists:users,email'],
            'password' => ['required', 'string', 'between:8,100'],
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'email.exists' => 'This email does not exists',
        ];
    }
}
