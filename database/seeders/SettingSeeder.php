<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Str;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(
            ['key' => 'api_key'],
            [
                'value'       => Str::random(24),
                'description' => 'Kunci rahasia Global untuk Aplikasi Mobile',
            ]
        );
    }
}
