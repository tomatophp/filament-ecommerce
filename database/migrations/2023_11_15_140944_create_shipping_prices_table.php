<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

            // filament-locations v5 only creates these tables with its database driver.
            foreach (['country_id' => 'countries', 'city_id' => 'cities', 'area_id' => 'areas'] as $column => $foreignTable) {
                $table->foreignId($column)->nullable();
                if (Schema::hasTable($foreignTable)) {
                    $table->foreign($column)->references('id')->on($foreignTable)->onDelete('cascade');
                }
            }

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
