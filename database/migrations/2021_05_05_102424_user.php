<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class User extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name',255)->collation('utf8_spanish_ci');
            $table->string('document_number',50)->collation('utf8_spanish_ci');
            $table->string('phone',50)->collation('utf8_spanish_ci');            
            $table->string('password',255)->collation('utf8_spanish_ci');
            $table->unsignedBigInteger('yard')->nullable();
            $table->foreign('yard')->references('id')->on('yard');
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
        //
    }
}
