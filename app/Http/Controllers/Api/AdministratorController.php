<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdministratorRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;

//  Controller guide
//  This controller controls about admin data
class AdministratorController extends Controller
{
    // To get all data from database
    public function index()
    {
        try {
            return UserResource::collection(User::paginate(10))
                    ->additional(['message' => 'Users data sucessfully loaded']);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);            
        }
    }

    // To store data to database
    public function store (StoreAdministratorRequest $request)
    {
        try {
            $user = User::create($request->validated() + [
                'role' => 'admin'
            ]);

            $user->assignRole('admin');

            return response()->json([
                'message' => 'User data was successfully created',
                'data' => new UserResource($user)
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // to delete data from database
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Succesful delete user data'
        ], 200);
    }
}
