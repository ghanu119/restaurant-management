<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraChargeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_charges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('slug');
            $table->text('description')->nullable();
            $table->float('price')->nullable()->default(null);
            $table->enum('status', ['n', 'y']);
            $table->timestamps();

            $table->index(['id']);
            $table->index(['slug']);
            $table->index(['status']);
            $table->index(['price']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extra_charges');
    }
}
