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
		Schema::create('orders', function (Blueprint $table) {
			$table->id();
			$table->foreignId('customer_id')->constrained('users');
			// Set at checkout when the customer picks a booster, or when a booster claims the order
			$table->foreignId('booster_id')->nullable()->constrained('users')->nullOnDelete();
			$table->enum('status', ['awaiting_payment', 'pending', 'in_progress', 'paused', 'completed', 'paid_out'])
				->default('awaiting_payment')
				->index();
			$table->string('game', 16);
			$table->string('service', 64);
			// Human summary of the purchase, e.g. "Gold II → Platinum IV"
			$table->string('title');
			$table->string('server', 32);
			// The in-game name the booster plays as
			$table->string('account_name', 64);
			// Riot account credentials, encrypted with APP_KEY (the `encrypted` cast)
			$table->text('account_login')->nullable();
			$table->text('account_password')->nullable();
			// Snapshot of the server-side quote: inputs, breakdown, options and total
			$table->json('quote');
			$table->unsignedInteger('price_cents');
			$table->unsignedTinyInteger('booster_share_pct')->default(50);
			$table->text('comment')->nullable();
			$table->string('payment_intent_id')->nullable()->unique();
			$table->timestamp('paid_at')->nullable();
			$table->timestamp('claimed_at')->nullable();
			$table->timestamp('completed_at')->nullable();
			$table->timestamp('paid_out_at')->nullable();
			$table->timestamps();

			$table->index(['customer_id', 'status']);
			$table->index(['booster_id', 'status']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('orders');
	}
};
