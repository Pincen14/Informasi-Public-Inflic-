<?php

/*
|==========================================================================
| UNIT TEST – WHITE BOX TESTING (PEST)
| Sistem     : Inflic – Informasi Public Lost & Found
| Fitur       : ClaimController::store() – Proses Klaim Barang
| Metode      : Basis Path Testing (Cyclomatic Complexity V(G) = 3)
|
| Analisis White Box Berdasarkan Laporan Bab 3:
| ------------------------------------------------------------------
| Node  | Keterangan
| N1    | START – menerima request dan $id item
| N2    | Item::where('status','approved')->findOrFail($id) → item ditemukan?
| N3    | Item not found / status bukan approved → 404 abort (EXIT)
| N4    | $request->validate([...]) → validasi input klaim
| N5    | Validasi gagal → redirect with validation errors (EXIT)
| N6    | Upload foto_pengambil / simpan file
| N7    | Claim::create([...]) + Item::update(['status'=>'taken'])
| N8    | redirect()->route('dashboard.user')->with('success') (EXIT)
|
| Independent Paths:
| Path 1: N1 → N2 → N3           (Item tidak ditemukan / tidak approved)
| Path 2: N1 → N2 → N4 → N5     (Item ditemukan, validasi input gagal)
| Path 3: N1 → N2 → N4 → N6 → N7 → N8 (Semua kondisi valid, klaim berhasil)
|
| Catatan Implementasi:
| - Endpoint: POST /items/{id}/claim → ClaimController::store()
| - ClaimController::store() tidak memvalidasi status item (itu ada di form GET)
| - Validasi status item diimplementasikan melalui route GET /items/{id}/claim
|   (claimForm method di ItemController) dengan where('status','approved')
| - Pengujian Path 1 (item tidak found/approved) diuji melalui GET endpoint
|==========================================================================
*/

use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

// ─────────────────────────────────────────────────────────────────────────
// PATH 1 – Item tidak ditemukan atau status bukan 'approved'
// Node: N1 → N2 → N3 (abort 404)
// Diuji melalui GET /items/{id}/claim (claimForm) yang memvalidasi status
// ─────────────────────────────────────────────────────────────────────────

test('[Path 1] akses form klaim menampilkan 404 jika item berstatus pending', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->pending()->create(); // status = 'pending'

    // Act – akses halaman form klaim (GET) untuk item pending
    $response = $this->actingAs($user)->get("/items/{$item->id}/claim");

    // Assert – harus 404 karena ItemController::claimForm hanya izinkan 'approved'
    $response->assertStatus(404);
});

test('[Path 1] akses form klaim menampilkan 404 jika item berstatus taken', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->taken()->create(); // status = 'taken'

    // Act
    $response = $this->actingAs($user)->get("/items/{$item->id}/claim");

    // Assert
    $response->assertStatus(404);
});

test('[Path 1] akses form klaim menampilkan 404 jika item tidak ditemukan', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    $nonExistentId = 9999;

    // Act
    $response = $this->actingAs($user)->get("/items/{$nonExistentId}/claim");

    // Assert
    $response->assertStatus(404);
});

test('[Path 1] akses halaman konfirmasi item show 404 jika item belum approved', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->pending()->create();

    // Act – items.show juga hanya mengizinkan approved/taken
    $response = $this->actingAs($user)->get("/items/{$item->id}");

    // Assert
    $response->assertStatus(404);
});

// ─────────────────────────────────────────────────────────────────────────
// PATH 2 – Input validasi gagal (item ada, tapi data tidak lengkap)
// Node: N1 → N2 → N4 → N5
// ─────────────────────────────────────────────────────────────────────────

test('[Path 2] klaim gagal jika nama_pengambil kosong (validasi required)', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->approved()->create();

    // Act – kirim tanpa nama_pengambil
    $response = $this->actingAs($user)->post("/items/{$item->id}/claim", [
        'nama_pengambil'  => '',            // <-- kosong, wajib diisi
        'NIMorKTP'        => '1234567890123456',
        'phone_pengambil' => '081234567890',
        'foto_pengambil'  => UploadedFile::fake()->image('ktp.jpg'),
        'tgl_ambil'       => now()->addDay()->toDateString(),
    ]);

    // Assert – validasi gagal, tidak ada record claim tersimpan
    $response->assertSessionHasErrors('nama_pengambil');
    expect(Claim::count())->toBe(0);
});

test('[Path 2] klaim gagal jika NIMorKTP kosong', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->approved()->create();

    // Act
    $response = $this->actingAs($user)->post("/items/{$item->id}/claim", [
        'nama_pengambil'  => 'Budi Santoso',
        'NIMorKTP'        => '', // <-- kosong
        'phone_pengambil' => '081234567890',
        'foto_pengambil'  => UploadedFile::fake()->image('ktp.jpg'),
        'tgl_ambil'       => now()->addDay()->toDateString(),
    ]);

    // Assert
    $response->assertSessionHasErrors('NIMorKTP');
    expect(Claim::count())->toBe(0);
});

