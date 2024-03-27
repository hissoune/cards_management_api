<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\BusinessCardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register',[UserAuthController::class,'register']);
Route::post('login',[UserAuthController::class,'login']);
  

  // routes/api.php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/business_cards', [BusinessCardController::class,'createBusinessCard'])->name('business_cards');
    Route::post('/update_business_cards/{id}', [BusinessCardController::class,'updateBusinessCard'])->name('update_business_cards');
    Route::post('/deletcard/{id}',[BusinessCardController::class ,'deleteBusinessCard']);
    Route::get('getCards',[BusinessCardController::class ,'getAllBusinessCards']);
    
    
    Route::post('logout',[UserAuthController::class,'logout']);
});