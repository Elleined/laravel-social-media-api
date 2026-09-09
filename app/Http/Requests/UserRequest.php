<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;
use Password;

class UserRequest extends FormRequest
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
        // Either reuse this for update. email property approach russel or jerome
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users,email'],
            'password' => ['sometimes', 'string', Password::min(8)
                ->max(256)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()],
            'attachment' => ['sometimes', 'string', 'url', 'max:100'],
        ];
    }

    #[Override]
    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
        ];
    }
}
