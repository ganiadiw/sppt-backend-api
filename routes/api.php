<?php

use App\Http\Controllers\Api\AdministratorController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ProfileController;
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

    Route::group(['role:super-admin', 'prefix' => 'administrator'], function () {
        Route::post('/', [AdministratorController::class, 'store']);
        Route::delete('/{id}', [AdministratorController::class, 'destroy']);
    }); 
});
