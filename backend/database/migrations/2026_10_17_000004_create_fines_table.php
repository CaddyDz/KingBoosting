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
		Schema::create('fines', function (Blueprint $table) {
			$table->id();
			$table->foreignId('booster_id')->constrained('users')->cascadeOnDelete();
			$table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
			$table->unsignedInteger('amount_cents');
			$table->string('reason');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('fines');
	}
};
