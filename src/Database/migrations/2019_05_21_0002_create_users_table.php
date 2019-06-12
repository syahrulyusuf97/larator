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
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('email');
            $table->string('username');
            $table->string('password');
            $table->date('date_of_birth')->nullable();
            $table->mediumText('address')->nullable();
            $table->char('telp', 15)->nullable();
            $table->enum('gender', ['Male', 'Female'])->default('Male');
            $table->string('religion', 25)->nullable();
            $table->unsignedBigInteger('idlevel')->index();
            $table->enum('status', ['N', 'Y'])->default('N');
            $table->string('image')->nullable();
            $table->dateTime('lastlogin')->nullable();
            $table->dateTime('lastlogout')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('idlevel')->references('id')->on('level')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
