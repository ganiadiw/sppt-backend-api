<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\FamilyResource;
use App\Http\Resources\SpptResource;
use App\Models\Family;
use App\Models\Land;
use App\Models\Owner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpptController extends Controller
{
    public function familyIndex()
    {
        try {
            $families = Family::all();

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

    public function ownerSearch($nop)
    {
        try {
            $land = Land::where('nop', $nop)->first();
            $owner = Owner::where('id', $land->owner_id)->first();
            $owners = Owner::where('family_id', $owner->family_id)->get();
            $lands = Land::where('owner_id', $owner->id)->get();
            // foreach ($owners as $owner) {
            //     $lands = Land::where('owner_id', $owner->id)->get();
            //     return ResponseFormatter::success(
            //         $lands
            //     );
            // }

            return ResponseFormatter::success(
                $owners
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function showSppt($id)
    {
        try {
            $sppt = Land::findOrFail($id);

            return ResponseFormatter::success(
                new SpptResource($sppt),
                'Data successfully loaded'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Load data failed',
                $e->getMessage(),
                400
            );
        }
    }

    public function update(Request $request, $id)
    {

    }

    public function mutation(Request $request, $id)
    {

    }
}
