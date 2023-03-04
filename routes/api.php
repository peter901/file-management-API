<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileManagementController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('create',[FileManagementController::class,'create']);
Route::get("read/{id}",[FileManagementController::class,'read']);
Route::post('update/{id}',[FileManagementController::class,'update']); // 'put' not supported for file uploads. using 'post' instead
Route::delete('delete/{id}',[FileManagementController::class,'delete']);