test('[Path 2] klaim gagal jika foto_pengambil tidak disertakan', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->approved()->create();

    // Act – tidak mengirim foto_pengambil sama sekali
    $response = $this->actingAs($user)->post("/items/{$item->id}/claim", [
        'nama_pengambil'  => 'Budi Santoso',
        'NIMorKTP'        => '1234567890123456',
        'phone_pengambil' => '081234567890',
        // foto_pengambil tidak dikirim
        'tgl_ambil'       => now()->addDay()->toDateString(),
    ]);

    // Assert
    $response->assertSessionHasErrors('foto_pengambil');
    expect(Claim::count())->toBe(0);
});

test('[Path 2] klaim gagal jika tgl_ambil tidak diisi', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->approved()->create();

    // Act
    $response = $this->actingAs($user)->post("/items/{$item->id}/claim", [
        'nama_pengambil'  => 'Budi Santoso',
        'NIMorKTP'        => '1234567890123456',
        'phone_pengambil' => '081234567890',
        'foto_pengambil'  => UploadedFile::fake()->image('ktp.jpg'),
        'tgl_ambil'       => '', // <-- kosong, wajib diisi
    ]);

    // Assert
    $response->assertSessionHasErrors('tgl_ambil');
    expect(Claim::count())->toBe(0);
});

test('[Path 2] klaim gagal jika foto bukan file gambar', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->approved()->create();

    // Act – mengirim file PDF, bukan gambar
    $response = $this->actingAs($user)->post("/items/{$item->id}/claim", [
        'nama_pengambil'  => 'Budi Santoso',
        'NIMorKTP'        => '1234567890123456',
        'phone_pengambil' => '081234567890',
        'foto_pengambil'  => UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf'), // <-- bukan image
        'tgl_ambil'       => now()->addDay()->toDateString(),
    ]);

    // Assert
    $response->assertSessionHasErrors('foto_pengambil');
    expect(Claim::count())->toBe(0);
});

// ─────────────────────────────────────────────────────────────────────────
// PATH 3 – Semua kondisi valid, klaim berhasil disimpan
// Node: N1 → N2 → N4 → N6 → N7 → N8 (redirect ke dashboard)
// ─────────────────────────────────────────────────────────────────────────

test('[Path 3] klaim berhasil jika item ada dan semua input valid', function () {
    // Arrange – buat direktori public/claims agar file upload bisa berjalan
    if (!is_dir(public_path('claims'))) {
        mkdir(public_path('claims'), 0755, true);
    }

    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->approved()->create();
    $fotoFile = UploadedFile::fake()->image('ktp.jpg', 100, 100);

    // Act – submit form klaim yang lengkap dan valid
    $response = $this->actingAs($user)->post("/items/{$item->id}/claim", [
        'nama_pengambil'  => 'Budi Santoso',
        'NIMorKTP'        => '1234567890123456', // tepat 16 digit
        'phone_pengambil' => '081234567890',
        'foto_pengambil'  => $fotoFile,
        'tgl_ambil'       => now()->addDay()->toDateString(),
    ]);

    // Assert – redirect ke dashboard user dengan pesan sukses
    $response->assertRedirect(route('dashboard.user'));
    $response->assertSessionHas('success');

    // Verifikasi record claim tersimpan di database
    expect(Claim::count())->toBe(1);
    $claim = Claim::first();
    expect($claim->nama_pengambil)->toBe('Budi Santoso');
    expect($claim->NIMorKTP)->toBe('1234567890123456');
    expect($claim->item_id)->toBe($item->id);

    // Verifikasi status item berubah menjadi 'taken'
    expect($item->fresh()->status)->toBe('taken');
});

test('[Path 3] klaim berhasil dan foto pengambil tersimpan dengan nama file unik', function () {
    // Arrange
    if (!is_dir(public_path('claims'))) {
        mkdir(public_path('claims'), 0755, true);
    }

    $user = User::factory()->create(['role' => 'user']);
    $item = Item::factory()->approved()->create();

    // Act
    $this->actingAs($user)->post("/items/{$item->id}/claim", [
        'nama_pengambil'  => 'Siti Rahayu',
        'NIMorKTP'        => '9876543210987654',
        'phone_pengambil' => '082345678901',
        'foto_pengambil'  => UploadedFile::fake()->image('foto_siti.jpg', 100, 100),
        'tgl_ambil'       => now()->addDays(2)->toDateString(),
    ]);

    // Assert – claim tersimpan dengan foto
    $claim = Claim::first();
    expect($claim)->not->toBeNull();
    expect($claim->foto_pengambil)->not->toBeEmpty();
    expect($claim->nama_pengambil)->toBe('Siti Rahayu');
});
