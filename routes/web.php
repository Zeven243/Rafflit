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
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\PrivacyController;

// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/terms', [TermsController::class, 'index'])->name('terms');
Route::get('/privacy', [PrivacyController::class, 'index'])->name('privacy');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ListingsController::class, 'index'])->name('dashboard');
    Route::resource('searches', SearchController::class);

    Route::get('/listings', [ListingsController::class, 'userIndex'])->name('listings.index');
    Route::get('/listings/create', [ListingsController::class, 'create'])->name('listings.create')->middleware('role:Administrator|Developer-Master');
    Route::post('/listings', [ListingsController::class, 'store'])->name('listings.store')->middleware('role:Administrator|Developer-Master');
    Route::get('/listings/{listing}', [ListingsController::class, 'show'])->name('listings.show');
    Route::get('/listings/{listing}/edit', [ListingsController::class, 'edit'])->name('listings.edit')->middleware('role:Administrator|Developer-Master');
    Route::put('/listings/{listing}', [ListingsController::class, 'update'])->name('listings.update')->middleware('role:Administrator|Developer-Master');
    Route::delete('/listings/{listing}', [ListingsController::class, 'destroy'])->name('listings.destroy')->middleware('role:Administrator|Developer-Master');

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
        Route::get('/item-management/search', [ItemManagementController::class, 'search'])->name('item-management.search');
        Route::put('/item-management/{listing}/update-status', [ItemManagementController::class, 'updateStatus'])->name('item-management.updateStatus');
        Route::put('/item-management/{listing}/update-shipping-status', [ItemManagementController::class, 'updateShippingStatus'])->name('item-management.updateShippingStatus');
        Route::prefix('carousel-images')->name('carousel-images.')->group(function () {
            Route::get('/', [CarouselImageController::class, 'index'])->name('index');
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
