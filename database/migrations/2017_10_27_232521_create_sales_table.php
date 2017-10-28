<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->text('description')->nullable();
            $table->decimal('gross_value', 12);
            $table->decimal('net_value', 12);
            $table->decimal('discount', 12);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('merchandise_sale', function (Blueprint $table) {
            $table->integer('merchandise_id');
            $table->integer('sale_id');
            $table->decimal('retail_price');
            $table->decimal('quantity');

            $table->foreign('merchandise_id')->references('id')->on('merchandises');
            $table->foreign('sale_id')->references('id')->on('sales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchandise_sale');
        Schema::dropIfExists('sales');
    }
}
