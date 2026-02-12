<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MyClientController;
use App\Http\Controllers\GroupUserController;
use App\Http\Controllers\ClientUserController;

Route::post('/login', [AuthController::class, 'login']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {

    // Clients
    Route::get('/me/clients', [MyClientController::class, 'index']);
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/users/{id}/clients', [ClientController::class, 'byUserId']);

    // Client User Account
    Route::post('/clientUser/add', [ClientUserController::class, 'addUserClient']);
    Route::post('/clientUser/edit/{id}', [ClientUserController::class, 'editUserClient']);
    Route::delete('/clientUser/delete/{id}', [ClientUserController::class, 'deleteUserClient']);


    // Group User Account
    Route::get('/groups', [GroupController::class, 'index']);
    Route::post('/groupUser/add', [GroupUserController::class, 'store']);
    Route::delete('/groupUser/delete/{id}', [GroupUserController::class, 'destroy']);
    Route::put('/groupUser/edit/{id}', [GroupUserController::class, 'update']);


    // Client List
    Route::get('/list/client', [ClientController::class, 'index']);
    Route::get('/list/group', [GroupController::class, 'index']);
});
