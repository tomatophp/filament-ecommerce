<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentTypes\Models\Type;

class OrderStateChart extends ChartWidget
{
    protected static ?string $heading = 'Orders This Week Per Status';

    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $orderQuery = Order::query();
        $orderStates = Type::query()
            ->where('for', 'orders')
            ->where('type','status')
            ->get();
        $trend = Trend::query((clone $orderQuery))
            ->interval('day')
            ->dateColumn('created_at')
            ->between(
                now()->subWeek(),
                now()
            )
            ->count();

        $datasets = [];
        foreach ($orderStates as $item) {
            $datasets[] = [
                'label' => $item->name,
                'data' => Trend::query((clone $orderQuery)->where('status', $item->key))
                    ->interval('day')
                    ->dateColumn('created_at')
                    ->between(
                        now()->subWeek(),
                        now()
                    )
                    ->count()
                    ->pluck('aggregate')
                    ->toArray(),
                'borderColor' => $item->color,
                'backgroundColor' => $item->color,
                'hoverBackgroundColor'  => $item->color,
                'hoverBorderColor' => $item->color,
            ]
            ;
        }
        return [
            'datasets' => $datasets,
            'labels' => $trend->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
