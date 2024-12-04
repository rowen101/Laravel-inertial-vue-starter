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

Route::get('/dashboard',function(){return Inertia::render('Home');})->name('dashboard');
Route::get('/sli/user',[\App\Http\Controllers\UserController::class,'index'])->name('sli/user');

