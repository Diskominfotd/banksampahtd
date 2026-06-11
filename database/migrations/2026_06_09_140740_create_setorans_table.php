<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('setorans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyetor_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->decimal('total_berat', 12, 2)->default(0);
            $table->decimal('total_saldo', 12, 2)->default(0);
            $table->date('tanggal')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setorans');
    }
};
