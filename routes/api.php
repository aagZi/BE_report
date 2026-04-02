<?php

/**
 * Semua route di file ini otomatis berprefix: /api
 * Contoh URL lengkap: GET /api/list/client , GET /api/list/group
 * (Base URL tergantung server, misal: http://localhost/atrindon_report/public atau http://atrindon_report.test)
 */

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ApiDocController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientUserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupUserController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MyClientController;
use App\Http\Controllers\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

// Dokumentasi API untuk frontend (tanpa auth agar bisa dilihat daftar API & params)
Route::get('/docs', [ApiDocController::class, 'index']);

// Settings — public (mobile app)
Route::get('/settings', [SettingController::class, 'index']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {

    // History (task status=1, tugas=4)
    Route::get('/history', [HistoryController::class, 'index']);

    // Clients
    Route::get('/me/clients', [MyClientController::class, 'index']);
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/users/{id}/clients', [ClientController::class, 'byUserId']);

    // Client User Account
    Route::get('/clientUser/list', [ClientUserController::class, 'index']);
    Route::post('/clientUser/add', [ClientUserController::class, 'addUserClient']);
    Route::post('/clientUser/edit/{id}', [ClientUserController::class, 'editUserClient']);
    Route::delete('/clientUser/delete/{id}', [ClientUserController::class, 'deleteUserClient']);


    // Group User Account
    Route::get('/groups', [GroupController::class, 'index']);
    Route::post('/groupUser/add', [GroupUserController::class, 'store']);
    Route::delete('/groupUser/delete/{id}', [GroupUserController::class, 'destroy']);
    Route::put('/groupUser/edit/{id}', [GroupUserController::class, 'update']);


    // Client List - CRUD
    Route::get('/list/client', [ClientController::class, 'listClientSummary']);
    Route::get('/list/client/{id}', [ClientController::class, 'show']);
    Route::post('/list/client', [ClientController::class, 'store']);
    Route::put('/list/client/{id}', [ClientController::class, 'update']);
    Route::delete('/list/client/{id}', [ClientController::class, 'destroy']);

    // Group List - CRUD
    Route::get('/list/group', [GroupController::class, 'index']);
    Route::get('/list/group/{id}', [GroupController::class, 'show']);
    Route::post('/list/group', [GroupController::class, 'store']);
    Route::put('/list/group/{id}', [GroupController::class, 'update']);
    Route::delete('/list/group/{id}', [GroupController::class, 'destroy']);

    // Admin Settings
    Route::get('/admin/settings', [SettingController::class, 'index']);
    Route::post('/admin/settings', [SettingController::class, 'storeOrUpdate']);
    Route::post('/admin/settings/upload', [SettingController::class, 'upload']);

    // Absensi
    Route::post('/absen/store', [AbsensiController::class, 'store']);

    // Jadwal
    Route::get('/jadwal', [JadwalController::class, 'index']);
});
