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
        Schema::create('setoran_items', function (Blueprint $table) {
          $table->id();
            $table->foreignId('setoran_id')->constrained('setorans')->cascadeOnDelete();
            $table->foreignId('price_id')->constrained('prices')->cascadeOnDelete();
            $table->foreignId('trash_id')->constrained('trashes')->cascadeOnDelete();
            $table->decimal('berat', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_items');
    }
};
