<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
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
        try {
            $families = Family::orderBy('name')->get();

            return ResponseFormatter::success(
                FamilyResource::collection($families),
                'Families data successfully loaded'
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
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:100',
                'rt' => 'required|max:10',
                'rw' => 'required|max:10',
                'village' => 'required|max:100',
                'road' => 'max:100'
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }

            $family = Family::create([
                'name' => $request->name,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'village' => $request->village,
                'road' => $request->road
            ]);

            return ResponseFormatter::success(
                $family,
                'Family data was successfully created'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to create family data',
                $e->getMessage()
            );
        }
    }

    // to update in database
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:100',
                'rt' => 'required|max:10',
                'rw' => 'required|max:10',
                'village' => 'required|max:100',
                'road' => 'max:100'
                
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }

            Family::where('id', $id)->update([
                'name' => $request->name,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'village' => $request->village,
                'road' => $request->road
            ]);

            $family = Family::find($id);

            return ResponseFormatter::success(
                $family,
                'Family data successfully updated'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to update family data',
                $e->getMessage()
            );
        }
    }

    // to show specific data by id from database
    public function show($id)
    {
        try {
            $family = Family::findOrFail($id);

            return ResponseFormatter::success(
                $family,
                'Family data successfully loaded'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Data not found',
                $e->getMessage(),
                404
            );
        }
    }

    // to show specific data by name from database
    public function showByName($name)
    {
        try {
            $families = Family::where('name', 'LIKE', '%' . $name . '%')->get();

            return ResponseFormatter::success(
                $families,
                'Families data successfully loaded'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Data not found',
                $e->getMessage().
                404
            );
        }
    }

    // to delete data from database
    public function destroy($id)
    {
        try {
            $owner = Owner::where('family_id', $id)->get();
            $ownerCount = $owner->count();

            if ($ownerCount != 0) {
                $response = [
                    'family_id' => $id,
                    'number_of_connected_data' => $ownerCount
                ];
                return ResponseFormatter::error(
                    $response,
                    'family data is still connected to other data, please update the connected data first',
                    409
                );
            } else {
                Family::findOrFail($id)->delete();

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
