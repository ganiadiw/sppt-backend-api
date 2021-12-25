<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


//  Controller guide
//  This controller controls about admin data
class ProfileController extends Controller
{
    // to show profile
    public function show()
    {
        if (Auth::check()) {
            return response()->json([
                'message' => 'User profile sucessfully loaded',
                'data' => new UserResource(Auth::user())
            ]);
        }
    }

    // to update data in database
    public function update(UpdateProfileRequest $request)
    {
        try {
            //Check request password is null
            if ($request->new_password == null || $request->new_password == ''){      

                $payload = User::where('id', Auth::user()->id)->first();    
            
                if ($request->image) {
                    $image = $request->file('image');
                    $imagePath = $image->store('images/profile');
                    $imageName = $image->getClientOriginalName(); 
                    Storage::delete($payload->image_path);
                }

                if ($request->image == null) {
                    $imagePath = null;
                    $imageName = null;
                }

                $validatedData = $request->except(['_method', 'image']);
                $validatedData['image_name'] = $imageName;
                $validatedData['image_path'] = $imagePath;
                $validatedData['password'] = $payload->password;

                $user = User::where('id', Auth::user()->id)->update($validatedData);                
                $user = User::where('id', Auth::user()->id)->first();

                return response()->json([
                    'message' => 'Profile data successfully updated',
                    'data' => new UserResource($user)
                ]);
            }

            if ($request->new_password != null){
                $payload = User::where('id', Auth::user()->id)->first();

                if ($request->confirmation_password != $request->new_password) {
                    return response()->json([
                        'message' => 'The new and confirmation password not match'
                    ], 406);
                }

                if ($request->image) {
                    $image = $request->file('image');
                    $imagePath = $image->store('images/profile');
                    $imageName = $image->getClientOriginalName(); 
                    Storage::delete($payload->image_path);
                }

                if ($request->image == null) {
                    $imagePath = null;
                    $imageName = null;
                }

                $validatedData = $request->except(['_method', 'image', 'new_password', 'confirmation_password']);
                $validatedData['image_name'] = $imageName;
                $validatedData['image_path'] = $imagePath;
                $validatedData['password'] = bcrypt($request->new_password);

                $user = User::where('id', Auth::user()->id)->update($validatedData);
                $user = User::where('id', Auth::user()->id)->first();

                return response()->json([
                    'message' => 'Profile data successfully updated',
                    'data' => new UserResource($user)
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
