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
        if (Schema::hasTable('teams')) {
            $tables = [
                'products',
                'coupons',
                'companies',
                'gift_cards',
                'referral_codes',
                'shipping_vendors',
                'orders'
            ];

            foreach ($tables as $table) {
                Schema::table($table, function (Blueprint $table) {
                    $table->unsignedBigInteger('team_id')->nullable()->after('id');
                    $table->foreign('team_id')->references('id')->on('teams')->nullOnDelete();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'products',
            'coupons',
            'companies',
            'gift_cards',
            'referral_codes',
            'shipping_vendors',
            'deliveries',
            'orders'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            });
        }
    }
};
