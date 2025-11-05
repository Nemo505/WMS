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
        Schema::table('issue_return_histories', function (Blueprint $table) {
            $table->string('do_return')->nullable()->after('new_mrr_no');
            $table->string('new_do_return')->nullable()->after('do_return');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issue_return_histories', function (Blueprint $table) {
            $table->dropColumn(['do_return','new_do_return']);
        });
    }
};
