<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingsController;
use App\Http\Controllers\AuditSystemsController;
use App\Http\Controllers\CarouselImageController;
use App\Http\Controllers\ItemManagementController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\RaffleEntryController;

// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ListingsController::class, 'index'])->name('dashboard');
    Route::resource('searches', SearchController::class);

    Route::get('/listings', [ListingsController::class, 'userIndex'])->name('listings.index');
    Route::get('/listings/create', [ListingsController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingsController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing}', [ListingsController::class, 'show'])->name('listings.show');
    Route::get('/listings/{listing}/edit', [ListingsController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [ListingsController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}', [ListingsController::class, 'destroy'])->name('listings.destroy');

    // Standard User Routes
    Route::middleware(['role:Standard User|Administrator|Developer-Master'])->group(function () {
        Route::post('/listings/{listing}/raffle-entry', [RaffleEntryController::class, 'storeRaffleEntry'])->name('listings.raffle-entry.store');
        Route::get('/raffle-entries', [RaffleEntryController::class, 'getRaffleEntries'])->name('raffle-entries.index');
        Route::get('/raffle-entries/show', [RaffleEntryController::class, 'showRaffleEntries'])->name('raffle-entries.show');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update-picture');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::post('/listings/{listing}/buy-out', [ListingsController::class, 'buyOut'])->name('listings.buy-out');

        // Cart Routes
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/', [CartController::class, 'store'])->name('store');
            Route::delete('/{cartItem}', [CartController::class, 'destroy'])->name('destroy');
            Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
            Route::get('/potential-tickets/{listing}', [CartController::class, 'potentialTickets'])->name('potentialTickets');
            Route::patch('/{cartItem}', [CartController::class, 'update'])->name('update');
        });
    });

    // Administrator and Developer-Master Routes
    Route::middleware(['role:Administrator|Developer-Master'])->group(function () {
        Route::get('/audit-systems', [AuditSystemsController::class, 'index'])->name('audit-systems.index');
        Route::resource('role-management', RoleManagementController::class);
        Route::get('/item-management', [ItemManagementController::class, 'index'])->name('item-management.index');
        Route::prefix('carousel-images')->name('carousel-images.')->group(function () {
            Route::post('/', [CarouselImageController::class, 'store'])->name('store');
            Route::delete('/{carouselImage}', [CarouselImageController::class, 'destroy'])->name('destroy');
            Route::post('/{carouselImage}/replace', [CarouselImageController::class, 'replace'])->name('replace');
        });
        Route::resource('categories', CategoryController::class)->only(['index', 'store']);
    });

    // Developer-Master Routes
    Route::middleware(['role:Developer-Master'])->group(function () {
        Route::resource('user-management', UserManagementController::class)->except(['create', 'store', 'show']);
        Route::put('/user-management/{user}/updateRole', [UserManagementController::class, 'updateRole'])->name('user-management.updateRole');
        Route::delete('/user-management/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
    });
});

require __DIR__.'/auth.php';
