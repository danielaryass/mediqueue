<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\PermissionsController;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\AppointmentController;

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
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('/unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');
Route::get('/doctors', [DoctorController::class, 'index']);
Route::get('/doctors/{id}', [DoctorController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/store', [UserController::class, 'store']);
    Route::get('/user/{user}/edit', [UserController::class, 'edit']);
    Route::put('/user/{user}/update', [UserController::class, 'update']);
    Route::post('/user/{user}/delete', [UserController::class, 'destroy']);
    // roles 
    Route::get('/roles', [RolesController::class, 'index']);
    Route::get('/roles/{role}/edit', [RolesController::class, 'edit']);
    Route::post('/roles/{role}/update', [RolesController::class, 'update']);
    Route::post('/roles/store', [RolesController::class, 'store']);
    Route::post('/roles/{role}/delete', [RolesController::class, 'destroy']);
    // Permissions
    Route::get('/permissions', [PermissionsController::class, 'index']);
    Route::get('/permissions/{permission}/edit', [PermissionsController::class, 'edit']);
    Route::put('/permissions/{permission}/update', [PermissionsController::class, 'update']);
    Route::post('/permissions/store', [PermissionsController::class, 'store']);
    Route::post('/permissions/{permission}/delete', [PermissionsController::class, 'destroy']);
    Route::resource('doctor', DoctorController::class);

    Route::get('/appointment', [AppointmentController::class, 'dataUser']);
    Route::post('/appointment/store', [AppointmentController::class, 'store']);
    
    
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
