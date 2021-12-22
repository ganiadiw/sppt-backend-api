<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdministratorRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//  Controller guide
//  This controller controls about admin data
class AdministratorController extends Controller
{
    // To get all data from database
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
                'Data not found',
                $e->getMessage(),
                404
            );
        }
    }

    // To store data to database
    public function store (StoreAdministratorRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['role'] = 'admin';

            $user = User::create($validatedData);

            $user->assignRole('admin');

            return ResponseFormatter::success(
                new UserResource($user),
                'User data was successfully created'
            );


        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to create user data',
                $e->getMessage(),
                400
            );
        }
    }

    // to delete data from database
    public function destroy($id)
    {
        try {
            User::findOrFail($id)->delete();

            return ResponseFormatter::success(
                null,
                'Suceessful delete user data'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to delete user data',
                $e->getMessage()
            );
        }
    }
}
