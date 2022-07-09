<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Rate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('movement', 2)->collation('utf8_spanish_ci');
            $table->unsignedBigInteger('origin_yard')->nullable();
            $table->unsignedBigInteger('destiny_yard')->nullable();
            $table->unsignedBigInteger('supplier')->nullable();
            $table->string('supplier_name', 155)->collation('utf8_spanish_ci')->nullable();
            $table->unsignedBigInteger('customer')->nullable();
            $table->string('customer_name', 155)->collation('utf8_spanish_ci')->nullable();
            $table->unsignedBigInteger('conveyor_company')->nullable();
            $table->string('conveyor_company_name', 155)->collation('utf8_spanish_ci')->nullable();
            $table->date('start_date');
            $table->date('final_date');
            $table->unsignedBigInteger('material');
            $table->decimal('material_price', 20,2)->nullable();
            $table->decimal('freight_price', 20,2);
            $table->decimal('net_price', 20,2)->nullable();
            $table->string('observation', 200)->collation('utf8_spanish_ci')->nullable();
            $table->boolean('round_trip')->default(0);
            $table->timestamps();
            /* foreigns */
            $table->foreign('origin_yard')->references('id')->on('yard');
            $table->foreign('destiny_yard')->references('id')->on('yard');
            $table->foreign('material')->references('id')->on('material');
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
