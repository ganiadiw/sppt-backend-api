<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewMutationResource;
use App\Http\Resources\OriginMutationResource;
use App\Http\Resources\OwnerSearchResource;
use App\Http\Resources\SpptResource;
use App\Models\Land;
use App\Models\MutationHistory;
use App\Models\Owner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SpptController extends Controller
{
    public function showByFamily($nop)
    {
        try {
            $land = Land::where('nop', $nop)->first();
            
            $owner = Owner::where('id', $land->owner_id)->first();
            $owners = Owner::with('land')->where('family_id', $owner->family_id)->get();
            
            return ResponseFormatter::success(
                OwnerSearchResource::collection($owners),
                'SPPT data successfully loaded'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Data not found',
                $e->getMessage(),
                404
            );
        }
    }

    public function showByFamilyId($id)
    {
        try {
            $owners = Owner::with('land')->where('family_id', $id)->get();
            
            return ResponseFormatter::success(
                OwnerSearchResource::collection($owners),
                'SPPT data successfully loaded'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Data not found',
                $e->getMessage(),
                404
            );
        }
    }

    public function show($nop)
    {
        try {
            $sppt = Land::where('nop', $nop)->first();

            return ResponseFormatter::success(
                new SpptResource($sppt),
                'SPPT data successfully loaded'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Data not found',
                $e->getMessage(),
                404
            );
        }
    }

    public function showByGuardian($guardian_id)
    {
        try {
            $sppt = Land::where('guardian_id', $guardian_id)->get();

            return ResponseFormatter::success(
                SpptResource::collection($sppt),
                'SPPT data successfully loaded'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Data not found',
                $e->getMessage(),
                404
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nop' => 'required|unique:lands,nop',
                'taxpayer_name' => 'required|max:100',
                'taxpayer_rt'  => 'required|max:10',
                'taxpayer_rw' => 'required|max:10',
                'taxpayer_village' => 'required|max:100',
                'taxpayer_road' => 'max:100',
                'guardian_id' => 'required',
                'tax_object_rt' => 'required|max:10',
                'tax_object_rw' => 'required|max:10',
                'tax_object_village' => 'required|max:100',
                'tax_objcet_road' => 'max:100',
                'sppt_persil_number' => 'max:50',
                'block_number' => 'required|max:20',
                'land_area' => 'required',
                'land_area_unit' => 'required|max:10',
                'building_area' => 'required',
                'building_area_unit' => 'required|max:10',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }

            DB::beginTransaction();
            $owner = Owner::create([
                'family_id' => $request->family_id,
                'name' => $request->taxpayer_name,
                'rt' => $request->taxpayer_rt,
                'rw' => $request->taxpayer_rw,
                'village' => $request->taxpayer_village,
                'road' => $request->taxpayer_road,
            ]);
                
            $land = Land::create([
                'nop' => $request->nop,
                'owner_id' => $owner->id,
                'guardian_id' => $request->guardian_id,
                'rt' => $request->tax_object_rt,
                'rw' => $request->tax_object_rw,
                'village' => $request->tax_object_village,
                'road' => $request->tax_object_road,
                'determination' => $request->determination,
                'sppt_persil_number' => $request->sppt_persil_number,
                'block_number' => $request->block_number,
                'land_area' => $request->land_area,
                'land_area_unit' => $request->land_area_unit,
                'building_area' => $request->building_area,
                'building_area_unit' => $request->building_area_unit,
            ]);
            
            DB::commit();

            $sppt = Land::where('nop', $request->nop)->first();

            return ResponseFormatter::success(
                new SpptResource($sppt),
                'Successfully created SPPT data'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Failed to create SPPT data',
                $e->getMessage(),
                400
            );
        }
    }

    public function update(Request $request, $nop)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nop' => 'required',
                'taxpayer_name' => 'required|max:100',
                'taxpayer_rt'  => 'required|max:10',
                'taxpayer_rw' => 'required|max:10',
                'taxpayer_village' => 'required|max:100',
                'taxpayer_road' => 'max:100',
                'guardian_id' => 'required',
                'tax_object_rt' => 'required|max:10',
                'tax_object_rw' => 'required|max:10',
                'tax_object_village' => 'required|max:100',
                'tax_objcet_road' => 'max:100',
                'sppt_persil_number' => 'max:50',
                'block_number' => 'required|max:20',
                'land_area' => 'required',
                'land_area_unit' => 'required|max:10',
                'building_area' => 'required',
                'building_area_unit' => 'required|max:10',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }
            
            DB::beginTransaction();
            $land = Land::where('nop', $nop)->first();
            $existingDatas = Land::all()->except($land->id);

            foreach ($existingDatas as $existingData) {
                if ($request->nop == $existingData->nop){
                    return ResponseFormatter::error(
                        null,
                        'NOP already exist',
                        409
                    );
                }  
            }

            Land::where('nop', $land->nop)->update([
                'nop' => $request->nop,
                'owner_id' => $land->owner_id,
                'guardian_id' => $request->guardian_id,
                'rt' => $request->tax_object_rt,
                'rw' => $request->tax_object_rw,
                'village' => $request->tax_object_village,
                'road' => $request->tax_object_road,
                'determination' => $request->determination,
                'sppt_persil_number' => $request->sppt_persil_number,
                'block_number' => $request->block_number,
                'land_area' => $request->land_area,
                'land_area_unit' => $request->land_area_unit,
                'building_area' => $request->building_area,
                'building_area_unit' => $request->building_area_unit,
            ]);

            Owner::where('id', $land->owner_id)->update([
                'family_id' => $request->family_id,
                'name' => $request->taxpayer_name,
                'rt' => $request->taxpayer_rt,
                'rw' => $request->taxpayer_rw,
                'village' => $request->taxpayer_village,
                'road' => $request->taxpayer_road,
            ]);
            DB::commit();

            $sppt = Land::where('nop', $request->nop)->first();

            return ResponseFormatter::success(
                new SpptResource($sppt),
                'SPPT data successfully loaded'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Failed to update SPPT data',
                $e->getMessage(),
                400
            );
        }
    }

    public function mutation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nop' => 'required|unique:lands,nop',
                'nop_target' => 'required',
                'taxpayer_name' => 'required|max:100',
                'taxpayer_rt'  => 'required|max:10',
                'taxpayer_rw' => 'required|max:10',
                'taxpayer_village' => 'required|max:100',
                'taxpayer_road' => 'max:100',
                'guardian_id' => 'required',
                'tax_object_rt' => 'required|max:10',
                'tax_object_rw' => 'required|max:10',
                'tax_object_village' => 'required|max:100',
                'tax_objcet_road' => 'max:100',
                'sppt_persil_number' => 'max:50',
                'block_number' => 'required|max:20',
                'land_area' => 'required',
                'land_area_unit' => 'required|max:10',
                'building_area' => 'required',
                'building_area_unit' => 'required|max:10',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }
    
            $landTarget = Land::where('nop', $request->nop_target)->first();
            if (!$landTarget) {
                return ResponseFormatter::error(
                    null,
                    'SPPT target data not found',
                    404
                );
            }
            
            $remainingArea = $landTarget->land_area - $request->land_area;
            $remainingBuildingArea = $landTarget->building_area - $request->building_area;
            $originLand = Land::where('nop', $request->nop_target)->first();
            $originOwner = Owner::find($originLand->owner_id);

            if ($remainingArea < 0) {
                return ResponseFormatter::error(
                    null,
                    'The area of ​​the target land is zero, so it cannot reduce the area of ​​the land, make sure the mutation value does not exceed the value of the target land area',
                    400
                );
            }

            DB::beginTransaction();

            $owner = Owner::create([
                'family_id' => $request->family_id,
                'name' => $request->taxpayer_name,
                'rt' => $request->taxpayer_rt,
                'rw' => $request->taxpayer_rw,
                'village' => $request->taxpayer_village,
                'road' => $request->taxpayer_road,
            ]);

            if ($landTarget->building_area != 0) {
                if ($remainingBuildingArea < 0) {
                    DB::rollBack();
                    return ResponseFormatter::error(
                        null,
                        'The area of ​​the target building is zero, so it cannot reduce the area of ​​the building, make sure the mutation value does not exceed the value of the target building area',
                        400
                    );
                }

                Land::where('nop', $request->nop_target)->update([
                    'nop' => $originLand->nop,
                    'owner_id' => $originLand->owner_id,
                    'guardian_id' => $originLand->guardian_id,
                    'rt' => $originLand->rt,
                    'rw' => $originLand->rw,
                    'village' => $originLand->village,
                    'road' => $originLand->road,
                    'determination' => $originLand->determination,
                    'sppt_persil_number' => $originLand->sppt_persil_number,
                    'block_number' => $originLand->block_number,
                    'land_area' => $remainingArea,
                    'land_area_unit' => $originLand->land_area_unit,
                    'building_area' => $remainingBuildingArea,
                    'building_area_unit' => $originLand->building_area_unit,
                ]);
            } else {
                Land::where('nop', $request->nop_target)->update([
                    'nop' => $originLand->nop,
                    'owner_id' => $originLand->owner_id,
                    'guardian_id' => $originLand->guardian_id,
                    'rt' => $originLand->rt,
                    'rw' => $originLand->rw,
                    'village' => $originLand->village,
                    'road' => $originLand->road,
                    'determination' => $originLand->determination,
                    'sppt_persil_number' => $originLand->sppt_persil_number,
                    'block_number' => $originLand->block_number,
                    'land_area' => $remainingArea,
                    'land_area_unit' => $originLand->land_area_unit,
                    'building_area' => $originLand->building_area,
                    'building_area_unit' => $originLand->building_area_unit,
                ]);
            }
                
            $newLand = Land::create([
                'nop' => $request->nop,
                'owner_id' => $owner->id,
                'guardian_id' => $request->guardian_id,
                'rt' => $request->tax_object_rt,
                'rw' => $request->tax_object_rw,
                'village' => $request->tax_object_village,
                'road' => $request->tax_object_road,
                'determination' => $request->determination,
                'sppt_persil_number' => $request->sppt_persil_number,
                'block_number' => $request->block_number,
                'land_area' => $request->land_area,
                'land_area_unit' => $request->land_area_unit,
                'building_area' => $request->building_area,
                'building_area_unit' => $request->building_area_unit,
            ]);
            
            $newOwner = Owner::where('id', $owner->id)->first();
            $originOwner = Owner::where('id', $originLand->owner->id)->first();
            $newLand = Land::where('nop', $request->nop)->first();

            if ($remainingArea <= 0) {
                Owner::where('id', $originOwner->id)->delete();
            }

            MutationHistory::create([
                'modified_by' => Auth::user()->name,
                'new_taxpayer_name' => $newOwner->name,
                'new_taxpayer_village' => $newOwner->village,
                'new_taxpayer_road' => $newOwner->road,
                'new_nop' => $newLand->nop,
                'guardian_id' => $newLand->guardian_id,
                'new_tax_object_road' => $newLand->road,
                'sppt_persil_number' => $newLand->spt_persil_number,
                'block_number' => $originLand->block_number,
                'new_land_area' => $newLand->land_area,
                'new_land_area_unit' => $newLand->land_area_unit,
                'new_building_area' => $newLand->building_area,
                'new_building_area_unit' => $newLand->building_area_unit,
                'taxpayer_source_name' => $originOwner->name,
                'taxpayer_source_village' => $originOwner->village,
                'taxpayer_source_road' => $originOwner->road,
                'source_nop' => $originLand->nop,
                'tax_object_road' => $originLand->road,
                'land_source_area' => $originLand->land_area,
                'land_source_area_unit' => $originLand->land_area_unit,
                'building_source_area' => $originLand->building_area,
                'building_source_area_unit' => $originLand->building_area,
            ]);

            DB::commit();

            return ResponseFormatter::success(
                [
                    new OriginMutationResource($originLand),
                    new NewMutationResource($newLand),
                ],
                'Mutation data was successfully created'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Failed to create mutation data',
                $e->getMessage()
            );
        }   
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
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
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
                    'determination' => $land->determination,
                    'sppt_persil_number' => $land->sppt_persil_number,
                    'block_number' => $land->block_number,
                    'land_area' => $land->land_area,
                    'land_area_unit' => $land->land_area_unit,
                    'building_area' => $land->building_area,
                    'building_area_unit' => $land->building_area_unit,
                ]);
            }

            return ResponseFormatter::success(
                'SPPT data successfully updated',
                'SPPT data successfully updated'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to update SPPT data',
                $e->getMessage(),
                400
            );
        }
    }

    public function updateFamilyId(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sppt_objects' => 'required',
                'sppt_objects.*.id' => 'required',
                'sppt_objects.*.family_id' => 'required',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }

            foreach ($request->sppt_objects as $key => $value) {
                $owner = Owner::where('id', $value['id'])->first();

                Owner::where('id', $owner->id)->update([
                    'family_id' => $value['family_id'],                        
                    'name' => $owner->name,
                    'rt' => $owner->rt,
                    'rw' => $owner->rw,
                    'village' => $owner->village,
                    'road' => $owner->road
                ]);
            }

            return ResponseFormatter::success(
                'SPPT data successfully updated',
                'SPPT data successfully updated'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to update SPPT data',
                $e->getMessage(),
                400
            );
        }
    }

    public function destroy ($id)
    {
        try {
            Owner::findOrFail($id)->delete();
            return ResponseFormatter::success(
                'The data has been deleted',
                'SPPT data successfully deleted'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to delete SPPT data',
                $e->getMessage(),
                404
            );
        }
    }
}