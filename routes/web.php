<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\ProfileController;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/auth/{providers}/redirect',[GithubController::class,'redirect'] )->name('provider.login');
Route::get('/auth/{providers}/callback', [GithubController::class,'callBack']);
Route::post('/file/upload', [FileController::class, 'upload'])->name('file.upload');
require __DIR__.'/auth.php';
