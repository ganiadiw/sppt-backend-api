<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuardianRequest;
use App\Http\Requests\UpdateGuardianRequest;
use App\Http\Resources\GuardianResource;
use App\Models\Guardian;
use App\Models\Land;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function updateGuardianId(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sppt_objects' => 'required',
                'sppt_objects.*.nop' => 'required',
                'sppt_objects.*.guardian_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'The given data was invalid',
                    'errors' => $validator->errors(),
                ], 422);
            }

            foreach ($request->sppt_objects as $key => $value) {
                $land = Land::where('nop', $value['nop'])->first();

                Land::where('nop', $land->nop)->update([
                    'nop' => $land->nop,
                    'owner_id' => $land->owner_id,
                    'guardian_id' => $value['guardian_id'],
                    'rt' => $land->rt,
                    'rw' => $land->rw,
                    'village' => $land->village,
                    'road' => $land->road,
                    'sppt_persil_number' => $land->sppt_persil_number,
                    'block_number' => $land->block_number,
                    'land_area' => $land->land_area,
                    'land_area_unit' => $land->land_area_unit,
                    'building_area' => $land->building_area,
                    'building_area_unit' => $land->building_area_unit,
                ]);
            }

            return response()->json([
                'message' => 'SPPT data successfully updated'
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
