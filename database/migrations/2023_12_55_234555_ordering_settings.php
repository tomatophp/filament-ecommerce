<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('ordering.ordering_web_branch', 1);
        $this->migrator->add('ordering.ordering_mobile_branch', 1);
        $this->migrator->add('ordering.ordering_direct_branch', 1);
        $this->migrator->add('ordering.ordering_show_company_data', true);
        $this->migrator->add('ordering.ordering_show_branch_data', true);
        $this->migrator->add('ordering.ordering_show_company_logo', true);
        $this->migrator->add('ordering.ordering_show_tax_number', true);
        $this->migrator->add('ordering.ordering_show_registration_number', true);
        $this->migrator->add('ordering.ordering_stating_code', "TOMATO");
        $this->migrator->add('ordering.ordering_active_inventory', 0);
        $this->migrator->add('ordering.ordering_active_inventory_web_branch', 1);
        $this->migrator->add('ordering.ordering_active_inventory_direct_branch', 1);
        $this->migrator->add('ordering.ordering_active_shipping_fees', false);
        $this->migrator->add('ordering.ordering_shipping_fees', 10);
    }
};
