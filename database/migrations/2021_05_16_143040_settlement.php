<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Settlement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlement', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('consecutive', 20)->collation('utf8_spanish_ci')->unique();
            $table->string('third', 155)->collation('utf8_spanish_ci');
            $table->date('date');
            $table->decimal('subtotal_amount', 20,2);
            $table->decimal('subtotal_settlement', 20,2);
            $table->decimal('unit_royalties', 10,2)->default(0);
            $table->decimal('royalties', 20,2)->default(0);
            $table->decimal('retentions_percentaje', 10,2)->default(0);
            $table->decimal('retentions', 20,2);
            $table->decimal('total_settle', 20,2);
            $table->string('observation', 200)->collation('utf8_spanish_ci')->nullable();
            $table->string('invoice', 30)->collation('utf8_spanish_ci');
            $table->date('invoice_date');
            $table->string('internal_document', 50)->collation('utf8_spanish_ci');
            $table->date('start_date');
            $table->date('final_date');
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
