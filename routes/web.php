<?php

use App\Http\Controllers\HomeownerController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', HomeownerController::class);