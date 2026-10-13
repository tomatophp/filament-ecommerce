<?php

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use TomatoPHP\FilamentEcommerce\Database\Factories\OrderFactory;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource;
use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentEcommerce\Support\OrderPrintUrl;
use TomatoPHP\FilamentEcommerce\Tests\Models\Team;
use TomatoPHP\FilamentEcommerce\Tests\Models\User;
use TomatoPHP\FilamentEcommerce\Tests\Policies\OrderPolicy;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    seedOrderTypes();
});

function usePrintPolicy(): void
{
    Gate::policy(Order::class, OrderPolicy::class);
}

function signedPrintUrl(Order $order, array $parameters = [], int $minutes = 30): string
{
    return URL::temporarySignedRoute('order.print', now()->addMinutes($minutes), ['order' => $order->uuid, ...$parameters]);
}

function useTenantPanel(): void
{
    Schema::create('teams', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->foreignId('user_id');
        $table->timestamps();
    });
    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedBigInteger('team_id')->nullable();
    });

    app(PanelRegistry::class)->register(Panel::make()->id('shop')->path('shop')->tenant(Team::class));
}

it('lets an authorized user print their order', function () {
    usePrintPolicy();
    $user = User::factory()->create();
    $order = OrderFactory::new()->create(['user_id' => $user->id]);

    actingAs($user)->get(OrderPrintUrl::for($order))
        ->assertSuccessful()
        ->assertSee($order->uuid);
});

it('forbids printing another user\'s order', function () {
    usePrintPolicy();
    $owner = User::factory()->create();
    $order = OrderFactory::new()->create(['user_id' => $owner->id]);

    actingAs(User::factory()->create())->get(OrderPrintUrl::for($order))
        ->assertForbidden();
});

it('forbids printing when the host app registers no order policy', function () {
    $user = User::factory()->create();
    $order = OrderFactory::new()->create(['user_id' => $user->id]);

    actingAs($user)->get(OrderPrintUrl::for($order))
        ->assertForbidden();
});

it('rejects unsigned print urls', function () {
    usePrintPolicy();
    $user = User::factory()->create();
    $order = OrderFactory::new()->create(['user_id' => $user->id]);

    actingAs($user)->get("/orders/{$order->uuid}/print")->assertForbidden();
    actingAs($user)->get("/orders/{$order->uuid}/print?panel=admin")->assertForbidden();
});

it('rejects expired print urls', function () {
    usePrintPolicy();
    $user = User::factory()->create();
    $order = OrderFactory::new()->create(['user_id' => $user->id]);

    $url = OrderPrintUrl::for($order);
    $this->travel(31)->minutes();

    actingAs($user)->get($url)->assertForbidden();
});

it('rejects tampered print urls', function () {
    usePrintPolicy();
    $user = User::factory()->create();
    $mine = OrderFactory::new()->create(['user_id' => $user->id]);
    $other = OrderFactory::new()->create(['user_id' => $user->id]);

    $url = str_replace($mine->uuid, $other->uuid, OrderPrintUrl::for($mine));

    actingAs($user)->get($url)->assertForbidden();
});

it('requires authentication to print', function () {
    usePrintPolicy();
    $order = OrderFactory::new()->create();

    $response = $this->get(OrderPrintUrl::for($order));

    expect($response->isSuccessful())->toBeFalse();
});

it('denies printing an order that belongs to another tenant', function () {
    usePrintPolicy();
    useTenantPanel();

    $user = User::factory()->create();
    $myTeam = Team::query()->create(['name' => 'Mine', 'user_id' => $user->id]);
    $otherTeam = Team::query()->create(['name' => 'Other', 'user_id' => User::factory()->create()->id]);

    // The policy alone would allow it: the order is the user's.
    $order = OrderFactory::new()->create(['user_id' => $user->id]);
    $order->forceFill(['team_id' => $otherTeam->id])->save();

    // Signed for the user's own tenant, which does not own the order.
    actingAs($user)->get(signedPrintUrl($order, ['panel' => 'shop', 'tenant' => $myTeam->id]))
        ->assertForbidden();

    // Signed for the order's tenant, which the user cannot access.
    actingAs($user)->get(signedPrintUrl($order, ['panel' => 'shop', 'tenant' => $otherTeam->id]))
        ->assertForbidden();

    // Signed without a tenant.
    actingAs($user)->get(signedPrintUrl($order, ['panel' => 'shop']))
        ->assertForbidden();
    actingAs($user)->get(signedPrintUrl($order, ['panel' => 'admin']))
        ->assertForbidden();
});

it('allows printing an order of the current tenant', function () {
    usePrintPolicy();
    useTenantPanel();

    $user = User::factory()->create();
    $team = Team::query()->create(['name' => 'Mine', 'user_id' => $user->id]);
    $order = OrderFactory::new()->create(['user_id' => $user->id]);
    $order->forceFill(['team_id' => $team->id])->save();

    Filament::setCurrentPanel('shop');
    Filament::setTenant($team, isQuiet: true);
    $url = OrderPrintUrl::for($order);
    Filament::setTenant(null, isQuiet: true);
    Filament::setCurrentPanel('admin');

    expect($url)->toContain('panel=shop')->toContain('tenant=' . $team->id);

    actingAs($user)->get($url)->assertSuccessful();
});

it('links the order print action to a signed url', function () {
    $user = User::factory()->create();
    $order = OrderFactory::new()->create(['user_id' => $user->id]);

    actingAs($user)->get(OrderResource::getUrl('view', ['record' => $order]))
        ->assertSuccessful()
        ->assertSee('/orders/' . $order->uuid . '/print', false)
        ->assertSee('signature=', false)
        ->assertDontSee('/orders/' . $order->id . '/print', false);
});

it('hides print links when the print route is disabled', function () {
    config()->set('filament-ecommerce.print.enabled', false);

    expect(OrderPrintUrl::enabled())->toBeFalse()
        ->and(OrderPrintUrl::for(OrderFactory::new()->create()))->toBeNull();
});
