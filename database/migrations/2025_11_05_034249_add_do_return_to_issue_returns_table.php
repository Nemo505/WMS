<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issue_returns', function (Blueprint $table) {
            $table->string('do_return')->nullable()->after('mrr_no');
            $table->unsignedInteger('serial_do_return')->nullable();
            $table->unsignedInteger('serial_mr_no')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issue_returns', function (Blueprint $table) {
            $table->dropColumn(['do_return','serial_do_return, serial_mr_no']);
        });
    }
};
