<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_cate_id')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0->not avtive, 1->active, -1 -> delete');
            $table->timestamps();

            $table->foreign('main_cate_id')->references('id')->on('main_categories')->onDelete('cascade');
        });

        Schema::create('sub_categories_translations', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('sub_category_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();

            $table->unique(['sub_category_id', 'locale']);
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_categories');
        Schema::dropIfExists('sub_categories_translations');

    }
}
