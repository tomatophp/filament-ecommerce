<?php

namespace TomatoPHP\FilamentEcommerce\Http\Controllers;

use Filament\Facades\Filament;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use TomatoPHP\FilamentEcommerce\Models\Order;

class PrintOrderController
{
    public function __invoke(Request $request, Order $order)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $panel = rescue(fn () => Filament::getPanel((string) $request->query('panel')), null, false);

        if ($panel?->hasTenancy() || filled($order->getAttribute('team_id'))) {
            abort_unless($panel?->hasTenancy(), 403);

            $tenantKey = (string) $request->query('tenant');
            abort_if($tenantKey === '', 403);

            $tenant = rescue(fn () => $panel->getTenant($tenantKey), null, false);
            abort_unless($tenant, 403);
            abort_unless($user instanceof HasTenants && $user->canAccessTenant($tenant), 403);
            abort_unless($this->belongsToTenant($order, $tenant, $panel), 403);

            Filament::setCurrentPanel($panel);
            Filament::setTenant($tenant, isQuiet: true);
        }

        // Use the same Order policy as the OrderResource; without one, deny.
        abort_if(Gate::getPolicyFor($order) === null, 403);
        Gate::forUser($user)->authorize('view', $order);

        return view('filament-ecommerce::orders.print', ['model' => $order]);
    }

    protected function belongsToTenant(Order $order, $tenant, Panel $panel): bool
    {
        $foreignKey = Str::snake($panel->getTenantOwnershipRelationshipName()) . '_id';
        $ownerId = $order->getAttribute($foreignKey);

        return filled($ownerId) && (string) $ownerId === (string) $tenant->getKey();
    }
}
