<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->references('id')->on('products')->onDelete('cascade');
            $table->string("key");
            $table->json("value")->nullable();
            $table->bigInteger("model_id")->nullable();
            $table->string("model_type")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_metas');
    }
};
