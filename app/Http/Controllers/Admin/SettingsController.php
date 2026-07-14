<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    //render setting page
    public function index()
    {
        $apiKey = Setting::where('key', 'api_key')->value('value');
        $user = Auth::user();
        $whatsappNumber = Setting::where('key', 'whatsapp_number')->value('value') ?? '6281234567890';

        return view('admin.settings.index', compact('apiKey', 'user', 'whatsappNumber'));
    }

    //rotate api key
    public function rotateApiKey(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $newKey = Str::random(24);

        Setting::updateOrCreate(
            ['key' => 'api_key'],
            [
                'value'       => $newKey,
                'description' => 'Kunci rahasia global untuk Aplikasi Mobile',
            ]
        );

        return redirect()->route('admin.settings.index')
            ->with('swal_success', 'API Key berhasil diperbarui');
    }

    //update whatsapp
    public function updateWhatsApp(Request $request)
    {
        $request->validate([
            'whatsapp_number' => ['required', 'string', 'regex:/^[0-9]{10,14}$/'],
        ]);

        $setting = Setting::firstOrCreate(['key' => 'whatsapp_number']);
        $setting->value = $request->whatsapp_number;
        $setting->save();

        return redirect()->back()->with('swal_success', 'Nomor WhatsApp berhasil diperbarui.');
    }
}
