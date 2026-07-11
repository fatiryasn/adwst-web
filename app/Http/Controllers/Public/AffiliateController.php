<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    //render affiliate page
    public function index()
    {
        return view('landing.affiliate');
    }

    //insert new affiliate
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'          => ['required', 'string', 'max:100'],
            'email'              => ['required', 'email', 'max:100', 'unique:affiliates,email'],
            'phone_number'       => ['required', 'string', 'max:20'],
            'promotion_channels' => ['nullable', 'array'],
            'join_reason'        => ['nullable', 'string'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar sebagai afiliasi.',
        ]);

        $channels = $request->has('promotion_channels')
            ? implode(',', $request->promotion_channels)
            : null;

        $code = $this->generateUniqueCode();

        $affiliate = Affiliate::create([
            'code'               => $code,
            'full_name'          => $validated['full_name'],
            'email'              => $validated['email'],
            'phone_number'       => $validated['phone_number'],
            'promotion_channels' => $channels,
            'join_reason'        => $validated['join_reason'] ?? null,
            'total_points'       => 0,
        ]);

        session()->flash('affiliate_success_id', $affiliate->id);
        return redirect()->route('affiliates.success');
    }

    //render success page
    public function success()
    {
        $affiliateId = session()->pull('affiliate_success_id');

        if (!$affiliateId) {
            return redirect()->route('affiliates.index');
        }

        $affiliate = Affiliate::findOrFail($affiliateId);

        return view('landing.affiliate-success', compact('affiliate'));
    }

    //render check points page
    public function checkForm()
    {
        return view('landing.affiliate-check');
    }

    //find affiliate by checing
    public function check(Request $request)
    {
        $request->validate([
            'code_or_url' => ['required', 'string'],
            'email'       => ['required', 'email'],
        ]);

        $input = $request->code_or_url;
        $email = $request->email;

        $code = $input;
        if (filter_var($input, FILTER_VALIDATE_URL)) {
            $query = parse_url($input, PHP_URL_QUERY);
            $params = [];
            parse_str($query ?? '', $params);
            $code = $params['ref'] ?? '';
        }

        if (empty($code)) {
            return back()->withErrors(['code_or_url' => 'Kode afiliasi tidak valid.'])->withInput();
        }

        $affiliate = Affiliate::where('code', strtoupper($code))
            ->where('email', $email)
            ->first();

        if (!$affiliate) {
            return back()->withErrors(['code_or_url' => 'Kode atau email tidak cocok.'])->withInput();
        }

        $censoredName  = $this->censorString($affiliate->full_name);
        $censoredPhone = $this->censorString($affiliate->phone_number);

        return view('landing.affiliate-check', [
            'verified'       => true,
            'affiliate'      => $affiliate,
            'censoredName'   => $censoredName,
            'censoredPhone'  => $censoredPhone,
            'inputCode'      => $code,  
            'inputEmail'     => $email,
        ]);
    }

    //HELPER: censor string
    private function censorString(string $value): string
    {
        $length = mb_strlen($value);
        if ($length <= 2) {
            return '***';
        }
        $first = mb_substr($value, 0, 1);
        $last  = mb_substr($value, -1);
        $stars = str_repeat('*', $length - 2);
        return $first . $stars . $last;
    }

    //HELPER: generate affiliate code
    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Affiliate::where('code', $code)->exists());

        return $code;
    }
}
