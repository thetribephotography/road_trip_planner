<?php

use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\DestinationMapController;

// Guest Route
Route::get('/', function () { return view('guest');})->middleware('guest');

// Authenticated User Routes
Route::middleware('auth')->group(function () {

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Destination Routes
    Route::get('/destinations/create/{trip_id}', [DestinationController::class, 'create'])->name('destinations.create');
    Route::resource('destinations', DestinationController::class);

    Route::post('/destinations/order', [DestinationController::class, 'updateOrder'])->name('destinations.updateOrder');
    Route::get('/destination-map', [DestinationMapController::class, 'index'])->name('destination_map.index');
    
    // Route::post('/destinations/store', [DestinationController::class, 'store'])->name('destinations.store');


    //TRIPS
    Route::resource('trips', TripController::class);
    Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
    // Route::get('/trips/{id}', [TripController::class, 'show'])->name('trips.show');
    // Route::post('/trips/store', [TripController::class, 'storeTrip'])->name('trips.store');
    // Route::get('/trips/create', [TripController::class, 'create'])->name('trips.create');

});

// User Dashboard Route
Route::get('/dashboard', [UserDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Authentication Routes
require __DIR__.'/auth.php';
