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
        Schema::create('product_has_tags', function (Blueprint $table) {
            $table->foreignId("product_id")->references('id')->on('products')->onDelete('cascade')->unsigned();
            $table->foreignId("tag_id")->constrained('categories')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_has_tags');
    }
};
