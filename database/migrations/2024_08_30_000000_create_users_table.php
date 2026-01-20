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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastname');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('document')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0:active 1:inactive');
            $table->tinyInteger('sex')->default(0)->comment('0:man 1:woman');
            $table->date('birthdate')->nullable();
            // Intended relation: ipress.id
            $table->unsignedBigInteger('ipress_id')->nullable();
            //columns for patients
            $table->string('ubigeo')->nullable();
            $table->string('address')->nullable();
            $table->string('clinic_history')->nullable();
            // Intended relation: languages.id
            $table->unsignedBigInteger('language_id')->nullable();
            // Intended relation: etnias.id
            $table->unsignedBigInteger('etnia_id')->nullable();  
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
