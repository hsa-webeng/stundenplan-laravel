<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\KursController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudiengangController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// get the view with the HomeController class
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// image route
Route::get('/images/{imageName}', [ImageController::class, 'show'])->name('image.show');

/**
 * Admins only functions
 */
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    // only admins can delete users
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // show all users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/kurse', [KursController::class, 'index'])->name('kurse.index');
    Route::get('/studiengänge', [StudiengangController::class, 'index'])->name('studiengänge.index');
});

require __DIR__.'/auth.php';
