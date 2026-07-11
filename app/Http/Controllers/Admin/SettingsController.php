<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $apiKey = Setting::where('key', 'api_key')->value('value');
        $user = Auth::user();

        return view('admin.settings.index', compact('apiKey', 'user'));
    }

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
}
