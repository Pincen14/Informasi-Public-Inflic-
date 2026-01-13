<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->time('time_found')->after('date_found'); // Waktu ditemukan
            $table->string('finder_name')->after('time_found'); // Nama penemu
            $table->string('finder_contact')->after('finder_name'); // Kontak penemu (private)
            $table->string('admin_contact')->nullable()->after('finder_contact'); // Kontak admin (public)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['time_found', 'finder_name', 'finder_contact', 'admin_contact']);
        });
    }
};