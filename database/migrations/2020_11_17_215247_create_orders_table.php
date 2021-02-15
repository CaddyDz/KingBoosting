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
	public function up(): void
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->id();
			$table->string('purchase');
			$table->string('service');
			$table->string('server');
			$table->string('summoner');
			$table->string('riot_login')->nullable();
			$table->string('riot_password')->nullable();
			$table->foreignId('booster_id')->nullable()->constrained('users');
			$table->foreignId('client_id')->constrained('users');
			$table->enum('status', [
				'pending',
				'progress',
				'paused',
				'completed',
				'suspended',
				'paid',
			])->default('pending');
			$table->json('options')->nullable();
			$table->decimal('price', 5, 2);
			$table->decimal('share', 4, 2)->default(50.00);
			$table->text('comment')->nullable();
			$table->string('champion')->nullable();
			$table->string('transaction_id')->nullable();
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
		Schema::dropIfExists('orders');
	}
}
