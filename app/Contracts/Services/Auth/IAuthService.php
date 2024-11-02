<?php

namespace App\Contracts\Services\Auth;

use App\Models\User;

interface IAuthService
{
    /**
     * User Login.
     *
     * @param string $email
     * @param string $password
     * @return string
     */
    public function login(string $email, string $password): string;

    /**
     * User Logout
     *
     * @param User $user
     * @return void
     */
    public function logout(User $user): void;
}
