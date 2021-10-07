<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('addressable_id')->nullable();
            $table->string('addressable_type')->nullable();
            $table->string('name')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('details')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longgitude')->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
