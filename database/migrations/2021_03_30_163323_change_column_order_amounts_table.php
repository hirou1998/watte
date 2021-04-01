<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnOrderAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amounts', function (Blueprint $table) {
            $table->renameColumn('event_id', 'event_id_old');
        });
        Schema::create('amounts', function (Blueprint $table) {
            $table->uuid('event_id')->before('id');
        });
        DB::table('amounts')->update([
            'event_id' => DB::raw('event_id_old')
        ]);
        Schema::create('amounts', function (Blueprint $table) {
            $table->dropClumn('event_id_old');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
