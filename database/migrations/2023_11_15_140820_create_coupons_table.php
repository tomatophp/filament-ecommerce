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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique()->index();
            $table->string('type')->default('discount_coupon')->nullable();
            $table->double('amount')->default(0);

            //Limited
            $table->boolean('is_limited')->default(0)->nullable();
            $table->date('end_at')->nullable();
            $table->integer('use_limit')->default(0)->nullable();
            $table->integer('use_limit_by_user')->default(0)->nullable();
            $table->integer('order_total_limit')->default(0)->nullable();

            //Is Active
            $table->boolean('is_activated')->default(1)->nullable();

            //Link To Marketer
            $table->boolean('is_marketing')->default(0)->nullable();
            $table->string('marketer_name')->nullable();
            $table->string('marketer_type')->nullable();
            $table->double('marketer_amount')->nullable();
            $table->double('marketer_amount_max')->nullable();
            $table->boolean('marketer_show_amount_max')->default(0)->nullable();
            $table->boolean('marketer_hide_total_sales')->default(0)->nullable();

            //Is Used
            $table->double('is_used')->default(0)->nullable();

            //Select
            $table->json('apply_to')->nullable();
            $table->json('except')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
