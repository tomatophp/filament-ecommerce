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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('cascade');


            $table->foreignId('product_id')->nullable()->constrained('products');

            $table->string('session_id')->nullable();
            $table->string('item')->index();
            $table->double('price')->default(0);
            $table->double('discount')->default(0)->nullable();
            $table->double('vat')->default(0)->nullable();
            $table->double('qty')->default(0)->nullable();
            $table->double('total')->default(0)->nullable();
            $table->text('note')->nullable();
            $table->json('options')->nullable();

            $table->boolean('is_active')->default(1)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
