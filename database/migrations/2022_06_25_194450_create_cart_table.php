<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('guest_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('parcel_type_id')->nullable();
            $table->string('flavour_id')->nullable();
            $table->bigInteger('pack_of_toppings_id')->nullable();
            $table->boolean('is_custom')->nullable();
            $table->string('toppings_id')->nullable();
            $table->bigInteger('quantity');

            $table->index(['id']);
            $table->index(['user_id']);
            $table->index(['parcel_type_id']);
            $table->index(['pack_of_toppings_id']);
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
        Schema::dropIfExists('carts');
    }
}
