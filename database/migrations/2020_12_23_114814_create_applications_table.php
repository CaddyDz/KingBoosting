<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('applications', function (Blueprint $table) {
			$table->id();
			$table->string('summoner');
			$table->text('application');
			$table->string('email');
			$table->string('country');
			$table->string('discord_skype');
			$table->string('rank');
			$table->string('languages');
			$table->string('games');
			$table->string('servers');
			$table->string('hours');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::dropIfExists('applications');
	}
}
