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
		Schema::create('messages', function (Blueprint $table) {
			$table->id();
			$table->foreignId('order_id')->constrained()->cascadeOnDelete();
			// Null for system messages posted by order actions
			$table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
			$table->text('body');
			$table->timestamp('read_at')->nullable();
			$table->timestamps();

			$table->index(['order_id', 'id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('messages');
	}
};
