<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('day_wise_id')->nullable();
            $table->bigInteger('user_id');
            $table->bigInteger('table_id');
            $table->string('table_name')->nullable();
            $table->double('total')->nullable();
            $table->tinyInteger('status');
            $table->tinyInteger('payment_status');
            $table->timestamps();

            $table->index(['id']);
            $table->index(['user_id']);
            $table->index(['status']);
            $table->index(['payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
