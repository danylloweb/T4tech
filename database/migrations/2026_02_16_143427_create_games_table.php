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
		Schema::create('games', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->integer('season');
            $table->string('status', 50);
            $table->integer('period')->nullable();
            $table->string('time', 20)->nullable();
            $table->boolean('postseason')->default(false);
            $table->boolean('postponed')->default(false);
            $table->integer('home_team_score')->nullable();
            $table->integer('visitor_team_score')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->integer('home_q1')->nullable();
            $table->integer('home_q2')->nullable();
            $table->integer('home_q3')->nullable();
            $table->integer('home_q4')->nullable();
            $table->integer('home_ot1')->nullable();
            $table->integer('home_ot2')->nullable();
            $table->integer('home_ot3')->nullable();
            $table->integer('home_timeouts_remaining')->nullable();
            $table->boolean('home_in_bonus')->nullable();
            $table->integer('visitor_q1')->nullable();
            $table->integer('visitor_q2')->nullable();
            $table->integer('visitor_q3')->nullable();
            $table->integer('visitor_q4')->nullable();
            $table->integer('visitor_ot1')->nullable();
            $table->integer('visitor_ot2')->nullable();
            $table->integer('visitor_ot3')->nullable();
            $table->integer('visitor_timeouts_remaining')->nullable();
            $table->boolean('visitor_in_bonus')->nullable();
            $table->string('ist_stage', 50)->nullable();
            $table->unsignedInteger('home_team_id')->nullable();
            $table->unsignedInteger('visitor_team_id')->nullable();
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
		Schema::drop('games');
	}
};
