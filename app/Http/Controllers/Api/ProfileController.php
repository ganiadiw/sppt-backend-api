<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
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

    public function checkImage(Request $request)
    {
        $payload = User::where('id', Auth::user()->id)->first();

        if ($request) {
            $image = $request->file('image');
            $imagePath = $image->store('images/profile');
            $imageName = $image->getClientOriginalName();
            Storage::delete($payload->image_path);
        }

        if ($request == null) {
            $imagePath = null;
            $imageName = null;
        }

        $imageData['imageName'] = $imageName;
        $imageData['imagePath'] = $imagePath;

        return $imageData;
    }

    // to update data in database
    public function update(UpdateProfileRequest $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $image = $this->checkImage($request);
        $validatedData = $request->except(['_method', 'image', 'new_password', 'confirmation_password',]);
        $validatedData['image_name'] = $image['imageName'];
        $validatedData['image_path'] = $image['imagePath'];
        
        if ($request->new_password == null || $request->new_password == ''){
            $validatedData['password'] = $user->password;

            $user->update($validatedData);                
        }

        if ($request->new_password != null){
            if ($request->confirmation_password != $request->new_password) {
                return response()->json([
                    'message' => 'The new and confirmation password not match'
                ], 406);
            }
            $validatedData['password'] = bcrypt($request->new_password);

            $user->update($validatedData);
        }

        return response()->json([
            'message' => 'Profile data successfully updated',
            'data' => new UserResource(User::where('id', Auth::user()->id)->first())
        ]);
    }
}
