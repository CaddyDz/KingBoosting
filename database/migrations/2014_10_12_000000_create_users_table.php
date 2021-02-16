<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\{Migrations\Migration, Schema\Blueprint};

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->string('username')->unique();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('social')->nullable();
			$table->string('email')->unique();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password')->nullable();
			$table->string('country');
			$table->boolean('coach')->default(false);
			$table->decimal('coaching_price')->nullable();
			$table->boolean('visible')->default(true);
			$table->rememberToken();
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
		Schema::dropIfExists('users');
	}
}
