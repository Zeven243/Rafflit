<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarouselImageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/carousel-images', [CarouselImageController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/recent-searches', [SearchController::class, 'recentSearches']);
    Route::get('/trending-searches', [SearchController::class, 'trendingSearches']);
    Route::post('/save-search', [SearchController::class, 'saveSearch']);

    // Role management routes
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy']);

    // Permission management routes
    Route::get('/permissions', [PermissionController::class, 'index']);
});
