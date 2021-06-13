<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\HttpCodes;

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

Route::namespace('Api')->group(function() {
    Route::apiResource('employees', EmployeeController::class);
});

Route::fallback(function () {
    return response()->json([
        'error' => 'Resource not found'
    ], HttpCodes::HTTP_NOT_FOUND);
});
