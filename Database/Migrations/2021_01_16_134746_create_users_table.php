<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usermodule_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('family');
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('last_session')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('email_verified_at');
            $table->timestamp('mobile_verified_at');
            $table->string('password');
            $table->string('remember_token');

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
        Schema::dropIfExists('usermodule_users');
    }
}
