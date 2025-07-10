<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('sales');

        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->string('g_number');
            $table->date('date')->nullable(); // на всякий случай nullable
            $table->date('last_change_date')->nullable();

            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->bigInteger('barcode')->nullable();

            $table->decimal('total_price', 12, 2)->nullable();
            $table->unsignedTinyInteger('discount_percent')->nullable();

            $table->boolean('is_supply')->nullable(); // может быть null
            $table->boolean('is_realization')->nullable();

            $table->decimal('promo_code_discount', 12, 2)->nullable();

            $table->string('warehouse_name')->nullable();
            $table->string('country_name')->nullable();
            $table->string('oblast_okrug_name')->nullable();
            $table->string('region_name')->nullable();

            $table->unsignedBigInteger('income_id')->nullable();
            $table->string('sale_id')->nullable();
            $table->unsignedBigInteger('odid')->nullable();
            $table->unsignedTinyInteger('spp')->nullable();

            $table->decimal('for_pay', 12, 2)->nullable();
            $table->decimal('finished_price', 12, 2)->nullable();
            $table->decimal('price_with_disc', 12, 2)->nullable();

            $table->bigInteger('nm_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();

            $table->boolean('is_storno')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
