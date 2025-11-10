<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use App\Traits\HandleJsonResponse;
use Illuminate\Http\JsonResponse;

class RegisterUserController extends Controller
{
    use HandleJsonResponse;

    public function store(RegisterUserRequest $request): JsonResponse
    {
        return User::create($request->validated())
            ? $this->success([], 'User registered successfully')
            : $this->error(["message" => 'User registration failed. Please try again later.']);
    }

    public function update() {}

    public function destroy() {}
}
