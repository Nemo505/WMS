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
            $table->id();
            $table->foreignId('shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->date('received_date');
            $table->string('voucher_no');
            $table->string('barcode')->nullable();
            $table->foreignId('code_id')->references('id')->on('codes')->onDelete('cascade');
            $table->foreignId('unit_id')->references('id')->on('units')->onDelete('cascade');

            $table->enum('type', ['opening', 'receive', 'transfer'])->default('receive');
            $table->decimal('received_qty', 14, 3)->nullable();
            $table->decimal('balance_qty', 14, 3)->nullable();
            $table->decimal('transfer_qty', 14, 3)->nullable();
            $table->decimal('mr_qty', 14, 3)->nullable();
            $table->decimal('mrr_qty', 14, 3)->nullable();
            $table->decimal('supplier_return_qty', 14, 3)->nullable();

            $table->decimal('sub_adjustment', 14, 3)->nullable();
            $table->decimal('add_adjustment', 14, 3)->nullable();
            $table->integer('transfer_id')->nullable();

            $table->foreignId('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreignId('created_by','user_id')->references('id')->on('users');
            $table->foreignId('updated_by','user_id')->nullable()->references('id')->on('users');
            
            $table->text('remarks')->nullable();
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
