<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Yard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yard', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('code', 30)->collation('utf8_spanish_ci');
            $table->string('name', 100)->collation('utf8_spanish_ci');
            $table->unsignedBigInteger('zone')->nullable();
            $table->decimal('longitude', 6,6)->nullable();
            $table->decimal('latitude', 6,6)->nullable();
            $table->foreign('zone')->references('id')->on('zone');
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
