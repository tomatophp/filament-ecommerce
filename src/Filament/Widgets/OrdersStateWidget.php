<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Flowframe\Trend\Trend;
use TomatoPHP\FilamentEcommerce\Filament\State\EcommerceState;
use TomatoPHP\FilamentEcommerce\Filament\Widgets\Traits\HasShield;
use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentTypes\Models\Type;

class OrdersStateWidget extends BaseWidget
{
    use HasShield;

    protected function getStats(): array
    {
        $orderQuery = Order::query();
        $orderStates = Type::query()
            ->where('for', 'orders')
            ->where('type', 'status')
            ->get();

        $states = [];
        foreach ($orderStates as $item) {
            $trend = Trend::query((clone $orderQuery)->where('status', $item->key))
                ->interval('day')
                ->dateColumn('created_at')
                ->between(
                    now()->subMonth(),
                    now()
                )
                ->count();
            $count = (clone $orderQuery)->where('status', $item->key)->count();

            $states[] = EcommerceState::make(trans('filament-ecommerce::messages.widget.orders') . ' ' . $item->name, $count)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($trend->pluck('aggregate')->toArray())
                ->color($item->color)
                ->icon($item->icon);
        }

        return $states;
    }
}
