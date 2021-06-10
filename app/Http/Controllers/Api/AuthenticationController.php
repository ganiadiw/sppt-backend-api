<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            $credential = request(['username', 'password']);

            if (!Auth::attempt($credential)) {
                return ResponseFormatter::error(
                    null,
                    'Unautorized',
                    401
                );
            }

            $user = User::where('username', $request->username)->first();
            if (!Hash::check($request->password, $user->password)) {
                return ResponseFormatter::error(
                    null,
                    'Unautorized',
                    401
                );
            }

            $token = $user->createToken('AuthorizationToken')->plainTextToken;

            if ($user->hasRole('super-admin')) {
                return ResponseFormatter::success([
                    'access_token' => $token,
                    'token_type' => 'Bearer Token',
                    'user' => $user
                ], 'Authenticated Successfully');
            }

            return ResponseFormatter::success([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated Successfully');

        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e
            ], 'Authentication Failed');
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return ResponseFormatter::success('Token Revoked, Successfully Loged Out');
    }
}
