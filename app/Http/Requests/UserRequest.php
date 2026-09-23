<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Override;

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
        $isCreate = $this->isMethod('POST');

        $isRequired = $isCreate ? 'required' : 'sometimes';
        $isEmailUnique = $isCreate
            ? 'unique:users,email'
            : Rule::unique('users', 'email')->ignore($this->route('user'), 'id');

        return [
            'first_name' => [$isRequired, 'string', 'max:50'],
            'last_name' => [$isRequired, 'string', 'max:50'],
            'email' => [$isRequired, 'string', 'max:50', 'ends_with:@gmail.com', $isEmailUnique],
            'password' => [Rule::excludeIf(! $isCreate), 'required', 'string', 'confirmed', Password::defaults()],
            'attachment' => ['sometimes', 'string', 'max:100', 'url'],
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
