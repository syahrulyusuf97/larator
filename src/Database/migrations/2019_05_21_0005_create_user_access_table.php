<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_access', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('iduser')->index();
            $table->unsignedBigInteger('access')->index();
            $table->enum('read', ['N', 'Y'])->default('N');
            $table->enum('insert', ['N', 'Y'])->default('N');
            $table->enum('update', ['N', 'Y'])->default('N');
            $table->enum('delete', ['N', 'Y'])->default('N');
            $table->timestamps();
            $table->foreign('iduser')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('access')->references('id')->on('access')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_access');
    }
}
