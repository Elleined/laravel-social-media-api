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
        $currentUser = $this->user();

        $isRequired = $isCreate ? 'required' : 'sometimes';
        $isEmailUnique = $isCreate
            ? 'unique:users,email'
            : Rule::unique('users', 'email')->ignore($this->route('user'), 'id');

        return [
            'first_name' => [$isRequired, 'string', 'max:50'],
            'last_name' => [$isRequired, 'string', 'max:50'],
            'email' => [$isRequired, 'string', 'max:50', 'ends_with:@gmail.com', $isEmailUnique],
            'attachment' => ['sometimes', 'string', 'max:100', 'url'],
            'password' => [Rule::excludeIf(! $isCreate), 'required', 'string', 'confirmed', Password::defaults()], // Only required if http method is post
            'is_admin' => [Rule::excludeIf(! ($currentUser && $currentUser->is_admin)), 'required',  'boolean'], // Only required if current user is admin
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
