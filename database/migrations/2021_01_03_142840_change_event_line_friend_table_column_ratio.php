<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEventLineFriendTableColumnRatio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_line_friend', function (Blueprint $table) {
            $table->float('ratio', 8, 2)->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_line_friend', function (Blueprint $table) {
            $table->integer('ratio')->default(1)->change();
        });
    }
}
