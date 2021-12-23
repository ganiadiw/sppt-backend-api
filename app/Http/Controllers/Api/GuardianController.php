<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuardianRequest;
use App\Http\Requests\UpdateGuardianRequest;
use App\Http\Resources\GuardianResource;
use App\Models\Guardian;
use App\Models\Land;
use Exception;

//  Controller guide
//  This controller controls about admin data
class GuardianController extends Controller
{
    // to get all data from database
    public function index()
    {
        try {
            $guardians = Guardian::all();

            return response()->json([
                'message' => 'Guardians data successfully loaded',
                'data' => GuardianResource::collection($guardians)
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
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
        try {
            $guardian = Guardian::create($request->validated());

            $guardian = Guardian::find($request->id);

            return response()->json([
                'message' => 'Guardian data was created successfully',
                'data' => $guardian
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // to update data in database
    public function update(UpdateGuardianRequest $request, Guardian $guardian)
    {
        try {
            Guardian::where('id', $guardian->id)->update($request->validated());

            $guardian = Guardian::find($guardian->id);

            return response()->json([
                'message' => 'Guardian data successfully updated',
                'data' => $guardian
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // to delete data from database
    public function destroy(Guardian $guardian)
    {
        $landCount = Land::where('guardian_id',$guardian->id)->get()->count();

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
