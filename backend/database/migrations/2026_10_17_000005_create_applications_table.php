<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		// Booster applications sent from the storefront's /apply page
		Schema::create('applications', function (Blueprint $table) {
			$table->id();
			$table->string('in_game_name', 64);
			$table->string('email');
			$table->string('country', 64);
			$table->string('discord_skype', 64);
			$table->string('rank', 64);
			$table->string('languages');
			$table->string('games');
			$table->string('servers');
			$table->string('hours', 64);
			$table->text('motivation');
			$table->timestamp('reviewed_at')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('applications');
	}
};
