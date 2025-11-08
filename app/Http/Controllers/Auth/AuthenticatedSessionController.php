<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request): JsonResponse
    {
        if (RateLimiter::tooManyAttempts($request->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($request->throttleKey());

            return response()->json([
                'status' => 'error',
                'errors' => [
                    'message' => ["Too many attempts. Try again in {$seconds} seconds."]
                ]
            ], 429);
        }

        $credentials = $request->only('username_or_email', 'password');

        $fieldType = filter_var($credentials['username_or_email'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $user = User::where($fieldType, $credentials['username_or_email'])->first();

        if (!$user) {
            RateLimiter::hit($request->throttleKey());
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'message' => ["The provided {$fieldType} not found."]
                ]
            ], 422);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($request->throttleKey());
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'message' => ['Wrong password, please try again.']
                ]
            ], 422);
        }

        RateLimiter::clear($request->throttleKey());

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful'
        ]);
    }
}
