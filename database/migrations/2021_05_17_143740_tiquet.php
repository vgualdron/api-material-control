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
            $table->string('type', 2)->collation('utf8_spanish_ci');
            $table->string('operation', 1)->collation('utf8_spanish_ci')->nullable();
            $table->unsignedBigInteger('user');
            $table->unsignedBigInteger('origin_yard')->nullable();
            $table->unsignedBigInteger('destiny_yard')->nullable();
            $table->unsignedBigInteger('supplier')->nullable();
            $table->string('supplier_name', 155)->collation('utf8_spanish_ci')->nullable();
            $table->unsignedBigInteger('customer')->nullable();
            $table->string('customer_name', 155)->collation('utf8_spanish_ci')->nullable();
            $table->unsignedBigInteger('material');
            $table->decimal('ash_percentage', 11,2);
            $table->string('receipt_number', 50)->collation('utf8_spanish_ci')->nullable();
            $table->string('referral_number', 50)->collation('utf8_spanish_ci')->nullable();
            $table->date('date');
            $table->time('time');
            $table->string('license_plate', 50)->collation('utf8_spanish_ci');
            $table->string('trailer_number', 50)->collation('utf8_spanish_ci')->nullable();
            $table->string('driver_document', 100)->collation('utf8_spanish_ci');
            $table->string('driver_name', 100)->collation('utf8_spanish_ci');
            $table->decimal('gross_weight', 20,2);
            $table->decimal('tare_weight', 20,2);
            $table->decimal('net_weight', 20,2);
            $table->unsignedBigInteger('conveyor_company');
            $table->string('conveyor_company_name', 155);
            $table->string('observation', 200)->collation('utf8_spanish_ci')->nullable();
            $table->string('seals', 50)->collation('utf8_spanish_ci')->nullable();
            $table->boolean('round_trip')->default(0);
            $table->date('local_created_at')->nullable();
            $table->unsignedBigInteger('freight_settlement')->nullable();
            $table->unsignedBigInteger('material_settlement')->nullable();
            $table->decimal('freight_settlement_retention_percentage', 4,2)->nullable();
            $table->decimal('material_settlement_retention_percentage', 4,2)->nullable();
            $table->decimal('material_settlement_royalties', 20,2)->nullable();
            $table->decimal('freight_settlement_unit_value', 20,2)->nullable();
            $table->decimal('material_settlement_unit_value', 20,2)->nullable();
            $table->decimal('freight_settlement_net_value', 20,2)->nullable();
            $table->decimal('material_settlement_net_value', 20,2)->nullable();
            $table->boolean('material_settle_receipt_weight')->default(0);
            $table->boolean('material_settle_receipt_weight')->default(0);
            $table->boolean('freight_settle_receipt_weight')->default(0);
            $table->decimal('material_weight_settled', 20,2)->default(0);
            $table->decimal('freight_weight_settled', 20,2)->default(0);
            $table->unsignedBigInteger('ticketmovid')->nullable();
            $table->date('ticketmov_date')->nullable();
            /* foreigns */
            $table->foreign('origin_yard')->references('id')->on('yard');
            $table->foreign('destiny_yard')->references('id')->on('yard');            
            $table->foreign('material')->references('id')->on('material');
            $table->foreign('freight_settlement')->references('id')->on('settlement');
            $table->foreign('material_settlement')->references('id')->on('settlement');
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
