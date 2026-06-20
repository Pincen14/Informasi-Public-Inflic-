<?php

/*
|==========================================================================
| FEATURE TEST – BLACK BOX TESTING (PEST)
| Sistem     : Inflic – Informasi Public Lost & Found
| Fitur       : Lapor Barang Ditemukan (Store Item)
| Metode      : Equivalence Partitioning (EP) + Boundary Value Analysis (BVA)
|
| Skenario Pengujian Berdasarkan Laporan Bab 2:
| ─────────────────────────────────────────────────────────────────────────
| EP1: Semua field valid → laporan tersimpan, status 'pending', redirect dashboard
| EP2: Field nama_item kosong (required) → validasi gagal
| EP3: File image bukan format gambar (misal .pdf) → validasi gagal
| EP4: File image melebihi batas ukuran 2048 KB → validasi gagal
| EP5: Field location_found kosong → validasi gagal
| EP6: Field date_found bukan format tanggal → validasi gagal
| EP7: Field finder_name kosong → validasi gagal
| EP8: Guest (tidak login) mencoba lapor → redirect ke login
|
| BVA – Field nama_item (max:255 karakter):
| BVA1: nama_item tepat 255 karakter (batas atas) → valid
| BVA2: nama_item 256 karakter (melebihi batas)   → invalid
|==========================================================================
*/

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);


test('halaman form lapor barang dapat diakses oleh user yang login', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/items/create');
    expect($response->status())->not->toBe(302); 
    expect($response->status())->not->toBe(403); 
});

test('[EP1] laporan berhasil disimpan jika semua data diisi dengan benar', function () {
    Storage::fake('public');
    $user = User::factory()->student()->create();

    $response = $this->actingAs($user)->post('/items', [
        'nama_item'      => 'Dompet Kulit Hitam',
        'description'    => 'Dompet hitam berisi KTP dan kartu ATM',
        'image'          => UploadedFile::fake()->image('dompet.jpg'),
        'location_found' => 'Gedung A Lantai 3',
        'date_found'     => now()->toDateString(),
        'time_found'     => '10:30',
        'finder_name'    => 'Andi Prasetyo',
        'finder_contact' => '081234567890',
    ]);

    $response->assertRedirect(route('dashboard.user'));
    $response->assertSessionHas('success');

    expect(Item::count())->toBe(1);

    $item = Item::first();
    expect($item->nama_item)->toBe('Dompet Kulit Hitam');
    expect($item->status)->toBe('pending');       
    expect($item->user_id)->toBe($user->id);       
});


test('[EP2] laporan gagal jika nama_item kosong', function () {
    Storage::fake('public');
    $user = User::factory()->student()->create();

    $response = $this->actingAs($user)->post('/items', [
        'nama_item'      => '', // <-- kosong
        'image'          => UploadedFile::fake()->image('item.jpg'),
        'location_found' => 'Gedung B',
        'date_found'     => now()->toDateString(),
        'time_found'     => '09:00',
        'finder_name'    => 'Andi',
        'finder_contact' => '081234567890',
    ]);

    $response->assertSessionHasErrors('nama_item');
    expect(Item::count())->toBe(0);
});


test('[EP3] laporan gagal jika file yang diupload bukan file gambar', function () {
    Storage::fake('public');
    $user = User::factory()->student()->create();

    $response = $this->actingAs($user)->post('/items', [
        'nama_item'      => 'Tas Ransel',
        'image'          => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'), // <-- bukan image
        'location_found' => 'Kantin',
        'date_found'     => now()->toDateString(),
        'time_found'     => '12:00',
        'finder_name'    => 'Siti',
        'finder_contact' => '082345678901',
    ]);

    $response->assertSessionHasErrors('image');
    expect(Item::count())->toBe(0);
});


test('[EP4] laporan gagal jika ukuran gambar melebihi 2048 KB', function () {
    Storage::fake('public');
    $user = User::factory()->student()->create();

    $response = $this->actingAs($user)->post('/items', [
        'nama_item'      => 'Laptop Asus',
        'image'          => UploadedFile::fake()->image('laptop.jpg')->size(3000), // <-- 3000 KB, melebihi 2048 KB
        'location_found' => 'Perpustakaan',
        'date_found'     => now()->toDateString(),
        'time_found'     => '14:00',
        'finder_name'    => 'Rizky',
        'finder_contact' => '083456789012',
    ]);

    $response->assertSessionHasErrors('image');
    expect(Item::count())->toBe(0);
});


