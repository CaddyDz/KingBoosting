<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_service_type_id');
            $table->foreign('service_service_type_id')->references('id')->on('service_service_type');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('users');
            $table->unsignedBigInteger('booster_id')->nullable();
            $table->foreign('booster_id')->references('id')->on('users');
            $table->enum('status', ['pending', 'progress', 'paused', 'completed', 'suspended'])->default('pending');
            $table->decimal('price', 5, 2);
            $table->string('purchase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}
