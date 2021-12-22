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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

//  Controller guide
//  This controller controls about admin data
class GuardianController extends Controller
{
    // to get all data from database
    public function index()
    {
        try {
            $guardians = Guardian::all();

            return ResponseFormatter::success(
                GuardianResource::collection($guardians),
                'Guardians data successfully loaded'
            );

        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Data not found',
                $e->getMessage(),
                404
            );
        }
    }

    // to show specific from database
    public function show($id)
    {
        try {
            $guardian = Guardian::findOrFail($id);

            return ResponseFormatter::success(
                new GuardianResource($guardian),
                'GUardian data successfully loaded'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Data not found',
                $e->getMessage(),
                404
            );
        }
    }

    // to store data to database
    public function store(StoreGuardianRequest $request)
    {
        try {
            $guardian = Guardian::create($request->validated());

            $guardian = Guardian::find($request->id);
            return ResponseFormatter::success(
                $guardian,
                'Guardian data was created successfully'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to create guardian data',
                $e->getMessage()
            );
        }
    }

    // to update data in database
    public function update(UpdateGuardianRequest $request, $id)
    {
        try {
            Guardian::where('id', $id)->update($request->validated());

            $guardian = Guardian::find($id);

            return ResponseFormatter::success(
                $guardian,
                'Guardian data successfully updated'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to update guardian data',
                $e->getMessage()
            );
        }
    }

    // to delete data from database
    public function destroy($id)
    {
        try {
            $land = Land::where('guardian_id',$id)->get();
            $landCount = $land->count();

            if ($landCount != 0) {
                $response = [
                    'guardian_id' => $id,
                    'number_of_connected_data' => $landCount
                ];
                return ResponseFormatter::error(
                    $response,
                    'guardian data is still connected to other data, please update the connected data first',
                    409
                );
            } else {
                Guardian::findOrFail($id)->delete();

                return ResponseFormatter::success(
                    'The data has been deleted successfully deleted',
                    'Successful delete data'
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
}
