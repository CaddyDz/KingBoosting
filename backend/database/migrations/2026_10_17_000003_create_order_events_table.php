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
		Schema::create('order_events', function (Blueprint $table) {
			$table->id();
			$table->foreignId('order_id')->constrained()->cascadeOnDelete();
			// Null when the system acted, e.g. the Stripe webhook
			$table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
			$table->string('type', 32);
			$table->string('from_status', 32)->nullable();
			$table->string('to_status', 32)->nullable();
			$table->json('meta')->nullable();
			$table->timestamp('created_at')->useCurrent();

			$table->index(['order_id', 'id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('order_events');
	}
};
