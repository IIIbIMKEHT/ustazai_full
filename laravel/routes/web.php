<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\LoadPDFController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:web']], function () {
//    Route::get('/', function () {
//        return view('welcome');
//    })->name('chat');
    Route::get('load-pdf', [LoadPDFController::class, 'index'])->name('pdf-load');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/generate-material/{id}', [DashboardController::class, 'generate'])->name('generate-material');
    Route::get('/my-materials/{subject_id}', [DashboardController::class, 'myMaterials'])->name('my-materials');
    Route::get('/show-material/{material_id}', [DashboardController::class, 'showMaterial'])->name('show-material');
    Route::delete('/delete-material/{material_id}', [DashboardController::class, 'deleteMaterial'])->name('delete-material');

    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function(){
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin-dashboard');
    });

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::get('google', [AuthController::class, 'google'])->name('auth.google');
    Route::get('google-callback', [AuthController::class, 'googleCallback'])->name('auth.google-callback');
    Route::post('simple-auth', [AuthController::class, 'simpleAuth'])->name('auth.simple');
});
