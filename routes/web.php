<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Semua route aplikasi web didefinisikan di sini
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');



/*
|--------------------------------------------------------------------------
| Dashboard (User & Admin - Harus Login)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('homepage');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes (Breeze Default)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Item (Barang Hilang & Ditemukan)
|--------------------------------------------------------------------------
| - User: input barang ditemukan
| - User: melihat daftar barang
| - Admin: approve barang
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Melihat semua barang (user & admin)
    Route::get('/items', [ItemController::class, 'index'])
        ->name('items.index');

    // User input barang ditemukan
    Route::post('/items', [ItemController::class, 'store'])
        ->name('items.store');

    // Admin approve barang
    Route::post('/items/{id}/approve', [ItemController::class, 'approve'])
        ->name('items.approve');
});

/*
|--------------------------------------------------------------------------
| Claim Barang (Admin)
|--------------------------------------------------------------------------
| - Verifikasi offline
| - Upload dokumentasi barang telah diambil
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/items/{id}/claim', [ClaimController::class, 'store'])
        ->name('items.claim');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
