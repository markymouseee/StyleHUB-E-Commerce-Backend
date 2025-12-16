<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Traits\HandleJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticatedTokenController extends Controller
{

    use HandleJsonResponse;

    public function store(LoginRequest $request): JsonResponse
    {
        if (RateLimiter::tooManyAttempts($request->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($request->throttleKey());

            return $this->error(["message" => "Too many attempts. Try again in {$seconds} seconds."]);
        }

        $credentials = $request->only('username_or_email', 'password');

        $fieldType = filter_var($credentials['username_or_email'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $user = User::where($fieldType, $credentials['username_or_email'])->first();

        if (!$user) {
            RateLimiter::hit($request->throttleKey());

            return $this->error(["message" => "The provided {$fieldType} not found."], 422);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($request->throttleKey());

            return $this->error(["message" => "Wrong password, please try again."], 422);
        }

        RateLimiter::clear($request->throttleKey());

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function destroy(Request $request)
    {


        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful'
        ]);
    }
}
