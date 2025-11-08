<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username_or_email' => 'required|string',
            'password' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'username_or_email.required' => 'The username or email field is required',
            'password.required' => 'The password field is required'
        ];
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('username_or_email')) . '|' . $this->ip());
    }
}
