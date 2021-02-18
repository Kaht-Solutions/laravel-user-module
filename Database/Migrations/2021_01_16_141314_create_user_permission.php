<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usermodule_user_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('userable_id');
            $table->string('userable_type');

            $table->unsignedBigInteger('permission_id');
            $table->foreign('permission_id')
                ->references('id')->on('usermodule_permissions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_module_user_has_permissions');
    }
}
