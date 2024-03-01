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
        Schema::create('sales', function (Blueprint $table) {
            $table->id('PenjualanID');
            $table->dateTime('TanggalPenjualan');
            $table->decimal('TotalHarga', 10, 2);
            $table->unsignedBigInteger('PelangganID');
            $table->foreign('PelangganID')->references('PelangganID')->on('customers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
