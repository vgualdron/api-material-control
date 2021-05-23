<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Material extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('code', 30)->collation('utf8_spanish_ci');
            $table->string('name', 200)->collation('utf8_spanish_ci');
            $table->string('unit', 50)->collation('utf8_spanish_ci');    
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
