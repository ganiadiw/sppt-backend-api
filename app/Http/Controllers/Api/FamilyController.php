<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\FamilyResource;
use App\Models\Family;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FamilyController extends Controller
{
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
                'Families data failed to load',
                $e->getMessage(),
                404
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'village' => 'required'
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

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'village' => 'required'
                
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
                'Failed to load family data',
                $e->getMessage()
            );
        }
    }

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
                'Failed to load families data',
                $e->getMessage()
            );
        }
    }
}
