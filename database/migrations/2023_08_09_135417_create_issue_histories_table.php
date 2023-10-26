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
        Schema::create('issue_histories', function (Blueprint $table) {
            $table->id();
            $table->string('mr_no')->nullable();
            $table->string('new_mr_no')->nullable();
            $table->decimal('mr_qty', 14, 3)->nullable();
            $table->decimal('new_mr_qty', 14, 3)->nullable();
            $table->decimal('mrr_qty', 14, 3)->nullable();
            $table->decimal('new_mrr_qty', 14, 3)->nullable();

            $table->foreignId('shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');
            $table->foreignId('new_shelf_number_id')->references('id')->on('shelf_numbers')->onDelete('cascade');

            $table->foreignId('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreignId('new_department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreignId('new_product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->foreignId('code_id')->references('id')->on('codes')->onDelete('cascade');
            $table->foreignId('new_code_id')->references('id')->on('codes')->onDelete('cascade');

            $table->foreignId('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreignId('new_customer_id')->references('id')->on('customers')->onDelete('cascade');
            
            $table->foreignId('created_by','user_id')->references('id')->on('users');
            $table->foreignId('updated_by','user_id')->nullable()->references('id')->on('users');
            $table->text('remarks')->nullable();
            $table->text('new_remarks')->nullable();
            $table->enum('method', ['update', 'delete'])->default('update');

            $table->date('issue_date');
            $table->date('new_issue_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_histories');
    }
};
