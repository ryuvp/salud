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
        Schema::create('prescription', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diagnostic_id');
            $table->unsignedBigInteger('medicament_id');
            $table->foreign('diagnostic_id')->references('id')->on('diagnostic')->onDelete('cascade');
            $table->foreign('medicament_id')->references('id')->on('medicament')->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->integer('frequency')->nullable();
            $table->date('start_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription');
    }
};
