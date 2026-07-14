<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Str;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // API Key
        Setting::firstOrCreate(
            ['key' => 'api_key'],
            [
                'value'       => Str::random(24),
                'description' => 'Kunci rahasia Global untuk Aplikasi Mobile',
            ]
        );

        // WhatsApp Number
        Setting::firstOrCreate(
            ['key' => 'whatsapp_number'],
            [
                'value'       => '6282274016977',
                'description' => 'Nomor WhatsApp untuk pembayaran tiket',
            ]
        );
    }
}
