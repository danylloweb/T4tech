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
		Schema::create('teams', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('conference', 50)->index();
            $table->string('division', 50)->index();
            $table->string('city', 100)->index();
            $table->string('name', 100)->index();
            $table->string('full_name', 150);
            $table->string('abbreviation', 10)->index();
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
		Schema::drop('teams');
	}
};
