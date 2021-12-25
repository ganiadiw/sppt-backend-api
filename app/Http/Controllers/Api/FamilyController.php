<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FamilyResource;
use App\Models\Family;
use App\Models\Owner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//  Controller guide
//  This controller controls about family data
class FamilyController extends Controller
{
    // to get all data from database
    public function index()
    {
        return FamilyResource::collection(Family::orderBy('name')
                ->paginate(20))
                ->additional(['message' => 'Families data successfully loaded']);
    }

    // to store data to database
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'rt' => 'required|max:10',
            'rw' => 'required|max:10',
            'village' => 'required|max:100',
            'road' => 'max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid',
                'errors' => $validator->errors()
            ], 422);
        }

        $family = Family::create([
            'name' => $request->name,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'village' => $request->village,
            'road' => $request->road
        ]);

        return response()->json([
            'message' => 'Family data successfully created',
            'data' => $family
        ]);
    }

    // to update in database
    public function update(Request $request, Family $family)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'rt' => 'required|max:10',
            'rw' => 'required|max:10',
            'village' => 'required|max:100',
            'road' => 'max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid',
                'errors' => $validator->errors()
            ], 422);
        }

        Family::where('id', $family->id)->update([
            'name' => $request->name,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'village' => $request->village,
            'road' => $request->road
        ]);

        return response()->json([
            'message' => 'Family data successfully updated',
            'data' => Family::find($family->id)
        ]);
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
