<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaxHistoryResource;
use App\Models\TaxHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sppt_id' => 'required',
                'year' => 'required',
                'amount' => 'required|numeric',
                'payment_status' => 'required'
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }

            $taxHistory = TaxHistory::create([
                'land_id' => $request->sppt_id,
                'year' => $request->year,
                'amount' => $request->amount,
                'payment_status' => $request->payment_status
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

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sppt_id' => 'required',
                'year' => 'required',
                'amount' => 'required|numeric',
                'payment_status' => 'required'
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error(
                    $validator->errors(),
                    'The given data was invalid'
                );
            }

            TaxHistory::where('id', $id)->update([
                'land_id' => $request->sppt_id,
                'year' => $request->year,
                'amount' => $request->amount,
                'payment_status' => $request->payment_status
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
