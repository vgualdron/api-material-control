<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Third extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name', 255)->collation('utf8_spanish_ci');
            $table->string('code', 50)->collation('utf8_spanish_ci');
            $table->string('nit', 50)->collation('utf8_spanish_ci');            
            $table->string('type', 1)->collation('utf8_spanish_ci');
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
