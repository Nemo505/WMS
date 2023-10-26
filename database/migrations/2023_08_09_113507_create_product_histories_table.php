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
        Schema::create('product_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->foreignId('new_shelf_number_id')->nullable()->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->date('received_date');
            $table->date('new_received_date');

            $table->foreignId('code_id')->references('id')->on('codes')->onDelete('cascade');
            $table->foreignId('new_code_id')->nullable()->references('id')->on('codes')->onDelete('cascade');
            $table->foreignId('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreignId('new_unit_id')->nullable()->references('id')->on('units')->onDelete('cascade');
            
            $table->enum('type', ['opening', 'receive', 'transfer'])->default('receive');
            $table->decimal('received_qty', 14, 3)->nullable();
            $table->decimal('new_received_qty', 14, 3)->nullable();
            $table->decimal('balance_qty', 14, 3)->nullable();
            $table->decimal('transfer_qty', 14, 3)->nullable();
            $table->decimal('mr_qty', 14, 3)->nullable();
            $table->decimal('mrr_qty', 14, 3)->nullable();
            $table->decimal('supplier_return_qty', 14, 3)->nullable();
            
            $table->decimal('sub_adjustment', 14, 3)->nullable();
            $table->decimal('add_adjustment', 14, 3)->nullable();
            $table->integer('transfer_id')->nullable();
           
            $table->foreignId('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreignId('new_supplier_id')->nullable()->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreignId('created_by','user_id')->references('id')->on('users');
            $table->foreignId('updated_by','user_id')->nullable()->references('id')->on('users');
            
            $table->text('remarks')->nullable();
            $table->text('new_remark')->nullable();
            $table->enum('method', ['update', 'delete'])->default('update');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_histories');
    }
};
