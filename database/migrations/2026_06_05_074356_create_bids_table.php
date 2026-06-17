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
        Schema::create('bids', function (Blueprint $table) {
            //$table->id();
            $table->increments('bid_id');
           
            $table->string('product_id');
            $table->string('bidder_id');

            $table->integer('bid_amount');
            $table->datetime('bid_at');
            $table->timestamps();

             $table->foreign('product_id')
                  ->references('product_id')
                  ->on('products')
                  ->onDelete('cascade');

            
            $table->foreign('bidder_id')
                  ->references('user_id')
                  ->on('yic_users') 
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
