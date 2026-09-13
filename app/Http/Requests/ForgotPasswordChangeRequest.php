<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Override;

class ForgotPasswordChangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
            'token' => ['required', 'string'],
            'new_password' => ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'email.exists' => 'The token provided is invalid or has expired. Please check your email and try again.',
        ];
    }
}
