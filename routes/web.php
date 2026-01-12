<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

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
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Auth Pages (Login & Register - SINGLE PAGE)
|--------------------------------------------------------------------------
| Hanya 1 halaman login dan 1 halaman register
*/
Route::middleware('guest')->group(function () {

    // LOGIN
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    // REGISTER
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect (BASED ON ROLE)
|--------------------------------------------------------------------------
| Satu pintu, redirect otomatis sesuai role
*/
Route::get('/dashboard', function () {

    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('dashboard.admin');
    }

    return redirect()->route('dashboard.user');
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Dashboard Pages
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/user', function () {
        return view('homepage-user');
    })->name('dashboard.user');

    Route::get('/dashboard/admin', function () {
        return view('homepage-admin');
    })->name('dashboard.admin');
});

/*
|--------------------------------------------------------------------------
| Home (Optional / Legacy)
|--------------------------------------------------------------------------
*/
Route::get('/home', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');

/*
|--------------------------------------------------------------------------
| Profile Routes (Laravel Breeze Default)
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
*/
Route::middleware('auth')->group(function () {

    Route::post('/items/{id}/claim', [ClaimController::class, 'store'])
        ->name('items.claim');
});
