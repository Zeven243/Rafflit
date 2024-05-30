<?php

// routes/web.php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingsController;
use App\Http\Controllers\UserLogsController;
use App\Http\Controllers\AuditSystemsController;
use App\Http\Controllers\CarouselImageController;
use App\Http\Controllers\ItemManagementController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CartController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ListingsController::class, 'index'])->name('dashboard');
    Route::resource('searches', SearchController::class);

    // Routes accessible by Standard users
    Route::middleware(['checkRole:Standard User,Administrator,Developer-Master'])->group(function () {
        Route::get('/listings', [ListingsController::class, 'userIndex'])->name('listings.index');
        Route::resource('listings', ListingsController::class);
        Route::post('/listings/{listing}/raffle-entry', [ListingsController::class, 'storeRaffleEntry'])->name('listings.raffle-entry.store');
        Route::get('/raffle-entries', [ListingsController::class, 'showRaffleEntries'])->name('raffle-entries.index');
        Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
        Route::post('/wallet/update', [WalletController::class, 'update'])->name('wallet.update');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update-picture');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::post('/listings/{listing}/buy-out', [ListingsController::class, 'buyOut'])->name('listings.buy-out');

        // Cart routes
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
        Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    });

    // Routes accessible by Administrator and Developer-Master
    Route::middleware(['checkRole:Administrator,Developer-Master'])->group(function () {
        Route::get('/listings/create', [ListingsController::class, 'create'])->name('listings.create');
        Route::post('/listings', [ListingsController::class, 'store'])->name('listings.store');
        Route::get('/audit-systems', [AuditSystemsController::class, 'index'])->name('audit-systems.index');
        Route::resource('role-management', RoleManagementController::class);
        Route::get('/item-management', [ItemManagementController::class, 'index'])->name('item-management.index');
        Route::post('/carousel-images', [CarouselImageController::class, 'store'])->name('carousel-images.store');
        Route::delete('/carousel-images/{carouselImage}', [CarouselImageController::class, 'destroy'])->name('carousel-images.destroy');
        Route::post('/carousel-images/{carouselImage}/replace', [CarouselImageController::class, 'replace'])->name('carousel-images.replace');
        Route::resource('categories', CategoryController::class)->only(['index', 'store']);
    });

    // Routes exclusively for Developer-Master
    Route::middleware(['checkRole:Developer-Master'])->group(function () {
        Route::resource('user-management', UserManagementController::class)->except(['create', 'store', 'show']);
        Route::put('/user-management/{user}/updateRole', [UserManagementController::class, 'updateRole'])->name('user-management.updateRole');
        Route::delete('/user-management/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
    });
});

require __DIR__.'/auth.php';
