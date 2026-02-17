<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
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
		Schema::create('players', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('position', 10)->nullable();
            $table->string('height', 20)->nullable();
            $table->string('weight', 20)->nullable();
            $table->string('jersey_number', 10)->nullable();
            $table->string('college', 150)->nullable();
            $table->string('country', 100)->nullable();
            $table->integer('draft_year')->nullable();
            $table->integer('draft_round')->nullable();
            $table->integer('draft_number')->nullable();
            $table->unsignedInteger('team_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('players');
	}
};
