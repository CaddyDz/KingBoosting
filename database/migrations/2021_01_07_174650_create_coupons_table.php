<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupons', function (Blueprint $table) {
			$table->id();
			$table->string('code');
			$table->decimal('amount');
			$table->enum('type', [
				'Smurf',
				'Eloboost',
				'Eloboost & Smurf',
				'Agent',
				'Affilate',
			])->default('Eloboost');
			$table->boolean('used')->default(false);
			$table->boolean('public')->default(false);
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
		Schema::dropIfExists('coupons');
	}
}
