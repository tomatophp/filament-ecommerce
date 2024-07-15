<?php

namespace TomatoPHP\FilamentEcommerce\Settings;

use Spatie\LaravelSettings\Settings;


class OrderingSettings extends Settings
{
    public int $ordering_company_id;
    public int $ordering_web_branch;
    public int $ordering_mobile_branch;
    public int $ordering_direct_branch;
    public int $ordering_active_inventory_web_branch;
    public int $ordering_active_inventory_direct_branch;

    public string $ordering_stating_code;

    public bool $ordering_active_inventory;

    public bool $ordering_show_company_data;

    public bool $ordering_show_branch_data;

    public bool $ordering_show_company_logo;

    public bool $ordering_show_tax_number;

    public bool $ordering_show_registration_number;

    public bool $ordering_active_shipping_fees;
    public float $ordering_shipping_fees;


    public static function group(): string
    {
        return 'ordering';
    }
}
