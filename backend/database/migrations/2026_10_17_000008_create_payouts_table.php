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
		// One payment to a booster: their completed orders' shares, minus the fines it settles
		Schema::create('payouts', function (Blueprint $table) {
			$table->id();
			$table->foreignId('booster_id')->constrained('users');
			$table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
			$table->unsignedInteger('gross_cents');
			$table->unsignedInteger('fines_cents')->default(0);
			$table->unsignedInteger('amount_cents');
			$table->unsignedSmallInteger('orders_count');
			$table->timestamp('paid_at');
			$table->timestamps();
		});

		Schema::table('orders', function (Blueprint $table) {
			$table->foreignId('payout_id')->nullable()->after('paid_out_at')->constrained()->nullOnDelete();
		});

		Schema::table('fines', function (Blueprint $table) {
			$table->foreignId('payout_id')->nullable()->after('reason')->constrained()->nullOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('fines', fn (Blueprint $table) => $table->dropConstrainedForeignId('payout_id'));
		Schema::table('orders', fn (Blueprint $table) => $table->dropConstrainedForeignId('payout_id'));
		Schema::dropIfExists('payouts');
	}
};
