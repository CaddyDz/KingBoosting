<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivisionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('divisions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('tier_id');
			$table->foreign('tier_id')->references('id')->on('tiers')->onDelete('cascade');
			$table->string('image');
			$table->decimal('price')->default(1.90);
			$table->string('name');
			$table->timestamp('deleted_at');
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
		Schema::dropIfExists('divisions');
	}
}
