<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentTypes\Models\Type;

class OrderPaymentMethodChart extends ChartWidget
{
    protected static ?string $heading = 'Compare Order Payment Methods';

    protected function getData(): array
    {
        $query = Order::query()->groupBy('payment_method')->selectRaw('count(*) as count, payment_method');
        $paymentMethods = Type::query()->where('for', 'orders')
            ->where('type', 'payment_methods')
            ->get();

        return [
            'labels' => $paymentMethods->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Source',
                    'data' =>  $query->get()->whereIn('payment_method', $paymentMethods->pluck('key')->toArray())->pluck('count')->toArray(),
                    'backgroundColor' => $paymentMethods->pluck('color')->toArray(),
                    'hoverOffset'=> 4
                ]
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
