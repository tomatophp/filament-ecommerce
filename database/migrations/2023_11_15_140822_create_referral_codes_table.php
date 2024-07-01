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
        Schema::create('referral_codes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('cascade');

            $table->string('name');
            $table->string('code')->unique();
            $table->double('counter')->default(0)->nullable();

            $table->boolean('is_activated')->default(0)->nullable();
            $table->boolean('is_public')->default(0)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_codes');
    }
};
