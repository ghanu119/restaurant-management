<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product_extra_charges', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_item_id');
            $table->bigInteger('extra_charge_id');
            $table->string('name');
            $table->double('price');
            $table->integer('quantity');
            $table->double('sub_total')->nullable();
            $table->timestamps();

            $table->index(['id']);
            $table->index(['order_item_id']);
            $table->index(['extra_charge_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product_extra_charges');
    }
}
