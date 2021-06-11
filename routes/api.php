<?php

use App\Http\Controllers\Api\AdministratorController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SpptController;
use Illuminate\Http\Request;
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

    Route::get('/administrators', [AdministratorController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'showProfile']);
    Route::patch('/profile/update', [ProfileController::class, 'update']);

    Route::get('/sppt/families', [SpptController::class, 'familyIndex']);
    Route::get('/sppt/search/{nop}', [SpptController::class, 'ownerSearch']);
    Route::get('/sppt/{nop}', [SpptController::class, 'showSppt']);
});

Route::group(['middleware' => ['auth:sanctum', 'role:Super Admin'], 'prefix' => 'v1/administrator'], function(){
    Route::post('/', [AdministratorController::class, 'store']);
    Route::delete('/{id}', [AdministratorController::class, 'destroy']);
});
