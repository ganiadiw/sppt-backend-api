<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


//  Controller guide
//  This controller controls about admin data
class ProfileController extends Controller
{
    // to show profile
    public function show()
    {
        try {
            if (Auth::check()) {
                return ResponseFormatter::success(
                    new UserResource(Auth::user()),
                    'User profile sucessfully loaded'
                );
            }
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Data not found',
                $e->getMessage(),
                404
            );
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

                return ResponseFormatter::success(
                    new UserResource($user),
                    'Profile data successfully updated'
                );
            }

            if ($request->new_password != null){
                // $existingDatas = User::all()->except(Auth::id());
                $payload = User::where('id', Auth::user()->id)->first();

                if ($request->confirmation_password != $request->new_password) {
                    return ResponseFormatter::error(
                        null,
                        'The new and confirmation password don\'t match',
                        406
                    );
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

                return ResponseFormatter::success(
                    new UserResource($user),
                    'Profile data successfully updated'
                );
            }

        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to update profile data',
                $e->getMessage(),
                404
            );
        }
    }
}
