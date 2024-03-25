<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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

    Route::resource('listings' , ListingsController::class);
    Route::post('/listings/{listing}/raffle-entry', [ListingsController::class, 'storeRaffleEntry'])->name('listings.raffle-entry.store');
    Route::get('/raffle-entries', [ListingsController::class, 'getRaffleEntries'])->name('raffle-entries.index');
    Route::get('/dashboard', [ListingsController::class, 'getRaffleEntries'])->name('dashboard');
    Route::get('/raffle-entries', [ListingsController::class, 'showRaffleEntries'])->name('raffle-entries.index');

    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::post('/wallet/update', [WalletController::class, 'update'])->name('wallet.update');


});

require __DIR__.'/auth.php';
