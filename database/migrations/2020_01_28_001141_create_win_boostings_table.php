<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWinBoostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('win_boostings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('service_types')->onDelete('cascade');
            $table->unsignedBigInteger('tier_id');
            $table->foreign('tier_id')->references('id')->on('tiers')->onDelete('cascade');
            $table->unsignedTinyInteger('wins');
            // Approximate completion time
            $table->string('eta');
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
        Schema::dropIfExists('win_boostings');
    }
}
