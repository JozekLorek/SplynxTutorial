<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [Controller::class, 'serviceCount'])->name('test');
Route::get('/search', [Controller::class, 'searchSplynx'])->name('search');