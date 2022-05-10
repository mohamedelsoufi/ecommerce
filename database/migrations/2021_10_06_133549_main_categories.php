<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MainCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_categories', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(1)->comment('0->not avtive, 1->active, -1 -> delete');
            $table->timestamps();
        });

        Schema::create('main_categories_translations', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('main_category_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();

            $table->unique(['main_category_id', 'locale']);
            $table->foreign('main_category_id')->references('id')->on('main_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('main_categories');
        Schema::dropIfExists('main_categories_translations');

    }
}
