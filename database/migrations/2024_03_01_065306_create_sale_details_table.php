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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id('DetailID');
            $table->unsignedBigInteger('PenjualanID');
            $table->foreign('PenjualanID')->references('PenjualanID')->on('sales')->onDelete('cascade');
            $table->unsignedBigInteger('ProdukID');
            $table->foreign('ProdukID')->references('ProdukID')->on('products')->onDelete('cascade');
            $table->integer('JumlahProduk');
            $table->decimal('Subtotal', 10, 2);
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
