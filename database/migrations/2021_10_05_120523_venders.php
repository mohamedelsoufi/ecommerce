<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Venders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venders', function (Blueprint $table) {
            $table->id();
            $table->string('fullName')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable()->unique();
            $table->tinyInteger('email_verified_at')->nullable()->comment('null -> not active, 1-> active');
            $table->tinyInteger('status')->default(1)->comment('0-> blocked');
            $table->tinyInteger('gender')->nullable()->comment('0-> male, 1-> famale');
            $table->date('birth')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('venders');
    }
}