test('[EP5] laporan gagal jika location_found kosong', function () {
    Storage::fake('public');
    $user = User::factory()->student()->create();

    $response = $this->actingAs($user)->post('/items', [
        'nama_item'      => 'Kunci Motor',
        'image'          => UploadedFile::fake()->image('kunci.jpg'),
        'location_found' => '', // <-- kosong
        'date_found'     => now()->toDateString(),
        'time_found'     => '08:00',
        'finder_name'    => 'Dewi',
        'finder_contact' => '084567890123',
    ]);

    $response->assertSessionHasErrors('location_found');
    expect(Item::count())->toBe(0);
});


test('[EP6] laporan gagal jika date_found bukan format tanggal yang valid', function () {
    Storage::fake('public');
    $user = User::factory()->student()->create();

    $response = $this->actingAs($user)->post('/items', [
        'nama_item'      => 'Headphone',
        'image'          => UploadedFile::fake()->image('hp.jpg'),
        'location_found' => 'Ruang Kelas 101',
        'date_found'     => 'bukan-tanggal', 
        'time_found'     => '15:00',
        'finder_name'    => 'Hendra',
        'finder_contact' => '085678901234',
    ]);

    $response->assertSessionHasErrors('date_found');
    expect(Item::count())->toBe(0);
});


test('[EP7] laporan gagal jika finder_name (nama penemu) kosong', function () {
    Storage::fake('public');
    $user = User::factory()->student()->create();

    $response = $this->actingAs($user)->post('/items', [
        'nama_item'      => 'Charger HP',
        'image'          => UploadedFile::fake()->image('charger.jpg'),
        'location_found' => 'Lobi Utama',
        'date_found'     => now()->toDateString(),
        'time_found'     => '16:00',
        'finder_name'    => '',
        'finder_contact' => '086789012345',
    ]);

    $response->assertSessionHasErrors('finder_name');
    expect(Item::count())->toBe(0);
});

test('[EP8] guest tidak dapat mengakses form lapor barang dan diarahkan ke login', function () {
    $response = $this->get('/items/create');
    $response->assertRedirect('/login');
});

test('[EP8] guest tidak dapat submit laporan barang', function () {
    Storage::fake('public');

    $response = $this->post('/items', [
        'nama_item'      => 'Barang Test',
        'image'          => UploadedFile::fake()->image('item.jpg'),
        'location_found' => 'Gedung C',
        'date_found'     => now()->toDateString(),
        'time_found'     => '10:00',
        'finder_name'    => 'Guest',
        'finder_contact' => '081234567890',
    ]);

    $response->assertRedirect('/login');
    expect(Item::count())->toBe(0);
});

test('[BVA1] laporan berhasil jika nama_item tepat 255 karakter (batas atas)', function () {
    Storage::fake('public');
    $user = User::factory()->student()->create();

    $namaItem255 = str_repeat('A', 255); 
    expect(strlen($namaItem255))->toBe(255);

    $response = $this->actingAs($user)->post('/items', [
        'nama_item'      => $namaItem255,
        'image'          => UploadedFile::fake()->image('item.jpg'),
        'location_found' => 'Gedung D',
        'date_found'     => now()->toDateString(),
        'time_found'     => '11:00',
        'finder_name'    => 'Tester',
        'finder_contact' => '081234567890',
    ]);

    $response->assertRedirect(route('dashboard.user'));
    expect(Item::count())->toBe(1);
});

test('[BVA2] laporan gagal jika nama_item melebihi 255 karakter', function () {
    Storage::fake('public');
    $user = User::factory()->student()->create();

    $namaItem256 = str_repeat('A', 256); 
    expect(strlen($namaItem256))->toBe(256);

    $response = $this->actingAs($user)->post('/items', [
        'nama_item'      => $namaItem256,
        'image'          => UploadedFile::fake()->image('item.jpg'),
        'location_found' => 'Gedung E',
        'date_found'     => now()->toDateString(),
        'time_found'     => '11:30',
        'finder_name'    => 'Tester',
        'finder_contact' => '081234567890',
    ]);

    $response->assertSessionHasErrors('nama_item');
    expect(Item::count())->toBe(0);
});
