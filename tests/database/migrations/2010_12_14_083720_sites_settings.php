<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->createSetting('sites.site_name', '3x1');
        $this->createSetting('sites.site_description', 'Creative Solutions');
        $this->createSetting('sites.site_keywords', 'Graphics, Marketing, Programming');
        $this->createSetting('sites.site_profile', '');
        $this->createSetting('sites.site_logo', '');
        $this->createSetting('sites.site_author', 'Fady Mondy');
        $this->createSetting('sites.site_email', 'info@3x1.io');
        $this->createSetting('sites.site_phone', '+201207860084');
        $this->createSetting('sites.site_social', []);
    }

    private function createSetting(string $setting, mixed $value): void
    {
        $exSetting = explode('.', $setting);
        $group = $exSetting[0];
        $name = $exSetting[1];

        $settingExists = DB::table('settings')
            ->where('name', $name)
            ->where('group', $group)
            ->first();

        if (! $settingExists) {
            DB::table('settings')
                ->insert([
                    'group' => $group,
                    'name' => $name,
                    'locked' => 0,
                    'payload' => json_encode($value),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        }
    }
};
