<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullName')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable()->unique();
            $table->tinyInteger('email_verified_at')->nullable()->comment('null -> not active, 1-> active');
            $table->tinyInteger('status')->nullable()->comment('0-> blocked');
            $table->tinyInteger('gender')->nullable()->comment('0-> male, 1-> famale');
            $table->date('birth')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->string('socialite_id')->nullable();
            $table->rememberToken();
            $table->timestamps();

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
        Schema::dropIfExists('users');
    }
}
