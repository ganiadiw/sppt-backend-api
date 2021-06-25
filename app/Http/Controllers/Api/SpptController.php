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
    public function ownerSearch($nop)
    {
        try {
            $land = Land::where('nop', $nop)->first();
            $owner = Owner::where('id', $land->owner_id)->first();
            $owners = Owner::with('land')->where('family_id', $owner->family_id)->get();

            return ResponseFormatter::success(
                OwnerSearchResource::collection($owners),
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

    public function showSppt($nop)
    {
        try {
            $sppt = Land::where('nop', $nop)->first();

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

    public function createSppt(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nop' => 'required|unique:lands,nop',
                'taxpayer_name' => 'required',
                'taxpayer_rt'  => 'required',
                'taxpayer_rw' => 'required',
                'taxpayer_village' => 'required',
                'taxpayer_road' => 'required',
                'guardian_id' => 'required',
                'tax_object_rt' => 'required',
                'tax_object_rw' => 'required',
                'tax_object_village' => 'required',
                'tax_object_road' => 'required',
                'land_area' => 'required',
                'land_area_unit' => 'required',
                'building_area' => 'required',
                'building_area_unit' => 'required',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }

            // $land = Land::where('nop', $request->nop)->first();

            // if ($land) {
            //     return ResponseFormatter::error(
            //         null,
            //         'NOP already exist',
            //         409
            //     );
            // }

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
                'Data successfully updated'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Can\'t create new SPPT data',
                $e->getMessage(),
                400
            );
        }
    }

    public function spptUpdate(Request $request, $nop)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nop' => 'required',
                'guardian_id' => 'required',
                'tax_object_rt' => 'required',
                'tax_object_rw' => 'required',
                'tax_object_village' => 'required',
                'tax_object_road' => 'required',
                'land_area' => 'required',
                'land_area_unit' => 'required',
                'building_area' => 'required',
                'building_area_unit' => 'required',
                'taxpayer_name' => 'required',
                'taxpayer_rt' => 'required',
                'taxpayer_rw' => 'required',
                'taxpayer_village' => 'required',
                'taxpayer_road' => 'required',
                'family_id' => 'required',
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

            Land::where('nop', $nop)->update([
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
                'Data successfully updated'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Can\'t update SPPT data',
                $e->getMessage(),
                400
            );
        }
    }

    public function mutation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nop' => 'required',
                'nop_target' => 'required',
                'taxpayer_name' => 'required',
                'taxpayer_rt'  => 'required',
                'taxpayer_rw' => 'required',
                'taxpayer_village' => 'required',
                'taxpayer_road' => 'required',
                'guardian_id' => 'required',
                'tax_object_rt' => 'required',
                'tax_object_rw' => 'required',
                'tax_object_village' => 'required',
                'tax_object_road' => 'required',
                'land_area' => 'required',
                'land_area_unit' => 'required',
                'building_area' => 'required',
                'building_area_unit' => 'required',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }
    
            $land = Land::where('nop', $request->nop_target)->first();
            
            $remainingArea = $land->land_area - $request->land_area;
            $remainingBuildingArea = $land->building_area - $request->building_area;
    
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

            DB::beginTransaction();
            $owner = Owner::create([
                'family_id' => $request->family_id,
                'name' => $request->taxpayer_name,
                'rt' => $request->taxpayer_rt,
                'rw' => $request->taxpayer_rw,
                'village' => $request->taxpayer_village,
                'road' => $request->taxpayer_road,
            ]);
                
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
    
            $originLand = Land::where('nop', $request->nop_target)->first();
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
    
            $newOwner = Owner::where('id', $owner->id)->first();
            $originOwner = Owner::where('id', $originLand->owner->id)->first();
            $newLand = Land::where('nop', $request->nop)->first();

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
                'Data successfully updated'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Can\'t create mutation SPPT data',
                $e->getMessage()
            );
        }   
    }

    public function showSpptByGuardian($guardian_id)
    {
        try {
            $sppt = Land::where('guardian_id', $guardian_id)->get();

            return ResponseFormatter::success(
                SpptResource::collection($sppt),
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

    public function updateSpptGuardianId(Request $request)
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
                $landData = Land::where('nop', $value['nop'])->first();

                $land = Land::where('nop', $landData->nop)->update([
                    'nop' => $landData->nop,
                    'owner_id' => $landData->owner_id,
                    'guardian_id' => $value['guardian_id'],
                    'rt' => $landData->rt,
                    'rw' => $landData->rw,
                    'village' => $landData->village,
                    'road' => $landData->road,
                    'determination' => $landData->determination,
                    'sppt_persil_number' => $landData->sppt_persil_number,
                    'block_number' => $landData->block_number,
                    'land_area' => $landData->land_area,
                    'land_area_unit' => $landData->land_area_unit,
                    'building_area' => $landData->building_area,
                    'building_area_unit' => $landData->building_area_unit,
                ]);
            }

            return ResponseFormatter::success(
                'Data successfully updated',
                'Data successfully updated'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Can\'t update SPPT data',
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
                'Successfully delete data'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Something wrong',
                $e->getMessage()
            );
        }
    }
}