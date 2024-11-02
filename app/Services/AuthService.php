<?php

namespace App\Services;

use App\Contracts\Services\Auth\IAuthService;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthService implements IAuthService
{
    public function login(string $email, string $password): string
    {
        if (!Auth::attempt([User::EMAIL => $email, User::PASSWORD => $password])) {
            throw new Exception('Invalid user or password');
        }

        $token = auth()->user()->createToken('auth_token', [auth()->user()->access_level]);

        return $token->plainTextToken;
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

}
