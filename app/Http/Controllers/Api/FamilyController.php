<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\FamilyResource;
use App\Models\Family;
use Exception;
use Illuminate\Http\Request;

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
                'Load data failed',
                $e->getMessage(),
                400
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $family = Family::create([
                'name' => $request->name,
            ]);

            return ResponseFormatter::success(
                $family,
                'Family data created successfully'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Can\'t create data',
                $e->getMessage(),
                400
            );
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Family::where('id', $id)->update([
                'name' => $request->name
            ]);

            $family = Family::find($id);

            return ResponseFormatter::success(
                $family,
                'Family data updated successfully'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Can\'t update data',
                $e->getMessage(),
                400
            );
        }
    }

    public function show($id)
    {
        try {
            $family = Family::findOrFail($id);

            return ResponseFormatter::success(
                $family,
                'Family data loaded successfully'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Can\'t show data',
                $e->getMessage(),
                400
            );
        }
    }
}
