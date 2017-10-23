<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->text('description')->nullable();
            $table->decimal('gross_value', 12);
            $table->decimal('net_value', 12);
            $table->decimal('discount', 12);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('merchandise_purchase', function (Blueprint $table) {
            $table->integer('merchandise_id');
            $table->integer('purchase_id');
            $table->decimal('purchase_price');
            $table->decimal('quantity');

            $table->foreign('merchandise_id')->references('id')->on('merchandises');
            $table->foreign('purchase_id')->references('id')->on('purchases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchandise_purchase');
        Schema::dropIfExists('purchases');
    }
}
