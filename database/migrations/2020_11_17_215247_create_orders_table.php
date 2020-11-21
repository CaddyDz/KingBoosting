<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->id();
			$table->string('service');
			$table->string('tier');
			$table->string('division');
			$table->string('server');
			$table->integer('wins');
			$table->foreignId('booster_id')->nullable()->constrained('users');
			$table->foreignId('client_id')->constrained('users');
			$table->enum('status', ['pending', 'progress', 'paused', 'completed', 'suspended'])->default('pending');
			$table->enum('queue', ['solo_duo', 'flex_5v5'])->default('solo_duo');
			$table->boolean('specific_champions')->default(false);
			$table->boolean('priority')->default(false);
			$table->boolean('streaming')->default(false);
			$table->decimal('price', 5, 2);
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
		Schema::dropIfExists('orders');
	}
}
