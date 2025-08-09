<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::resource('jobs', JobController::class);


