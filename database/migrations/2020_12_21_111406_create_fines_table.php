<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fines', function (Blueprint $table) {
			$table->id();
			$table->foreignId('booster_id')->constrained('users');
			$table->decimal('amount');
			$table->string('comment');
			$table->string('status')->default('Balance Transfer');
			$table->boolean('paid')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('fines');
	}
}
