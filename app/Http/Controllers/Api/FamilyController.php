<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\FamilyRequest;
use App\Http\Resources\FamilyResource;
use App\Models\Family;
use App\Models\Owner;
use Exception;

//  Controller guide
//  This controller controls about family data
class FamilyController extends Controller
{
    // to get all data from database
    public function index()
    {
        try {
            return FamilyResource::collection(Family::orderBy('name')
                    ->paginate(20))
                    ->additional(['message' => 'Families data successfully loaded']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // to store data to database
    public function store(FamilyRequest $request)
    {
        try {
            $family = Family::create($request->validated());

            return response()->json([
                'message' => 'Family data was successfully created',
                'data' => $family
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // to update in database
    public function update(FamilyRequest $request, $id)
    {
        try {
            Family::where('id', $id)->update($request->validated());

            $family = Family::find($id);

            return response()->json([
                'message' => 'Family data successfully updated',
                'data' => $family
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // to show specific data by id from database
    public function show(Family $family)
    {
        return response()->json([
            'message' => 'Family data successfully loaded',
            'data' => new FamilyResource($family)
        ]);
    }

    // to show specific data by name from database
    public function showByName($name)
    {
        try {
            $families = Family::where('name', 'LIKE', '%' . $name . '%')->get();

            return response()->json([
                'message' => 'Families data successfully loaded',
                'data' => $families
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // to delete data from database
    public function destroy(Family $family)
    {
        $owner = Owner::where('family_id', $family->id)->get();
        $ownerCount = $owner->count();

        if ($ownerCount != 0) {
            $response = [
                'family_id' => $family->id,
                'number_of_connected_data' => $ownerCount
            ];

            return response()->json([
                'message' => 'family data is still connected to other data, please update the connected data first',
                'connected_data' => $response
            ], 409);
        } else {
            $family->delete();

            return response()->json([
                'message' => 'The data has been deleted successfully'
            ]);
        }
    }
}
