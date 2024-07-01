<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shipping_vendor_id')->nullable()->constrained('shipping_vendors')->onDelete('cascade');
            $table->foreignId('delivery_id')->nullable()->constrained('deliveries')->onDelete('cascade');

            $table->string('type')->default('delivery')->nullable();

            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('cascade');
            $table->foreignId('area_id')->nullable()->constrained('areas')->onDelete('cascade');

            $table->double('price')->default(0)->nullable();

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
        Schema::dropIfExists('shipping_prices');
    }
};
