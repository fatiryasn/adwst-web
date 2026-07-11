<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'full_name'    => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->full_name    = $request->full_name;
        $user->phone_number = $request->phone_number;
        $user->save();

        return redirect()
            ->route('admin.profile.index')
            ->with('swal_success', 'Profil berhasil diperbarui.');
    }
}
