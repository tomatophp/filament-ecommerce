<?php

namespace TomatoPHP\FilamentEcommerce\Filament\State;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\View\View;

class EcommerceState extends Stat
{
    public function render(): View
    {
        return view('filament-ecommerce::widgets.state', $this->data());
    }
}
