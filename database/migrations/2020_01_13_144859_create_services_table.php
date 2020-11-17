<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('services', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('kind_id');
			$table->foreign('kind_id')->references('id')->on('service_kinds')->onDelete('cascade');
			$table->string('name');
			$table->string('image');
			$table->text('description');
			$table->text('subtitle');
			$table->enum('status', ['Popular', 'New', 'Best Value'])->nullable();
			$table->string('slug');
			$table->string('bg_img');
			$table->timestamp('deleted_at')->nullable();
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
		Schema::dropIfExists('services');
	}
}
