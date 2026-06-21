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
        Schema::create('buku_tabungans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nomor_rekening');
            $table->decimal('saldo', 12, 2)->default(0);
            $table->foreignId('bank_id')->nullable()->constrained('bank_sampahs')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_tabungans');
    }
};
