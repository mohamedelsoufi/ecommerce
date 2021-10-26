<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Orders extends Migration
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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->tinyInteger('status')->default(0)->comment('-1 -> cancel, 0->not active, 1->Preparation and delivery, 2->finshed');
            $table->integer('total')->nullable()->comment('without promo code');
            $table->integer('final_total')->nullable()->comment('with promo code');
            $table->integer('shipping_cost')->default(0);
            $table->unsignedBigInteger('promoCode_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            $table->tinyInteger('payment_status')->nullable()->comment('0->faild, 1->success');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('promoCode_id')->references('id')->on('promo_codes')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
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
