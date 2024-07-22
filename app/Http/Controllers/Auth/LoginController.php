<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\{ErrorResource,UserResource};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;


class LoginController extends Controller
{
    /**
     * Handle user login and return a Sanctum token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(new ErrorResource(404, 'User not found'), 404);
        }
        
        if ((Hash::check($request->password, $user->password)) || ($user->password == null)) {
            $user->tokens()->delete();
            $token = $user->createToken(request()->userAgent())->plainTextToken;
            return response()->json([
                'token' => $token,
                'user' => new UserResource($user),
            ], 200);
        } else {
            return response()->json(new ErrorResource(401, 'Incorrect Password',), 401);
        }
    }

}
