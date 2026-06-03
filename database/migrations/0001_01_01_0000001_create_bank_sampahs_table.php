<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bank_sampahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis', ['induk', 'unit'])
            ->default('unit');
            $table->foreignId('parent_id')->nullable()
            ->constrained('bank_sampahs')->nullOnDelete();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->boolean('use_parent_price')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_sampahs');
    }
};
