<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('describe')->nullable();
            $table->integer('price')->nullable();
            $table->unsignedBigInteger('sub_categoriesId')->nullable();
            $table->unsignedBigInteger('vender_id')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0->not avtive, 1->active, -1 -> delete');
            $table->integer('number_of_sell')->default(0)->nullable();
            $table->integer('discound')->default(0);
            $table->integer('quantity')->nullable();
            $table->tinyInteger('gender')->nullable();

            $table->text('colors')->nullable();
            $table->text('sizes')->nullable();

            $table->timestamps();

            $table->foreign('sub_categoriesId')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->foreign('vender_id')->references('id')->on('venders')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
