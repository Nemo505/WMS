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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_no');
            $table->decimal('transfer_qty', 14, 3)->nullable();
            
            $table->foreignId('from_shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->foreignId('to_shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->onDelete('cascade');
            $table->foreignId('code_id')->references('id')->on('codes')->onDelete('cascade');

            $table->foreignId('created_by','user_id')->references('id')->on('users');
            $table->foreignId('updated_by','user_id')->nullable()->references('id')->on('users');
            $table->text('remarks')->nullable();
            $table->date('transfer_date');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
