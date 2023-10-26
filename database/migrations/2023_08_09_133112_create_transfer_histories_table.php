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
        Schema::create('transfer_histories', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_no');
            $table->string('new_transfer_no');
            $table->decimal('transfer_qty', 14, 3)->nullable();
            $table->decimal('new_transfer_qty', 14, 3)->nullable();
            
            $table->foreignId('from_shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->foreignId('new_from_shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->foreignId('to_shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->foreignId('new_to_shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreignId('new_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreignId('code_id')->references('id')->on('codes')->onDelete('cascade');
            $table->foreignId('new_code_id')->references('id')->on('codes')->onDelete('cascade');

            $table->foreignId('created_by','user_id')->references('id')->on('users');
            $table->foreignId('updated_by','user_id')->nullable()->references('id')->on('users');
            $table->text('remarks')->nullable();
            $table->text('new_remarks')->nullable();
            
            $table->date('transfer_date');
            $table->date('new_transfer_date');
            $table->enum('method', ['update', 'delete'])->default('update');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_histories');
    }
};
