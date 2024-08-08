<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\KursController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudiengangController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDozController;

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
    /**
     * Delete of users & dozenten, kurse and studiengänge
     */
    Route::delete('/users/{id}', [ProfileController::class, 'destroy'])
        ->name('users.destroy')
        ->defaults('isUser', true);
    Route::delete('/dozenten/{id}', [ProfileController::class, 'destroy'])
        ->name('dozenten.destroy')
        ->defaults('isUser', false);

    Route::delete('/kurse/{id}', [KursController::class, 'destroy'])
        ->name('kurse.destroy');

    Route::delete('/studiengänge/{id}', [StudiengangController::class, 'destroy'])
        ->name('studiengaenge.destroy');

    /**
     * Create new users & dozenten, kurse and studiengänge
     */
    // user create route must take a mode parameter (1 for user, 2 for dozent, 0 for both)
    Route::get('/users/create/{mode}/{id?}', [UserDozController::class, 'create'])
        ->name('users.create');

    Route::post('/users/add/{mode}/{id?}', [UserDozController::class, 'store'])
        ->name('users.register');

    /**
     * List all users & dozenten, kurse and studiengänge
     */
    Route::get('/users', [UserDozController::class, 'index'])->name('users.index');
    Route::get('/kurse', [KursController::class, 'index'])->name('kurse.index');
    Route::get('/studiengänge', [StudiengangController::class, 'index'])->name('studiengänge.index');
});

require __DIR__.'/auth.php';
