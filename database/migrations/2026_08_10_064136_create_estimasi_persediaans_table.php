<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estimasi_persediaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id')->unique(); // scoping per unit, sama pola seperti Gudang/BukuTabungan
            $table->decimal('nilai', 15, 2)->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estimasi_persediaans');
    }
};