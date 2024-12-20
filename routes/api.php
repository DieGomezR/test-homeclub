<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\IncidenceController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

Route::resource('property', PropertyController::class);
Route::resource('booking', BookingController::class);
Route::resource('incidence', IncidenceController::class);
Route::resource('task', TaskController::class);

Route::patch('task/{id}/finish', [TaskController::class, 'updateFinish'])->name('task.updateFinish');
