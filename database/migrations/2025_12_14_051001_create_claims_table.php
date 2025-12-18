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
        Schema::create('claims', function (Blueprint $table) {
            $table->id(); // id_claim
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->string('nama_pengambil');
            $table->string('NIMorKTP', 25);
            $table->string('phone_pengambil', 15);
            $table->string('foto_pengambil');
            $table->date('tgl_ambil');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
