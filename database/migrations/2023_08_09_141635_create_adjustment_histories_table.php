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
        Schema::create('adjustment_histories', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_no')->nullable();
            $table->string('new_adjustment_no')->nullable();

            $table->decimal('qty', 14, 3)->nullable();
            $table->decimal('new_qty', 14, 3)->nullable();

             $table->enum('type', ['sub', 'add']);
             $table->enum('new_type', ['sub', 'add']);

            $table->foreignId('shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->foreignId('new_shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreignId('new_product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->foreignId('code_id')->references('id')->on('codes')->onDelete('cascade');
            $table->foreignId('new_code_id')->references('id')->on('codes')->onDelete('cascade');

            $table->foreignId('created_by','user_id')->references('id')->on('users');
            $table->foreignId('updated_by','user_id')->nullable()->references('id')->on('users');
            $table->text('remarks')->nullable();
            $table->text('new_remarks')->nullable();

            $table->date('adjustment_date');
            $table->date('new_adjustment_date');
            $table->enum('method', ['update', 'delete'])->default('update');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustment_histories');
    }
};
