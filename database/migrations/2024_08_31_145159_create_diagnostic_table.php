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
        Schema::create('diagnostic', function (Blueprint $table) {
            $table->id();
            // Intended relation: users.id
            $table->unsignedBigInteger('user_id');
            // Intended relation: cie10.id
            $table->unsignedBigInteger('cie10_id');
            // Intended relation: ipress.id
            $table->unsignedBigInteger('ipress_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostic');
    }
};
