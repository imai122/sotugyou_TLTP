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
        Schema::create('yic_users', function (Blueprint $table) {
            //$table->id();
            $table->string('user_id');
            $table->primary('user_id');
            $table->string('password');
            $table->integer('role');
            $table->string('name');
            $table->string('postal_code');
            $table->string('address');
            $table->string('phone_number');
            $table->string('email');
            $table->string('bank_account');
            $table->integer('listing_count')->default(0);
            $table->integer('purchase_count')->default(0);
            $table->integer('rating')->nullable();
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yic_users');
    }
};
