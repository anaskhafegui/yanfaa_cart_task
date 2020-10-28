<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_variation_id')->unsigned()->index();
            $table->integer('related_offer_product_id')->unsigned()->index();
            $table->integer('amount');

            $table->integer('precentges');
            $table->timestamps();

            $table->foreign('product_variation_id')
            ->references('id')
            ->on('product_variations')
            ->onDelete('cascade');

            $table->foreign('related_offer_product_id')
            ->references('id')
            ->on('product_variations')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
