<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OwnerSearchResource;
use App\Http\Resources\SpptResource;
use App\Models\Family;
use App\Models\Guardian;
use App\Models\Land;
use App\Models\Owner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

//  Controller guide
//  This controller controls about sppt data
class SpptController extends Controller
{
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

    public function showByFamilyId($byFamily)
    {
        $land = Land::with('owner')->where('nop', $byFamily)->first();
        $owner = Owner::with('land')->where('family_id', $byFamily)->first();
        
        if ($land != null) {
            $owners = Owner::with('land')->where('family_id', $land->owner->family_id)
                    ->get()->except($land->id);
            $response = OwnerSearchResource::collection($owners);
            $response = $response->prepend(new SpptResource($land));
        } elseif ($owner != null) {
            $owners = Owner::with('land')->where('family_id', $byFamily)->get();
            $response = OwnerSearchResource::collection($owners);
        }

        return response()->json([
            'message' => 'SPPT data successfully loaded',
            'data' => $response
        ]);
    }
    
    public function show(Land $land)
    {
        return response()->json([
            'message' => 'SPPT data successfully loaded',
            'data' => new SpptResource($land)
        ]);
    }

    public function showByGuardian($guardian_id)
    {
        $sppt = Land::where('guardian_id', $guardian_id)->get();

        return response()->json([
            'message' => 'SPPT data successfully loaded',
            'data' => SpptResource::collection($sppt)
        ]);
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

    public function destroy ($id)
    {
        Owner::findOrFail($id)->delete();

        return response()->json([
            'message' => 'The data has been deleted'
        ]);
    }
}