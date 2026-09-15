<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['site_primary_color', 'site_secondary_color', 'site_tertiary_color'] as $name) {
            $exists = DB::table('settings')
                ->where('group', 'site_colors')
                ->where('name', $name)
                ->exists();

            if (! $exists) {
                DB::table('settings')->insert([
                    'group' => 'site_colors',
                    'name' => $name,
                    'locked' => 0,
                    'payload' => json_encode(null),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
};
