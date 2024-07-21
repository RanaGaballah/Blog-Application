<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    
    public function register(RegisterRequest $request): JsonResponse
    {
        // create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // generate sanctum token
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        
        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ], 201);
    }
}
