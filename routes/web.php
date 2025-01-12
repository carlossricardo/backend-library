<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\BookCliController;
use App\Http\Controllers\Admin\BookController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', [BookCliController::class, 'saludar']);
Route::post('/create', [BookController::class, 'create']);
// Route::post('/create', [BookController::class, 'create']);


// Route::post('save', [PaisController::class, 'saveCountry']); //