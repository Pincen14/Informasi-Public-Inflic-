<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

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
| Auth Pages
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
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
| Dashboard Redirect
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('dashboard.user');
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Dashboard Pages
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/user', [ItemController::class, 'userDashboard'])->name('dashboard.user');
    Route::get('/dashboard/admin', [ItemController::class, 'adminDashboard'])->name('dashboard.admin');
});

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Item Routes (User)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Form lapor barang ditemukan
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');

    // Detail barang
    Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');

    // Klaim barang
    Route::get('/items/{id}/claim', [ItemController::class, 'claimForm'])->name('items.claim.form');
    Route::post('/items/{id}/claim', [ClaimController::class, 'store'])->name('items.claim');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/items/{id}', [ItemController::class, 'adminShow'])->name('items.show');
    Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::post('/items/{id}/approve', [ItemController::class, 'approve'])->name('items.approve');
    Route::post('/items/{id}/reject', [ItemController::class, 'reject'])->name('items.reject');
    Route::post('/items/{id}/taken', [ItemController::class, 'markAsTaken'])->name('items.taken');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
});
