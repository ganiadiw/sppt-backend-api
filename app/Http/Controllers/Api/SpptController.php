<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewMutationResource;
use App\Http\Resources\OriginMutationResource;
use App\Http\Resources\OwnerSearchResource;
use App\Http\Resources\SpptResource;
use App\Models\Family;
use App\Models\Guardian;
use App\Models\Land;
use App\Models\MutationHistory;
use App\Models\Owner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

//  Controller guide
//  This controller controls about sppt data
class SpptController extends Controller
{
    // to get 10 data from database
    public function index()
    {
        $sppts = Land::orderBy('guardian_id')->take(10)->get();

        return response()->json([
            'message' => 'SPPT data successfully loaded',
            'data' => [
                'total_sppt' => Land::count(),
                'total_family_group' => Family::count(),
                'total_guardians' => Guardian::count(),
                'description' => 'Hanya ditampilkan ' . $sppts->count() . ' data teratas. Gunakan pencarian untuk mencari data yang diinginkan',
                'data' => SpptResource::collection($sppts)
            ]
        ]);
    }

    // to show sppt data group by nop
    public function showByFamily($nop)
    {
        $land = Land::with('owner')->where('nop', $nop)->first();
        $owners = Owner::with('land')->where('family_id', $land->owner->family_id)
                    ->get()->except($land->id);
        $response = OwnerSearchResource::collection($owners);

        return response()->json([
            'message' => 'SPPT data successfully loaded',
            'data' => $response->prepend(new SpptResource($land))
        ]);
    }

    // to show sppt data group family id
    public function showByFamilyId($id)
    {
        $owners = Owner::with('land')->where('family_id', $id)->get();
        
        return response()->json([
            'message' => 'SPPT data successfully loaded',
            'data' => OwnerSearchResource::collection($owners)
        ]);
    }

    // to show specific sppt data by nop number
    public function showByNop(Land $land)
    {
        return response()->json([
            'message' => 'SPPT data successfully loaded',
            'data' => new SpptResource($land)
        ]);
    }
    
    // to show specific sppt data by id
    public function show(Land $land)
    {
        return response()->json([
            'message' => 'SPPT data successfully loaded',
            'data' => new SpptResource($land)
        ]);
    }

    // to show sppt data by guardian
    public function showByGuardian($guardian_id)
    {
        $sppt = Land::where('guardian_id', $guardian_id)->get();

        return response()->json([
            'message' => 'SPPT data successfully loaded',
            'data' => SpptResource::collection($sppt)
        ]);
    }

    // to store data to database
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
                return response()->json([
                    'message' => 'The given data was invalid',
                    'errors' => $validator->errors(),
                ], 422);
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
                
            Land::create([
                'nop' => $request->nop,
                'owner_id' => $owner->id,
                'guardian_id' => $request->guardian_id,
                'rt' => $request->tax_object_rt,
                'rw' => $request->tax_object_rw,
                'village' => $request->tax_object_village,
                'road' => $request->tax_object_road,
                'sppt_persil_number' => $request->sppt_persil_number,
                'block_number' => $request->block_number,
                'land_area' => $request->land_area,
                'land_area_unit' => $request->land_area_unit,
                'building_area' => $request->building_area,
                'building_area_unit' => $request->building_area_unit,
            ]);
            
            DB::commit();

            $sppt = Land::where('nop', $request->nop)->first();

            return response()->json([
                'message' => 'Successfully created SPPT data',
                'data' => new SpptResource($sppt)
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // to update data in database
    public function update(Request $request, Land $land)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nop' => ['required', Rule::unique('lands')->ignore($land->id)],
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
                return response()->json([
                    'message' => 'The given data was invalid',
                    'errors' => $validator->errors(),
                ], 422);
            }
            
            DB::beginTransaction();

            $land->update([
                'nop' => $request->nop,
                'owner_id' => $land->owner_id,
                'guardian_id' => $request->guardian_id,
                'rt' => $request->tax_object_rt,
                'rw' => $request->tax_object_rw,
                'village' => $request->tax_object_village,
                'road' => $request->tax_object_road,
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

            return response()->json([
                'message' => 'SPPT data successfully loaded',
                'data' => new SpptResource($land)
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // to control mutation data
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
                return response()->json([
                    'message' => 'The given data was invalid',
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            $landTarget = Land::where('nop', $request->nop_target)->first();
            if (!$landTarget) {

                return response()->json([
                    'message' => 'SPPT target data not found',
                ], 404);
            }
            
            $remainingArea = $landTarget->land_area - $request->land_area;
            $remainingBuildingArea = $landTarget->building_area - $request->building_area;
            $originLand = Land::where('nop', $request->nop_target)->first();
            $originOwner = Owner::find($originLand->owner_id);

            if ($remainingArea < 0) {
                return response()->json([
                    'The area of ​​the target land is zero, so it cannot reduce the area of ​​the land, make sure the mutation value does not exceed the value of the target land area',
                ], 400);
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

                    return response()->json([
                        'message' => 'The area of ​​the target building is zero, so it cannot reduce the area of ​​the building, make sure the mutation value does not exceed the value of the target building area',
                    ], 400);
                }

                Land::where('nop', $request->nop_target)->update([
                    'nop' => $originLand->nop,
                    'owner_id' => $originLand->owner_id,
                    'guardian_id' => $originLand->guardian_id,
                    'rt' => $originLand->rt,
                    'rw' => $originLand->rw,
                    'village' => $originLand->village,
                    'road' => $originLand->road,
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

            return response()->json([
                'message' => 'Mutation data was successfully created',
                'data' => [
                    new OriginMutationResource($originLand),
                    new NewMutationResource($newLand),
                ]
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }   
    }

    //to update guardian id in array
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

    // to update family id in array
    public function updateFamilyId(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sppt_objects' => 'required',
                'sppt_objects.*.id' => 'required',
                'sppt_objects.*.family_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'The given data was invalid',
                    'errors' => $validator->errors(),
                ], 422);
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
    public function destroy ($id)
    {
        Owner::findOrFail($id)->delete();

        return response()->json([
            'message' => 'The data has been deleted'
        ]);
    }
}