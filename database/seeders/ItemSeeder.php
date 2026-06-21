<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Item::create([
            'user_id'         => 2,
            'nama_item'       => 'Dompet Hitam',
            'description'     => 'Dompet hitam ditemukan di depan kantin',
            'image'           => 'default.jpg',
            'location_found'  => 'Kantin Lantai 1',
            'date_found'      => '2026-06-13',
            'time_found'      => '10:00:00',
            'finder_name'     => 'User Inflic',
            'finder_contact'  => '081234567891',
            'status'          => 'pending',
        ]);

        \App\Models\Item::create([
            'user_id'         => 2,
            'nama_item'       => 'Kunci Motor',
            'description'     => 'Kunci motor ditemukan di parkiran',
            'image'           => 'default.jpg',
            'location_found'  => 'Parkiran Gedung B',
            'date_found'      => '2026-06-13',
            'time_found'      => '09:00:00',
            'finder_name'     => 'User Inflic',
            'finder_contact'  => '081234567891',
            'status'          => 'approved',
            'admin_contact'   => '081234567890',
        ]);

        \App\Models\Item::create([
            'user_id'         => 2,
            'nama_item'       => 'Tas Ransel',
            'description'     => 'Tas ransel ditemukan di perpustakaan',
            'image'           => 'default.jpg',
            'location_found'  => 'Perpustakaan Lantai 2',
            'date_found'      => '2026-06-13',
            'time_found'      => '11:00:00',
            'finder_name'     => 'User Inflic',
            'finder_contact'  => '081234567891',
            'status'          => 'approved',
            'admin_contact'   => '082233445566',
        ]);
    }
}
