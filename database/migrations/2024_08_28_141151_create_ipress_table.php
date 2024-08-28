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
        Schema::create('ipress', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->string('clasification')->nullable();
            $table->string('type')->nullable();
            $table->string('ubigeo')->nullable();
            $table->string('address')->nullable();
            $table->string('disa_code')->nullable();
            $table->string('disa_name')->nullable();
            $table->string('red code')->nullable();
            $table->string('red_name')->nullable();
            $table->string('microred_code')->nullable();
            $table->string('microred_name')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipress');
    }
};
