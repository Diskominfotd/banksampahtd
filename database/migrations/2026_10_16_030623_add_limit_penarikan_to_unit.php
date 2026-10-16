<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bank_sampahs', function (Blueprint $table) {
            $table->unsignedBigInteger('limit_penarikan')->default(5000);
        });
    }

    public function down(): void
    {
        Schema::table('bank_sampahs', function (Blueprint $table) {
            $table->dropColumn('limit_penarikan');
        });
    }
};
