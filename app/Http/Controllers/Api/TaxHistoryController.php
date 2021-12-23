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
    
            return ResponseFormatter::success(
                [
                    'sppt' => $response,
                    'tax_histories' => TaxHistoryResource::collection($taxHistories)
                ],
                'Tax histories successfully loaded'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Data not found',
                $e->getMessage(),
                404
            );
        }
    }

    public function show($id)
    {
        try {
            $taxHistory = TaxHistory::with('land')->where('id', $id)->first();

            $response = [
                'nop' => $taxHistory->land->nop,
                'taxpayer_name' => $taxHistory->land->owner->name,
            ];
    
            return ResponseFormatter::success(
                [
                    'sppt' => $response,
                    'tax_histories' => new TaxHistoryResource($taxHistory)
                ],
                'Tax histories successfully loaded'
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
    public function store(TaxHistoryRequest $request)
    {
        try {
            $taxHistory = TaxHistory::create($request->validated() + [
                'land_id' => $request->sppt_id
            ]);

            return ResponseFormatter::success(
                 new TaxHistoryResource($taxHistory),
                'Tax history was successfully created'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to create tax history data',
                $e->getMessage()
            );
        }
    }

    // to update data in database
    public function update(TaxHistoryRequest $request, $id)
    {
        try {
            TaxHistory::where('id', $id)->update($request->validated() + [
                'land_id' => $request->sppt_id
            ]);

            $taxHistory = TaxHistory::find($id);

            return ResponseFormatter::success(
                 new TaxHistoryResource($taxHistory),
                'Tax history was successfully updated'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to update tax history data',
                $e->getMessage()
            );
        }
    }

    public function destroy($id)
    {
        try {
            TaxHistory::findOrFail($id)->delete();
            return ResponseFormatter::success(
                'The data has been deleted',
                'Tax history data successfully deleted'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to delete tax history data',
                $e->getMessage(),
                404
            );
        }
    }
}
