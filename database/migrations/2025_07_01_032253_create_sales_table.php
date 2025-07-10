<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->timestamp('last_change_date');
            $table->string('supplier_article');
            $table->string('tech_size');
            $table->string('barcode');
            $table->integer('discount_percent');
            $table->boolean('is_supply');
            $table->boolean('is_realization');
            $table->double('total_price', 8, 2);
            $table->double('promo_code_discount')->nullable();
            $table->string('warehouse_name');
            $table->string('country_name');
            $table->string('oblast_okrug_name')->nullable();
            $table->string('region_name');
            $table->unsignedBigInteger('income_id');
            $table->string('sale_id');
            $table->unsignedBigInteger('odid')->nullable();
            $table->double('spp', 8, 2);
            $table->double('for_pay', 8, 2);
            $table->double('finished_price', 8, 2);
            $table->double('price_with_disc', 8, 2);
            $table->bigInteger('nm_id');
            $table->string('subject');
            $table->string('category');
            $table->string('brand');
            $table->boolean('is_storno')->nullable();
            $table->string('g_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

