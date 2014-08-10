<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('history', function(Blueprint $table) {
            $table->integer('id', true);
            $table->string('uuid');
            $table->integer('expires_on');
            $table->timestamps();
        });
        Schema::create('history-players', function(Blueprint $table){
            $table->integer('historyId')->signed();
            $table->string('name');
            $table->timestamps();
        });
        Schema::table('history-players', function($table){
            $table->foreign('historyId')->references('id')->on('history');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('history');
		Schema::drop('history-players');
	}

}
