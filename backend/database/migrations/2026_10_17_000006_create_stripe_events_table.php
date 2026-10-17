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
		// Stripe can deliver an event more than once; each id is processed only the first time
		Schema::create('stripe_events', function (Blueprint $table) {
			$table->string('id')->primary();
			$table->string('type', 64);
			$table->timestamp('processed_at')->useCurrent();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('stripe_events');
	}
};
