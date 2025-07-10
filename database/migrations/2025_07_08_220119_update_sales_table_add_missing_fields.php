<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSalesTableAddMissingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->after('brand');
            $table->integer('quantity_full')->nullable()->after('quantity');
            $table->integer('in_way_to_client')->nullable()->after('quantity_full');
            $table->integer('in_way_from_client')->nullable()->after('in_way_to_client');
            $table->bigInteger('sc_code')->nullable()->after('in_way_from_client');
            $table->double('price', 8, 2)->nullable()->after('sc_code');
            $table->double('discount', 8, 2)->nullable()->after('price');
            $table->string('barcode')->change(); // если раньше был int
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
