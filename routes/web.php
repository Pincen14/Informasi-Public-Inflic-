<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ProfileController;

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
/*
|--------------------------------------------------------------------------
| User Routes (Dashboard, Profile, and Item Reporting)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.user'])->group(function () {
    // Dashboard User
    Route::get('/dashboard/user', [ItemController::class, 'userDashboard'])->name('dashboard.user');

    // Profile User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Form lapor barang ditemukan
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');

    // Detail barang (User)
    Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');

    // Klaim barang
    Route::get('/items/{id}/claim', [ItemController::class, 'claimForm'])->name('items.claim.form');
    Route::post('/items/{id}/claim', [ClaimController::class, 'store'])->name('items.claim');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Dashboard, Profile, and Item Management)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.admin'])->group(function () {
    // Dashboard Admin
    Route::get('/dashboard/admin', [ItemController::class, 'adminDashboard'])->name('admin.dashboard');

    // Profile Admin
    Route::get('/admin/profile', [ProfileController::class, 'adminEdit'])->name('admin.profile.edit');
    Route::patch('/admin/profile', [ProfileController::class, 'adminUpdate'])->name('admin.profile.update');
    Route::delete('/admin/profile', [ProfileController::class, 'adminDestroy'])->name('admin.profile.destroy');
});

Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/items/{id}', [ItemController::class, 'adminShow'])->name('items.show');
    Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::post('/items/{id}/approve', [ItemController::class, 'approve'])->name('items.approve');
    Route::post('/items/{id}/reject', [ItemController::class, 'reject'])->name('items.reject');
    Route::post('/items/{id}/taken', [ItemController::class, 'markAsTaken'])->name('items.taken');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
});

require __DIR__.'/auth.php';
