<?php

namespace TomatoPHP\FilamentEcommerce\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use TomatoPHP\FilamentEcommerce\Filament\State\EcommerceState;
use TomatoPHP\FilamentEcommerce\Filament\Widgets\Traits\HasShield;
use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentTypes\Models\Type;

class OrdersStateWidget extends BaseWidget
{
    use HasShield;
    
    protected static string $view = 'filament-widgets::stats-overview-widget';

    protected function getStats(): array
    {
        $orderQuery = Order::query();
        $orderStates = Type::query()
            ->where('for', 'orders')
            ->where('type','status')
            ->get();

        $states = [];
        foreach ($orderStates as $item){
            $trend = Trend::query((clone $orderQuery)->where('status', $item->key))
                ->interval('day')
                ->dateColumn('created_at')
                ->between(
                    now()->subMonth(),
                    now()
                )
                ->count();
            $states[] = EcommerceState::make(trans('filament-ecommerce::messages.widget.orders') . ' ' . $item->name , (clone $orderQuery)->where('status', $item->key)->count())
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->value((clone $orderQuery)->where('status', $item->key)->count())
                ->chart($trend->pluck('aggregate')->toArray())
                ->color($item->color)
                ->icon($item->icon);
        }
        return $states;
    }
}
