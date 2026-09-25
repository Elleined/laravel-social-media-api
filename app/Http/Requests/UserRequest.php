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
            'first_name' => ['bail', $isRequired, 'string', 'max:50'],
            'last_name' => ['bail', $isRequired, 'string', 'max:50'],
            'email' => ['bail', $isRequired, 'string', 'max:50', 'ends_with:@gmail.com', $isEmailUnique],

            // Only available when user is admin. >eaning the even though its POST or PUT this will be available as long as the current user is admin
            'is_admin' => ['bail', Rule::excludeIf(! ($currentUser && $currentUser->is_admin)), 'required',  'boolean'],

            // Only available in POST
            'attachment' => ['bail', Rule::excludeIf(! $isCreate), 'sometimes', 'nullable', 'file', 'image', 'extensions:jpeg,png,jpg,gif', 'mimes:jpeg,png,jpg,gif', 'max:3072'],
            'password' => ['bail', Rule::excludeIf(! $isCreate), 'required', 'string', 'confirmed', Password::defaults()],
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
