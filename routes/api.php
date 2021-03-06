<?php

use App\Http\Controllers\Api\AdministratorController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\FamilyController;
use App\Http\Controllers\Api\GuardianController;
use App\Http\Controllers\Api\MutationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SpptController;
use App\Http\Controllers\Api\TaxHistoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', [AuthenticationController::class, 'login']);
Route::get('/v1/families', [FamilyController::class, 'index']);
Route::get('/v1/guardians', [GuardianController::class, 'index']);

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'family'], function () {
        Route::get('/{family}', [FamilyController::class, 'show']);
        Route::get('/name/{name}', [FamilyController::class, 'showByName']);
    });
    
    Route::get('sppts/', [SpptController::class, 'index']);
    Route::group(['prefix' => 'sppt'], function () {
        Route::get('/search/{nop}', [SpptController::class, 'showByFamily']);
        // Route::get('/family/{id}', [SpptController::class, 'showByFamilyId']);
        Route::get('/family/{byFamily}', [SpptController::class, 'showByFamilyId']);
        Route::get('/{land}', [SpptController::class, 'show']);
        Route::get('/guardian/{guardian_id}', [SpptController::class, 'showByGuardian']);
        Route::get('/tax-histories/{sppt_id}', [TaxHistoryController::class, 'showTaxHistory']);
        Route::get('/tax-history/{taxHistory}', [TaxHistoryController::class, 'show']);
    });
});

Route::group(['middleware' => ['auth:sanctum', 'role:super admin|admin']], function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);

    Route::group(['prefix' => 'v1'], function () {
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::patch('/profile/update', [ProfileController::class, 'update']);
    
        Route::group(['prefix' => 'family'], function () {
            Route::post('/', [FamilyController::class, 'store']);
            Route::patch('/{family}', [FamilyController::class, 'update']);
        });
    
        Route::group(['prefix' => 'sppt'], function () {
            Route::post('/', [SpptController::class, 'store']);
            Route::patch('/update/{land:nop}', [SpptController::class, 'update']);
            Route::post('/mutation', [MutationController::class, 'mutation']);
            Route::delete('/delete/{id}', [SpptController::class, 'destroy']);
            Route::post('/tax-history', [TaxHistoryController::class, 'store']);
            Route::patch('/tax-history/{taxHistory}', [TaxHistoryController::class, 'update']);
            Route::delete('/tax-history/{taxHistory}', [TaxHistoryController::class, 'destroy']);
        });
    });
});

Route::group(['middleware' => ['auth:sanctum', 'role:super admin'], 'prefix' => 'v1'], function () {
    Route::get('/administrators', [AdministratorController::class, 'index']);
    Route::group(['prefix' => 'administrator'], function () {
        Route::post('/', [AdministratorController::class, 'store']);
        Route::delete('/{user}', [AdministratorController::class, 'destroy']);
    });

    Route::group(['prefix' => 'guardian'], function () {
        Route::post('/', [GuardianController::class, 'store']);
        Route::put('/{guardian}', [GuardianController::class, 'update']);
        Route::get('/{guardian}', [GuardianController::class, 'show']);
        Route::delete('/{guardian}', [GuardianController::class, 'destroy']);
        Route::patch('/id/update', [GuardianController::class, 'updateGuardianId']);
    });

    Route::patch('/family/id/update', [FamilyController::class, 'updateFamilyId']);
    Route::delete('family/{family}', [FamilyController::class, 'destroy']);
});