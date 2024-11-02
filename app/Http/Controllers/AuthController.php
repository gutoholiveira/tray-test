<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    /**
     * @var App\Services\AuthService
     */
    private $auth_service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthService $auth_service)
    {
        $this->auth_service = $auth_service;
    }

    /**
     * Make the customer or store login
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $token = $this->auth_service->login($request->email, $request->password);

        return response()->json(['message' => 'success', 'data' => ['token' => $token]], Response::HTTP_OK);
    }

    /**
     * Make the logout
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->auth_service->logout(Auth::user());

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }
}
