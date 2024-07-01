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
        Schema::create('orders_items', function (Blueprint $table) {
            $table->id();

            //Order
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            //Warehouse
            $table->unsignedBigInteger('refund_id')->nullable();
            $table->unsignedBigInteger('warehouse_move_id')->nullable();

            //Customer
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');

            //Product
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');

            //Item Info
            $table->string('item')->nullable();
            $table->double('price')->default(0)->nullable();
            $table->double('discount')->default(0)->nullable();
            $table->double('vat')->default(0)->nullable();
            $table->double('total')->default(0)->nullable();
            $table->double('returned')->default(0)->nullable();

            //Qty
            $table->double('qty')->default(1)->nullable();
            $table->double('returned_qty')->default(0)->nullable();

            //Options
            $table->boolean('is_free')->default(0)->nullable();
            $table->boolean('is_returned')->default(0)->nullable();

            //Options
            $table->json('options')->nullable();

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
        Schema::dropIfExists('orders_items');
    }
};
