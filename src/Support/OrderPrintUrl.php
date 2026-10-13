<?php

namespace TomatoPHP\FilamentEcommerce\Support;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\URL;
use TomatoPHP\FilamentEcommerce\Models\Order;

class OrderPrintUrl
{
    public static function enabled(): bool
    {
        return (bool) config('filament-ecommerce.print.enabled', true);
    }

    /**
     * A temporary signed link to the order receipt, scoped to the current panel and tenant.
     */
    public static function for(Order $order): ?string
    {
        if (! static::enabled()) {
            return null;
        }

        $parameters = ['order' => $order->uuid];

        $panel = Filament::getCurrentPanel() ?? rescue(fn () => Filament::getDefaultPanel(), null, false);
        if ($panel) {
            $parameters['panel'] = $panel->getId();
        }

        if ($tenant = Filament::getTenant()) {
            $slugAttribute = $panel?->getTenantSlugAttribute();
            $parameters['tenant'] = $slugAttribute ? $tenant->getAttribute($slugAttribute) : $tenant->getRouteKey();
        }

        return URL::temporarySignedRoute(
            'order.print',
            now()->addMinutes((int) config('filament-ecommerce.print.expires', 30)),
            $parameters,
        );
    }
}
