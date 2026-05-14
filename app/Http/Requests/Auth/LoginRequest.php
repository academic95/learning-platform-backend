<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
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
            'email.required' => 'Вкажіть email',
            'email.email' => 'Вкажіть коректний email',
            'email.max' => 'Email не може бути довшим за 255 символів',
            'password.required' => 'Вкажіть пароль',
            'password.string' => 'Пароль має бути текстом',
            'password.max' => 'Пароль не може бути довшим за 255 символів',
        ];
    }
}
