<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use App\Traits\HandleJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class RegisterUserController extends Controller
{
    use HandleJsonResponse;

    public function store(RegisterUserRequest $request): JsonResponse
    {

        if ($user = User::create($request->validated())) {

            $token = $user->createToken('auth_token')->plainTextToken;

            $user->sendEmailVerificationNotification();

            return  $this->success([
                'token' => $token
            ], 'Registered successfully. Please check your email to verify your account.');
        }

        return $this->error(["message" => 'Registration failed. Please try again later.']);
    }

    public function verifyEmail($id, $hash): RedirectResponse
    {
        $user = User::find($id);

        if (!hash_equals((string) $hash, sha1($user->email))) {
            abort(403, 'Invalid verification link.');
        }

        $user->markEmailAsVerified();

        return redirect('http://localhost:4200/auth/sign-in');
    }

    public function update() {}

    public function destroy() {}
}
