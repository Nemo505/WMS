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
        Schema::table('issue_histories', function (Blueprint $table) {
            $table->string('do_no')->nullable()->after('new_mr_no');
            $table->string('new_do_no')->nullable()->after('do_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issue_histories', function (Blueprint $table) {
            $table->dropColumn(['do_no', 'new_do_no']);
        });
    }
};
