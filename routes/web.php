<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Mail\JobApplied;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/jobs/search', [JobController::class, 'search'])->name('jobs.search');
Route::resource('jobs', JobController::class)->middleware('auth')->only(['create','edit','update', 'destroy']);
Route::resource('jobs', JobController::class)->except(['create','edit','update', 'destroy']);

Route::middleware('guest')->group(function(){
    Route::get('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/store', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::put('profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');

Route::middleware('auth')->group(function(){
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/{job}', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/bookmarks/{job}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
});

Route::post('/jobs/{job}/apply', [ApplicantController::class, 'store'])->name('application.store')->middleware('auth');
Route::delete('/applicants/{applicant}', [ApplicantController::class, 'destroy'])->name('applicant.destroy')->middleware('auth');

Route::get('/test/email', function (){

    $job = (object) [
    "id" => 4,
    "title" => "Data Analyst",
      ];
    $application = (object) [
        "full_name" => "Rafael Miller",
        "contact_phone" => "+1 (152) 412-3965",
        "contact_email" => "cobiraly@mailinator.com",
        "message" => "Repellendus Incidun",
        "location" => "Qui impedit et poss",
        "resume_path" => "resumes/yAPyDfuMPdT8fAXuBkFnDXUYoT8cdcUKFNqUe2eY.pdf",
        "job_id" => 6,
        "user_id" => 13,
        "updated_at" => "2025-09-27 07:43:23",
        "created_at" => "2025-09-27 07:43:23",
        "id" => 11,
    ];

    // send email of job application
    return new JobApplied($application, $job);
});
