<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CaseApiController;
use App\Http\Controllers\Api\HearingApiController;
use App\Http\Controllers\Api\NotificationApiController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthApiController::class, 'login']);
Route::get('/cases', [CaseApiController::class, 'index']);
Route::get('/cases/{case}', [CaseApiController::class, 'show']);
Route::get('/hearings', [HearingApiController::class, 'index']);
Route::middleware('auth')->get('/notifications', [NotificationApiController::class, 'index']);
