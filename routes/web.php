<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\ProfileController;
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

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');



/* The Above Dashboard code is commented because we dont need that and
i have provided my dashboard inside the middleware auth route so that it checks if the user is authenticated for
 both dashboard and file upload  */


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [FileController::class, 'index'])->name('dashboard');
    Route::post('/file/upload', [FileController::class, 'upload'])->name('file.upload');
});

Route::post('/github/credentials',[GithubController::class,'githubCredStore'])->name('github.cred');

/* This are the Url Needed for the github Oauth and I used Laravel socialite Package to create this controller */
Route::get('/auth/{providers}/redirect',[GithubController::class,'redirect'] )->name('provider.login');
Route::get('/auth/{providers}/callback', [GithubController::class,'callBack']);

require __DIR__.'/auth.php';
