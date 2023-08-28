<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserPostRequest;
use App\Http\Requests\Auth\RegisterUserPostRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * @param RegisterUserPostRequest $request
     * @param UserService             $userService
     * @return JsonResponse
     */
    public function register(RegisterUserPostRequest $request, UserService $userService): JsonResponse
    {
        $user = $userService->store($request->validated());
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json(['token' => $token]);
    }

    /**
     * @param LoginUserPostRequest $request
     * @return JsonResponse
     */
    public function login(LoginUserPostRequest $request, UserService $userService): JsonResponse
    {
        $user = $userService->get($request->email);
        if (!$user) {
            return response()->json([], 422);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([], 422);
        }

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json([
            'token' => $token
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->token();
        $token->revoke();

        return response()->json();
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function me()
    {
        return Auth::user();
    }
}
