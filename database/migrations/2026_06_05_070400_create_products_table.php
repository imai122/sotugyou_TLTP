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
        Schema::create('products', function (Blueprint $table) {
           //$table->id();
            $table->bigIncrements('product_id');
            //$table->primary('product_id');
            $table->string('seller_id');
            $table->foreign('seller_id')->references('user_id')->on('yic_users');
            $table->integer('category_id'); 
            $table->foreign('category_id')->references('category_id')->on('categories');
            $table->string('product_name');
            $table->string('image_path')->nullable();
            $table->string('comment');
            $table->integer('wish_price');
            $table->datetime('end_date');
            //$table->datetime('create_at');
            $table->string('status');
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
