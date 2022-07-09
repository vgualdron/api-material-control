<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Adjustment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustment', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('type', 1)->collation('utf8_spanish_ci');
            $table->unsignedBigInteger('yard')->unsigned();
            $table->unsignedBigInteger('material')->unsigned();
            $table->decimal('amount', 20,2);
            $table->string('observation', 200)->collation('utf8_spanish_ci')->nullable();
            $table->date('date');
            //foreigns
            $table->foreign('yard')->references('id')->on('yard');
            $table->foreign('material')->references('id')->on('material');
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
