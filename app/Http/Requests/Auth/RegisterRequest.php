<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
        ];
    }

    /**
     * Переклад повідомлень про помилки валідації
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Вкажіть імʼя',
            'name.string' => 'Імʼя має бути текстом',
            'name.max' => 'Імʼя не може бути довшим за 255 символів',
            'email.required' => 'Вкажіть email',
            'email.email' => 'Вкажіть коректний email',
            'email.max' => 'Email не може бути довшим за 255 символів',
            'email.unique' => 'Користувач з таким email вже існує',
            'password.required' => 'Вкажіть пароль',
            'password.string' => 'Пароль має бути текстом',
            'password.min' => 'Пароль має містити щонайменше 8 символів',
            'password.confirmed' => 'Підтвердження пароля не збігається',
            'password_confirmation.required' => 'Підтвердіть пароль',
            'password_confirmation.string' => 'Підтвердження пароля має бути текстом',
            'password_confirmation.min' => 'Підтвердження пароля має містити щонайменше 8 символів',
        ];
    }
}
