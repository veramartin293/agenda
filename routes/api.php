<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

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

// Public routes

// Auth routes
Route::post('/register', [AuthController::class, 'register']);  
Route::post('/login', [AuthController::class, 'login']);




// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Contact routes
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::get('/contacts/{id}', [ContactController::class, 'show']);
    Route::get('/contacts/search/{name}', [ContactController::class, 'search']);
    Route::post('/contacts', [ContactController::class, 'store']);  
    Route::put('/contacts/{id}', [ContactController::class, 'update']);  
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy']); 
    
    // Auth route (logout)
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/auth' , [AuthController::class, 'getAuthUser']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
