<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/',function (){
//     return Inertia::render('Home');
// });

 //Route::inertia('/','Home')->name('home');

Route::get('/',function(){return Inertia::render('Home');})->name('dashboard');
Route::get('/sli/user',[\App\Http\Controllers\UserController::class,'index'])->name('sli/user');

// Authentication Routes
Route::get('/login', [\App\Http\Controllers\UserController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
Route::get('/register', [\App\Http\Controllers\UserController::class, 'showRegister'])->name('register');
Route::post('/register', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/logout', [\App\Http\Controllers\UserController::class, 'logout'])->name('logout');

// Fleet Management Routes
Route::resource('vehicles', \App\Http\Controllers\VehicleController::class);
Route::resource('drivers', \App\Http\Controllers\DriverController::class);

// Load Management Routes
Route::resource('loads', \App\Http\Controllers\LoadController::class);

// Legacy routes for backward compatibility
Route::get('/fleet/vehicles', [\App\Http\Controllers\VehicleController::class, 'index'])->name('fleet.vehicles');
Route::get('/fleet/drivers', [\App\Http\Controllers\DriverController::class, 'index'])->name('fleet.drivers');
Route::get('/loads/planning', [\App\Http\Controllers\LoadController::class, 'index'])->name('loads.planning');

