<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman register dapat diakses', function () {
    $response = $this->get('/register');
    $response->assertStatus(200);
});

test('[EP1] register berhasil dengan email @student.com dan role menjadi user', function () {
    $response = $this->post('/register', [
        'name'                  => 'Budi Santoso',
        'username'              => 'budisantoso',
        'email'                 => 'budi@student.com',
        'phone'                 => '081234567890',
        'password'              => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect(route('dashboard.user'));
    $this->assertAuthenticated();

    $user = User::where('email', 'budi@student.com')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe('user');
});

test('[EP2] register berhasil dengan email @admin.com dan role menjadi admin', function () {
    $response = $this->post('/register', [
        'name'                  => 'Admin Sistem',
        'username'              => 'adminsistem',
        'email'                 => 'admin@admin.com',
        'phone'                 => '081298765432',
        'password'              => 'Admin1234!',
        'password_confirmation' => 'Admin1234!',
    ]);

    $response->assertRedirect(route('dashboard.user'));
    $this->assertAuthenticated();

    $user = User::where('email', 'admin@admin.com')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe('admin');
});

test('[EP3] register gagal jika email tidak menggunakan domain @student.com atau @admin.com', function () {
    $response = $this->post('/register', [
        'name'                  => 'User Tidak Valid',
        'username'              => 'usertidakvalid',
        'email'                 => 'user@gmail.com', // <-- domain tidak diizinkan
        'phone'                 => '081234567890',
        'password'              => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
    expect(User::count())->toBe(0);
});

test('[EP3] register gagal jika email tidak menggunakan domain @yahoo.com', function () {
    $response = $this->post('/register', [
        'name'                  => 'User Yahoo',
        'username'              => 'useryahoo',
        'email'                 => 'user@yahoo.com',
        'phone'                 => '081234567890',
        'password'              => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('[EP4] register gagal jika username sudah digunakan', function () {
    User::factory()->create(['username' => 'budisantoso']);

    $response = $this->post('/register', [
        'name'                  => 'Budi Lain',
        'username'              => 'budisantoso',
        'email'                 => 'budilain@student.com',
        'phone'                 => '081234500000',
        'password'              => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

test('[EP5] register gagal jika password dan konfirmasi password tidak cocok', function () {
    $response = $this->post('/register', [
        'name'                  => 'Budi Santoso',
        'username'              => 'budisantoso',
        'email'                 => 'budi@student.com',
        'phone'                 => '081234567890',
        'password'              => 'Password123!',
        'password_confirmation' => 'BedaPassword!', 
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertGuest();
});

test('[EP6] register gagal jika field name kosong', function () {
    $response = $this->post('/register', [
        'name'                  => '', 
        'username'              => 'budisantoso',
        'email'                 => 'budi@student.com',
        'phone'                 => '081234567890',
        'password'              => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertSessionHasErrors('name');
    $this->assertGuest();
});

test('[EP6] register gagal jika field phone kosong', function () {
    $response = $this->post('/register', [
        'name'                  => 'Budi Santoso',
        'username'              => 'budisantoso',
        'email'                 => 'budi@student.com',
        'phone'                 => '',
        'password'              => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertSessionHasErrors('phone');
    $this->assertGuest();
});

test('[BVA1] register berhasil dengan password tepat 8 karakter (batas bawah minimum)', function () {
    $response = $this->post('/register', [
        'name'                  => 'Budi Santoso',
        'username'              => 'budibva1',
        'email'                 => 'budi.bva1@student.com',
        'phone'                 => '081234567890',
        'password'              => 'Pass123!',        
        'password_confirmation' => 'Pass123!',
    ]);

    $response->assertRedirect(route('dashboard.user'));
    $this->assertAuthenticated();
});

test('[BVA2] register gagal dengan password 7 karakter (di bawah minimum)', function () {
    $response = $this->post('/register', [
        'name'                  => 'Budi Santoso',
        'username'              => 'budibva2',
        'email'                 => 'budi.bva2@student.com',
        'phone'                 => '081234567890',
        'password'              => 'Pass12!',         
        'password_confirmation' => 'Pass12!',
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertGuest();
});

test('[BVA3] register berhasil dengan password panjang 50 karakter', function () {
    $longPassword = 'Password123!Password123!Password123!Password123!Pa'; 
    expect(strlen($longPassword))->toBe(50);

    $response = $this->post('/register', [
        'name'                  => 'Budi Santoso',
        'username'              => 'budibva3',
        'email'                 => 'budi.bva3@student.com',
        'phone'                 => '081234567890',
        'password'              => $longPassword,
        'password_confirmation' => $longPassword,
    ]);

    $response->assertRedirect(route('dashboard.user'));
    $this->assertAuthenticated();
});
