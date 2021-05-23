<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tiquet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiquet', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('type', 1)->collation('utf8_spanish_ci')->nullable(); 
            $table->unsignedBigInteger('origin_user')->nullable();
            $table->unsignedBigInteger('destiny_user')->nullable();
            $table->unsignedBigInteger('origin_yard')->nullable();
            $table->unsignedBigInteger('destiny_yard')->nullable();            
            $table->unsignedBigInteger('supplier')->nullable();
            $table->unsignedBigInteger('client')->nullable();
            $table->unsignedBigInteger('material')->nullable();
            $table->string('receipt_number', 50)->collation('utf8_spanish_ci')->nullable();
            $table->string('reference_number', 50)->collation('utf8_spanish_ci')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('final_date')->nullable(); 
            $table->string('license_plate', 50)->collation('utf8_spanish_ci')->nullable();
            $table->string('trailer_number', 50)->collation('utf8_spanish_ci')->nullable();
            $table->string('driver', 100)->collation('utf8_spanish_ci')->nullable();
            $table->decimal('origin_gross_weight')->nullable();
            $table->decimal('origin_tare_weight')->nullable();
            $table->decimal('origin_net_weight')->nullable();
            $table->decimal('destiny_gross_weight')->nullable();
            $table->decimal('destiny_tare_weight')->nullable();
            $table->decimal('destiny_net_weight')->nullable();  
            $table->unsignedBigInteger('transportation_company')->nullable();  
            $table->string('origin_observation', 200)->collation('utf8_spanish_ci')->nullable();
            $table->string('destiny_observation', 200)->collation('utf8_spanish_ci')->nullable();
            $table->string('origin_seal', 50)->collation('utf8_spanish_ci')->nullable();
            $table->string('destiny_seal', 50)->collation('utf8_spanish_ci')->nullable(); 
            $table->boolean('round_trip', 50)->collation('utf8_spanish_ci')->nullable();
            /* foreigns */
            $table->foreign('origin_yard')->references('id')->on('yard');
            $table->foreign('destiny_yard')->references('id')->on('yard');
            $table->foreign('supplier')->references('id')->on('third');
            $table->foreign('client')->references('id')->on('third');
            $table->foreign('material')->references('id')->on('material');
            $table->foreign('transportation_company')->references('id')->on('third');
            $table->foreign('origin_user')->references('id')->on('user');
            $table->foreign('destiny_user')->references('id')->on('user');
            /* unique index */
            $table->index(['receipt_number', 'license_plate']);
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
