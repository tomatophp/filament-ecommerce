<?php

namespace TomatoPHP\FilamentEcommerce\Tests\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\UserFactory;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory;
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getTenants(Panel $panel): Collection
    {
        return Team::query()->where('user_id', $this->id)->get();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return (int) $tenant->getAttribute('user_id') === (int) $this->id;
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
