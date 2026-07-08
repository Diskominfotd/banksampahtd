<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->decimal('total_penarikan', 12, 2);
            $table->string('keterangan');
            $table->foreignId('admin_id')->nullable()
            ->constrained('users')->nullOnDelete();
            $table->foreignId('gudang_id')->nullable()
            ->constrained('gudangs')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
