<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Override;

class ChangePasswordRequest extends FormRequest
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
            'revoke_current_session' => ['required', 'boolean'],
            'revoke_other_session' => ['required', 'boolean'],
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'different:current_password', 'confirmed', Password::defaults()],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'current_password.current_password' => 'The current password you provided is incorrect.',
            'password.different' => 'Your new password cannot be the same as your current password.',
        ];
    }
}
