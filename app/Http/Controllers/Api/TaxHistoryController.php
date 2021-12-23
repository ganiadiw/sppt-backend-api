<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaxHistoryRequest;
use App\Http\Resources\TaxHistoryResource;
use App\Models\TaxHistory;
use Exception;

class TaxHistoryController extends Controller
{
    public function showTaxHistory($sppt_id)
    {
        try {
            $taxHistories = TaxHistory::with('land')->orderBy('year', 'DESC')->where('land_id', $sppt_id)->get();
            $taxHistory = TaxHistory::with('land')->orderBy('year', 'DESC')->where('land_id', $sppt_id)->first();

            $response = [
                'nop' => (string)$taxHistory->land->nop,
                'taxpayer_name' => $taxHistory->land->owner->name,
            ];

            return response()->json([
                'message' => 'Tax histories successfully loaded',
                'data' => [
                    'sppt' => $response,
                    'tax_histories' => TaxHistoryResource::collection($taxHistories)
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function show(TaxHistory $taxHistory)
    {
        $response = [
            'nop' => $taxHistory->land->nop,
            'taxpayer_name' => $taxHistory->land->owner->name,
        ];

        return response()->json([
            'message' => 'Tax histories successfully loaded',
            'data' => [
                'sppt' => $response,
                'tax_histories' => new TaxHistoryResource($taxHistory)
            ]
        ]);
    }

    // to store data to database
    public function store(TaxHistoryRequest $request)
    {
        try {
            $taxHistory = TaxHistory::create($request->validated() + [
                'land_id' => $request->sppt_id
            ]);

            return response()->json([
                'message' => 'Tax history was successfully created',
                'data' => new TaxHistoryResource($taxHistory)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // to update data in database
    public function update(TaxHistoryRequest $request, TaxHistory $taxHistory)
    {
        try {
            $taxHistory->update($request->validated() + [
                'land_id' => $request->sppt_id
            ]);
    
            return response()->json([
                'message' => 'Tax history was successfully updated',
                'data' => new TaxHistoryResource($taxHistory)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something wrong happened',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(TaxHistory $taxHistory)
    {
        $taxHistory->delete();

        return response()->json([
            'message' => 'The data has been deleted'
        ]);
    }
}
