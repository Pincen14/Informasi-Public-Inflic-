<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman login dapat diakses oleh guest', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
});

test('[EP1] login berhasil dengan kredensial valid sebagai user dan redirect ke dashboard user', function () {
    $user = User::factory()->student()->create([
        'email' => 'mahasiswa@student.com',
    ]);

    $response = $this->post('/login', [
        'email'    => 'mahasiswa@student.com',
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard.user'));
});


test('[EP2] login berhasil sebagai admin dan redirect ke dashboard admin', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'superadmin@admin.com',
    ]);

    $response = $this->post('/login', [
        'email'    => 'superadmin@admin.com',
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('admin.dashboard'));
});

test('[EP3] login gagal jika password salah', function () {
    $user = User::factory()->student()->create([
        'email' => 'mahasiswa@student.com',
    ]);

    $response = $this->post('/login', [
        'email'    => 'mahasiswa@student.com',
        'password' => 'passwordSalah123', 
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('[EP4] login gagal jika email tidak terdaftar di sistem', function () {
    $response = $this->post('/login', [
        'email'    => 'tidakterdaftar@student.com',
        'password' => 'Password123!',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});


test('[EP5] login gagal jika field email kosong', function () {
    $response = $this->post('/login', [
        'email'    => '',
        'password' => 'Password123!',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});


test('[EP6] login gagal jika field password kosong', function () {
    $user = User::factory()->student()->create([
        'email' => 'mahasiswa@student.com',
    ]);

    $response = $this->post('/login', [
        'email'    => 'mahasiswa@student.com',
        'password' => '', 
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('password');
});

test('[BVA1] login gagal jika password yang dimasukkan hanya 1 karakter', function () {
    $user = User::factory()->student()->create([
        'email' => 'mahasiswa@student.com',
    ]);

    $response = $this->post('/login', [
        'email'    => 'mahasiswa@student.com',
        'password' => 'a', 
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('[BVA2] login gagal jika password berisi string kosong (boundary = 0 karakter)', function () {
    $user = User::factory()->student()->create([
        'email' => 'mahasiswa@student.com',
    ]);

    $response = $this->post('/login', [
        'email'    => 'mahasiswa@student.com',
        'password' => '', 
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('password');
});

test('user dapat logout dan diarahkan ke halaman utama', function () {
    $user = User::factory()->student()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
