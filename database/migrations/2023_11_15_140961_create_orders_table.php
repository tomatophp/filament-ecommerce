<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('uuid')->unique();

            //Order Type
            $table->string('type')->default('system')->nullable();

            //User Log
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            //Location
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('cascade');
            $table->foreignId('area_id')->nullable()->constrained('areas')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained('locations')->onDelete('cascade');

            //Customer
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');

            //Order
            $table->foreignId('cashier_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('cascade');
            $table->foreignId('shipper_id')->nullable()->constrained('deliveries')->onDelete('cascade');
            $table->foreignId('shipping_vendor_id')->nullable()->constrained('shipping_vendors')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');

            //Customer Info
            $table->string('name')->nullable();
            $table->string('phone')->nullable();

            //Location
            $table->string('flat')->nullable();
            $table->text('address')->nullable();

            //Sales From
            $table->string('source')->default('system');

            //Shipping
            $table->string('shipper_vendor')->nullable();

            //Prices
            $table->double('total')->default(0)->nullable();
            $table->double('discount')->default(0)->nullable();
            $table->double('shipping')->default(0)->nullable();
            $table->double('vat')->default(0)->nullable();

            //Status
            $table->string('status')->default('pending')->nullable();

            //Options
            $table->boolean('is_approved')->default(0)->nullable();
            $table->boolean('is_closed')->default(0)->nullable();
            $table->boolean('is_on_table')->default(0)->nullable();
            $table->string('table')->nullable();


            //Notes
            $table->text('notes')->nullable();

            //Return
            $table->boolean('has_returns')->default(0)->nullable();
            $table->double('return_total')->default(0)->nullable();
            $table->string('reason')->nullable();

            //Payments
            $table->boolean('is_payed')->default(0)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_vendor')->nullable();
            $table->string('payment_vendor_id')->nullable();

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
        Schema::dropIfExists('orders');
    }
};
