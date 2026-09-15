<?php

namespace TomatoPHP\FilamentEcommerce\Filament\State;

use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EcommerceState extends Stat
{
    /**
     * Order statuses store hex colors (e.g. "#d91919"); Filament v5 needs a palette for those.
     *
     * @param  string | array<string> | \Closure | null  $color
     */
    public function color(string | array | \Closure | null $color): static
    {
        if (is_string($color) && str_starts_with($color, '#')) {
            $color = Color::hex($color);
        }

        return parent::color($color);
    }
}
