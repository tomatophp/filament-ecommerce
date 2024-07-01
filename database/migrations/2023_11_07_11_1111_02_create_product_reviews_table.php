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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->references('id')->on('products')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');

            $table->double("rate")->default(0);
            $table->text("review")->nullable();
            $table->boolean("is_activated")->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
