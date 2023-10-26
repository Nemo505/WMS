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
        Schema::create('supplier_return_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->foreignId('new_shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreignId('new_product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->foreignId('code_id')->references('id')->on('codes')->onDelete('cascade');
            $table->foreignId('new_code_id')->references('id')->on('codes')->onDelete('cascade');

            $table->string('supplier_return_no')->nullable();
            $table->string('new_supplier_return_no')->nullable();

            $table->decimal('supplier_return_qty', 14, 3)->nullable();
            $table->decimal('new_supplier_return_qty', 14, 3)->nullable();
            
            $table->foreignId('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreignId('new_supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            $table->foreignId('created_by','user_id')->references('id')->on('users');
            $table->foreignId('updated_by','user_id')->nullable()->references('id')->on('users');
            
            $table->text('remarks')->nullable();
            $table->text('new_remarks')->nullable();
            $table->enum('method', ['update', 'delete'])->default('update');
            
            $table->date('supplier_return_date');
            $table->date('new_supplier_return_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_return_histories');
    }
};
