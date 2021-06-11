<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\FamilyResource;
use App\Http\Resources\OwnerSearchResource;
use App\Http\Resources\SpptResource;
use App\Models\Family;
use App\Models\Land;
use App\Models\Owner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $lands = Land::where('owner_id', $owner->id)->get();

            return ResponseFormatter::success(
                OwnerSearchResource::collection($lands),
                'SPPT families data successfully loaded'
            );

        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Load data failed',
                $e->getMessage(),
                400
            );
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
        try {
            $this->validate($request, [
                'id' => 'required',
                'nop' => 'required|unique:lands,nop',
                'taxpayer_name' => 'required',
                'taxpayer_rt' => 'required',
                'taxpayer_rw' => 'required',
                'taxpayer_village' => 'required',
                'taxpayer_road' => 'required',
                'family_id' => 'required',
                'guardian_id' => 'required',
                'tax_object_name' => 'required',
                'tax_object_rt' => 'required',
                'tax_object_rw' => 'required',
                'tax_object_village' => 'required',
                'tax_object_road' => 'required',
                'land_area' => 'required',
                'land_area_unit' => 'required',
                'building_area' => 'required',
                'building_area_unit' => 'required',
            ]);
            
            DB::beginTransaction();
            $land = Land::where('id', $id)->first();
            // $owner = Owner::where('id', $land->owner_id)->first();
            $existingDatas = Land::all()->except($land->nop);

            foreach ($existingDatas as $existingData) {
                if ($request->nop == $existingData->nop){
                    return ResponseFormatter::error(
                        null,
                        'NOP already exist',
                        409
                    );
                }  
            }

            $land = Land::where('id', $id)->update([
                'nop' => $request->nop,
                'owner_id' => $land->owner_id,
                'name' => $request->tax_object_name,
                'guardian_id' => $request->guardian_id,
                'rt' => $request->tax_object_rt,
                'rw' => $request->tax_object_rw,
                'village' => $request->tax_object_village,
                'road' => $request->tax_object_road,
                'determination' => $request->determination,
                'sppt_persil_number' => $request->sppt_persil_number,
                'land_area' => $request->land_area,
                'land_area_unit' => $request->land_area_unit,
                'building_area' => $request->building_area,
                'building_area_unit' => $request->building_area_unit,
            ]);

            $owner = Owner::where('id', $land->owner_id)->update([
                'family_id' => $request->family_id,
                'name' => $request->taxpayer_name,
                'rt' => $request->taxpayer_rt,
                'rw' => $request->taxpayer_rw,
                'village' => $request->taxpayer_village,
                'road' => $request->taxpayer_road,
            ]);

            $sppt = Land::findOrFail($id);

            return ResponseFormatter::success(
                new SpptResource($sppt),
                'Data successfully updated'
            );
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Can\'t update SPPT data',
                $e->getMessage(),
                400
            );
        }
    }

    public function mutation(Request $request, $id)
    {

    }
}
