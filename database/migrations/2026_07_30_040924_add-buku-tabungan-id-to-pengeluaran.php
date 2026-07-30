<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->foreignId('buku_tabungan_id')
                ->nullable()
                ->constrained('buku_tabungans')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('buku_tabungan_id');
        });
    }
};
