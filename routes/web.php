<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HearingController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('cases', CaseController::class);
    Route::post('/cases/{case}/documents', [DocumentController::class, 'store'])->name('cases.documents.store');
    Route::resource('hearings', HearingController::class)->only(['index', 'store', 'update']);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('role:super-admin,court-admin,judge');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export')->middleware('role:super-admin,court-admin,judge');
});
