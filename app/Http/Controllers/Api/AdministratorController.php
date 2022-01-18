<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdministratorRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

//  Controller guide
//  This controller controls about admin data
class AdministratorController extends Controller
{
    // To get all data from database
    public function index()
    {
        return UserResource::collection(User::paginate(10))
                ->additional(['message' => 'Users data sucessfully loaded']);
    }

    // To store data to database
    public function store (StoreAdministratorRequest $request)
    {
        $user = User::create($request->except('password') + [
            'role' => 'admin',
            'password' => bcrypt($request->password)
        ]);

        $user->assignRole('admin');

        return response()->json([
            'message' => 'User data was successfully created',
            'data' => new UserResource($user)
        ], 200);
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
