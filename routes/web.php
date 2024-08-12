<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\KursController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudiengangController;
use App\Http\Controllers\StundenController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DozentMiddleware;
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

    Route::get('/studiengänge/create', [StudiengangController::class, 'create'])
        ->name('stdgs.create');

    Route::post('/studiengänge/add', [StudiengangController::class, 'store'])
        ->name('stdgs.register');

    Route::get('/kurse/create/{doz_id}', [KursController::class, 'create'])
        ->name('kurse.create');

    Route::post('/kurse/add/{doz_id}', [KursController::class, 'store'])
        ->name('kurse.register');

    /**
     * Edit users & dozenten, kurse and studiengänge
     */
    Route::get('/kurse/edit/{id}', [KursController::class, 'edit'])
        ->name('kurse.edit');

    Route::patch('/kurse/update/{id}', [KursController::class, 'update'])
        ->name('kurse.update');

    Route::get('/studiengänge/edit/{id}', [StudiengangController::class, 'edit'])
        ->name('stdgs.edit');

    Route::patch('/studiengänge/update/{id}', [StudiengangController::class, 'update'])
        ->name('stdgs.update');

    // user create route must take a mode parameter (1 for user, 2 for dozent, 0 for both)
    Route::get('/users/edit/{id}/{mode}', [UserDozController::class, 'edit'])
        ->name('users.edit');

    Route::patch('/users/update/{id}/{mode}', [UserDozController::class, 'update'])
        ->name('users.update');

    /**
     * List all users & dozenten, kurse and studiengänge
     */
    Route::get('/users', [UserDozController::class, 'index'])->name('users.index');
    Route::get('/kurse', [KursController::class, 'index'])->name('kurse.index');
    Route::get('/studiengänge', [StudiengangController::class, 'index'])->name('studiengänge.index');
});

Route::middleware(['auth', DozentMiddleware::class])->group(function () {
    /**
     * Show stundenplan of current dozent
     */
    Route::get('/stundenplan', [StundenController::class, 'index'])
        ->name('stundenplan.show');

    Route::post('/stundenplan-speichern', [StundenController::class, 'parseTimetableJson'])
        ->name('stundenplan.save');
});

require __DIR__.'/auth.php';
