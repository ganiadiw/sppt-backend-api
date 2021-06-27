<?php

use App\Http\Controllers\Api\AdministratorController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\FamilyController;
use App\Http\Controllers\Api\GuardianController;
use App\Http\Controllers\Api\OwnerController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SpptController;
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
Route::post('/v1/login', [AuthenticationController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'v1'], function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::patch('/profile/update', [ProfileController::class, 'update']);

    Route::get('/families', [FamilyController::class, 'index']);
    Route::group(['prefix' => 'family'], function () {
        Route::post('/', [FamilyController::class, 'store']);
        Route::patch('/{id}', [FamilyController::class, 'update']);
        Route::get('/{id}', [FamilyController::class, 'show']);
        Route::get('/name/{name}', [FamilyController::class, 'showByName']);
    });

    Route::group(['prefix' => 'sppt'], function () {
        Route::post('/', [SpptController::class, 'createSppt']);
        Route::get('/search/{nop}', [SpptController::class, 'show']);
        Route::get('/{nop}', [SpptController::class, 'showSppt']);
        Route::patch('/update/{nop}', [SpptController::class, 'spptUpdate']);
        Route::post('/mutation', [SpptController::class, 'mutation']);
        Route::delete('/delete/{id}', [SpptController::class, 'destroy']); // OK
        Route::get('guardian/{guardian_id}', [SpptController::class, 'showSpptByGuardian']); // OK
    });

    Route::get('/guardians', [GuardianController::class, 'index']); // OK
});

Route::group(['middleware' => ['auth:sanctum', 'role:Super Admin'], 'prefix' => 'v1'], function () {
    Route::get('/administrators', [AdministratorController::class, 'index']);
    Route::group(['prefix' => 'administrator'], function () {
        Route::post('/', [AdministratorController::class, 'store']);
        Route::delete('/{id}', [AdministratorController::class, 'destroy']);
    });

    Route::group(['prefix' => 'guardian'], function () {
        Route::post('/', [GuardianController::class, 'createGuardian']); // OK
        Route::put('/{id}', [GuardianController::class, 'updateGuardian']); // OK
        Route::get('/{id}', [GuardianController::class, 'show']); // OK
        Route::delete('/{id}', [GuardianController::class, 'destroy']); // OK
    });

    Route::patch('/sppt/guardian-update', [SpptController::class, 'updateSpptGuardianId']); // OK
});
