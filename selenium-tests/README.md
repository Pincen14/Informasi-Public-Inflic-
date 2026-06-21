# Selenium UI tests — Infogritas

Skenario ini disesuaikan dengan state transition sistem Infogritas:

`Login -> Dashboard sesuai peran -> Lapor barang (pending) -> Admin menyetujui (approved)`

## Jalankan

Dari folder proyek Laravel:

```powershell
php artisan migrate:fresh --seed
php artisan serve
```

Di terminal kedua:

```powershell
cd selenium-tests
python -m pip install -r requirements.txt
python -m pytest -v
```

Test berjalan headless secara default. Gunakan `$env:HEADLESS='false'` sebelum `pytest` untuk melihat browser Chrome.

Jika Chrome belum tersedia, Microsoft Edge Chromium juga dapat dipakai untuk menjalankan Selenium pada Windows:

```powershell
$env:BROWSER='edge'
python -m pytest -v
```

Akun seeder: `admin@admin.com` / `password` dan `user@student.com` / `password`.
