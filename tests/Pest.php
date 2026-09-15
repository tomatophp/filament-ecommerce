<?php

use TomatoPHP\FilamentEcommerce\Tests\TestCase;
use TomatoPHP\FilamentTypes\Models\Type;

uses(TestCase::class)->in(__DIR__);

/**
 * Seed the order status / payment method / source types the order screens read.
 */
function seedOrderTypes(): void
{
    $types = [
        ['type' => 'status', 'key' => 'pending', 'name' => 'Pending', 'color' => '#f59e0b', 'icon' => 'heroicon-o-clock'],
        ['type' => 'status', 'key' => 'prepared', 'name' => 'Prepared', 'color' => '#3b82f6', 'icon' => 'heroicon-o-archive-box'],
        ['type' => 'status', 'key' => 'shipped', 'name' => 'Shipped', 'color' => '#10b981', 'icon' => 'heroicon-o-truck'],
        ['type' => 'payment_methods', 'key' => 'cash', 'name' => 'Cash', 'color' => '#22c55e', 'icon' => 'heroicon-o-banknotes'],
        ['type' => 'source', 'key' => 'system', 'name' => 'System', 'color' => '#6366f1', 'icon' => 'heroicon-o-computer-desktop'],
    ];

    foreach ($types as $type) {
        Type::query()->firstOrCreate(
            ['for' => 'orders', 'type' => $type['type'], 'key' => $type['key']],
            ['name' => ['en' => $type['name']], 'color' => $type['color'], 'icon' => $type['icon']],
        );
    }
}
