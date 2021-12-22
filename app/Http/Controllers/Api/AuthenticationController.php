<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


//  Controller guide
//  This controller controls about authentication
class AuthenticationController extends Controller
{
    // for login function
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }

            $credential = request(['username', 'password']);

            if (!Auth::attempt($credential)) {
                return ResponseFormatter::error(
                    null,
                    'Invalid username or password',
                    401
                );
            }

            $user = User::where('username', $request->username)->first();

            $token = $user->createToken(Auth::user()->name)->plainTextToken;

            if ($user->hasRole('super-admin')) {
                return ResponseFormatter::success([
                    'access_token' => $token,
                    'token_type' => 'Bearer Token',
                    'user' => new UserResource($user)
                ], 'Authentication successful');
            }

            return ResponseFormatter::success([
                'access_token' => $token,
                'token_type' => 'Bearer Token',
                'user' => new UserResource($user)
            ], 'Authentication successful');

        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Authenticaion failed',
                $e->getMessage()
            );
        }
    }

    // for logout function
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return ResponseFormatter::success('Token revoked, successful loged out');
    }
}
