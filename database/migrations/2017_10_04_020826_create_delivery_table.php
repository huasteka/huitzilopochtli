<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_id')->unsigned();
            $table->integer('delivery_address_id')->unsigned();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->integer('delivery_time')->nullable();

            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->foreign('delivery_address_id')->references('id')->on('delivery_addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
