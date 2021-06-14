<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdministratorController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();

            return ResponseFormatter::success(
                UserResource::collection($users),
                'Users data sucessfully loaded'
            );

        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Load data failed',
                $e->getMessage(),
                404
            );
        }
    }

    public function store (Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'username' => 'required|unique:users,username',
                'email' => 'required|unique:users,email',
                'occupation' => 'required',
                'password' => 'required|min:8'
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }

            $user = User::where('username', $request->username)->where('email', $request->email)->first();

            if($user) {
                return ResponseFormatter::error(
                    null,
                    'Username or email already exist',
                    406
                );
            }

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'occupation' => $request->occupation,
                'password' => bcrypt($request->password),
                'role' => 'admin'
            ]);

            $user->assignrole('admin');

            return ResponseFormatter::success(
                $user,
                'User created successfully'
            );


        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Something wrong!',
                $e->getMessage(),
            );
        }
    }

    public function destroy($id)
    {
        try {
            User::findOrFail($id)->delete();

            return ResponseFormatter::success(
                'The data has been deleted',
                'Successfully delete data'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Something wrong',
                $e->getMessage()
            );
        }
    }
}
