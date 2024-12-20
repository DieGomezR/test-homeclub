<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\IncidenceController;

Route::resource('properties', PropertyController::class);
Route::resource('bookings', BookingController::class);
Route::resource('incidences', IncidenceController::class);

// Redirigir raíz al índice de propiedades
Route::get('/', function () {
    return redirect()->route('properties.index');
});
