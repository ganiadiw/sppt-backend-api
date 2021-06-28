<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
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

    public function update(Request $request)
    {
        try {            
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'occupation' => 'required',
                'username' => 'required',
                'email' => 'required',
                'image' => 'image|mimes:png,jpg,jpeg',
                '_method' => 'required',
                'old_password' => 'min:8',
                'new_password' => 'min:8'
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }

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

            //Check request password is null
            if ($request->new_password == null || $request->new_password == ''){
                $existingDatas = User::all()->except(Auth::id());

                foreach ($existingDatas as $existingData) {
                    if ($request['username'] == $existingData->username){
                        return ResponseFormatter::error(
                            null,
                            'Username already exist',
                            409
                        );
                    }  
                }

                foreach ($existingDatas as $existingData) {
                    if ($request['email'] == $existingData->email){
                        return ResponseFormatter::error(
                            null,
                            'Email already exist',
                            409
                        );
                    }  
                }        

                $payload = User::where('id', Auth::user()->id)->first();
                
                $user = User::where('id', Auth::user()->id)->update([
                    'name' => $request->name,
                    'occupation' => $request->occupation,
                    'username' => $request->username,
                    'email' => $request->email,
                    'image_name' => $imageName,
                    'image_path' => $imagePath,
                    'password' => $payload->password
                ]);

                
                $user = User::where('id', Auth::user()->id)->first();

                return ResponseFormatter::success(
                    new UserResource($user)
                );
            }

            if ($request->new_password != null){
                $existingDatas = User::all()->except(Auth::id());
                $payload = User::where('id', Auth::user()->id)->first();

                if (!Hash::check($request->old_password, $payload->password)) {
                    throw new Exception (
                        ResponseFormatter::error(
                            null,
                            'Wrong old password',
                            406
                        )
                    );
                }

                //check username or email is already exist
                foreach ($existingDatas as $existingData) {
                    if ($request['username'] == $existingData->username){
                        return ResponseFormatter::error(
                            null,
                            'Username already exist',
                            409
                        );
                    }  
                }

                foreach ($existingDatas as $existingData) {
                    if ($request['email'] == $existingData->email){
                        return ResponseFormatter::error(
                            null,
                            'Email already exist',
                            409
                        );
                    }  
                } 
                
                $user = User::where('id', Auth::user()->id)->update([
                    'name' => $request->name,
                    'occupation' => $request->occupation,
                    'username' => $request->username,
                    'email' => $request->email,
                    'image_name' => $imageName,
                    'image_path' => $imagePath,
                    'password' => bcrypt($request->new_password),
                ]);

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
