<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginAuthenticationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


//  Controller guide
//  This controller controls about authentication
class AuthenticationController extends Controller
{
    // for login function
    public function login(LoginAuthenticationRequest $request)
    {
        try {
            if (!Auth::attempt($request->validated())) {
                return response()->json([
                    'message' => 'Invalid username or password',
                ], 401);
            }

            $user = User::where('username', $request->username)->first();

            $token = $user->createToken(Auth::user()->name)->plainTextToken;

            if ($user->hasRole('super-admin')) {
                return response()->json([
                    'message' => 'Authentication successful',
                    'data' => [
                        'access_token' => $token,
                        'token_type' => 'Bearer Token',
                        'user' => new UserResource($user)
                    ]
                ]);
            }

            return response()->json([
                'message' => 'Authentication successful',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer Token',
                    'user' => new UserResource($user)
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // for logout function
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Token revoked, successful loged out'], 200);
    }
}
