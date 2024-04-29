<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingsController;
use App\Http\Controllers\UserLogsController;
use App\Http\Controllers\AuditSystemsController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\UserManagementController;



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ListingsController::class, 'getRaffleEntries'])->name('dashboard');

    // Routes accessible by Standard users
    Route::middleware(['checkRole:Standard User,Administrator,Developer-Master'])->group(function () {
        Route::resource('listings', ListingsController::class)->only(['create', 'store', 'edit', 'update', 'destroy', 'index', 'show']);
        Route::post('/listings/{listing}/raffle-entry', [ListingsController::class, 'storeRaffleEntry'])->name('listings.raffle-entry.store');
        Route::get('/raffle-entries', [ListingsController::class, 'showRaffleEntries'])->name('raffle-entries.index');
        Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
        Route::post('/wallet/update', [WalletController::class, 'update'])->name('wallet.update');
        Route::post('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update-picture');

    });

    // Routes accessible by Administrator and Developer-Master
    Route::middleware(['checkRole:Administrator,Developer-Master'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        
        // Administrative routes
        Route::get('/audit-systems', [AuditSystemsController::class, 'index'])->name('audit-systems.index');
        Route::resource('role-management', RoleManagementController::class);
    });

    // Routes exclusively for Developer-Master
    Route::middleware(['checkRole:Developer-Master'])->group(function () {
        Route::resource('user-management', UserManagementController::class)->except(['create', 'store', 'show']);
        Route::put('/user-management/{user}/updateRole', [UserManagementController::class, 'updateRole'])->name('user-management.updateRole');
        Route::delete('/user-management/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
    });
});

require __DIR__.'/auth.php';
