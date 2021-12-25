<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuardianRequest;
use App\Http\Requests\UpdateGuardianRequest;
use App\Http\Resources\GuardianResource;
use App\Models\Guardian;
use App\Models\Land;

//  Controller guide
//  This controller controls about admin data
class GuardianController extends Controller
{
    // to get all data from database
    public function index()
    {
        return response()->json([
            'message' => 'Guardians data successfully loaded',
            'data' => GuardianResource::collection(Guardian::all())
        ]);
    }

    // to show specific from database
    public function show(Guardian $guardian)
    {
        return response()->json([
            'message' => 'Guardian data successfully loaded',
            'data' => new GuardianResource($guardian)
        ]);
    }

    // to store data to database
    public function store(StoreGuardianRequest $request)
    {
        $guardian = Guardian::create($request->validated());

        $guardian = Guardian::find($request->id);

        return response()->json([
            'message' => 'Guardian data was created successfully',
            'data' => $guardian
        ]);
    }

    // to update data in database
    public function update(UpdateGuardianRequest $request, Guardian $guardian)
    {
        $guardian->update($request->validated());

        return response()->json([
            'message' => 'Guardian data successfully updated',
            'data' => $guardian
        ]);
    }

    // to delete data from database
    public function destroy(Guardian $guardian)
    {
        $landCount = Land::where('guardian_id',$guardian->id)->count();

        if ($landCount != 0) {
            $response = [
                'guardian_id' => $guardian->id,
                'number_of_connected_data' => $landCount
            ];

            return response()->json([
                'message' => 'guardian data is still connected to other data, please update the connected data first',
                'connected_data' => $response
            ], 409);
        } else {
            $guardian->delete();

            return response()->json([
                'message' => 'The data has been deleted successfully'
            ]);
        }
    }
}
