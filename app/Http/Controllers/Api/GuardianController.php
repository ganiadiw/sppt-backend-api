<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\GuardianResource;
use App\Models\Guardian;
use App\Models\Land;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GuardianController extends Controller
{
    public function index()
    {
        try {
            $guardians = Guardian::all();

            return ResponseFormatter::success(
                GuardianResource::collection($guardians),
                'Successfully get guardians data'
            );

        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Load data failed',
                $e->getMessage(),
                404
            );
        }
    }

    public function createGuardian(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|unique:guardians,id',
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }

            $guardian = Guardian::create([
                'id' => $request->id,
                'name' => $request->name
            ]);

            $guardian = Guardian::find($request->id);
            return ResponseFormatter::success(
                $guardian,
                'Guardian data created successfully'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Can\'t create data',
                $e->getMessage()
            );
        }
    }

    public function updateGuardian(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => [
                    'required',
                    Rule::unique('guardians')->ignore($id),
                ],
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }

            Guardian::where('id', $id)->update([
                'id' => $request->id,
                'name' => $request->name,
            ]);

            $guardian = Guardian::find($id);

            return ResponseFormatter::success(
                $guardian,
                'Guardian data updated successfully'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Can\'t update data',
                $e->getMessage()
            );
        }
    }

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
                    'The data has been deleted',
                    'Successfully delete data'
                );
            }
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Something wrong',
                $e->getMessage()
            );
        }
    }
}
