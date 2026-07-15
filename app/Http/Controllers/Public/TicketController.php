<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\Cottage;
use App\Models\Destination;
use App\Models\Setting;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    //render buy ticket page
    // public function create($slug)
    // {
    //     $destination = Destination::with(['cottages' => function ($q) {
    //         $q->with(['tickets' => function ($q) {
    //             $q->whereNotIn('ticket_status', ['cancelled', 'expired'])
    //                 ->select('id', 'cottage_id', 'visit_date', 'departure_date');
    //         }]);
    //     }])->where('slug', $slug)->where('status', 'active')->firstOrFail();

    //     $refCode = request()->query('ref') ?: session()->get('affiliate_ref');
    //     $affiliate = null;
    //     if ($refCode) {
    //         $affiliate = Affiliate::where('code', strtoupper($refCode))->first();
    //     }

    //     return view('landing.destination-buy', compact('destination', 'affiliate'));
    // }

    //render buy ticket page
    public function create()
    {
        // [LOAD FIRST DESTINATION]
        $destination = Destination::with(['cottages' => function ($q) {
            $q->with(['tickets' => function ($q) {
                $q->whereNotIn('ticket_status', ['cancelled', 'expired'])
                    ->select('id', 'cottage_id', 'visit_date', 'departure_date');
            }]);
        }])->where('status', 'active')->firstOrFail();

        $refCode = request()->query('ref') ?: session()->get('affiliate_ref');
        $affiliate = null;
        if ($refCode) {
            $affiliate = Affiliate::where('code', strtoupper($refCode))->first();
        }

        return view('landing.destination-buy', compact('destination', 'affiliate'));
    }

    //insert new ticket
    public function store(Request $request, $slug)
    {
        $destination = Destination::with('cottages')->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $hasCottages = $destination->cottages->isNotEmpty();

        //validation
        $rules = [
            'customer_name'    => ['required', 'string', 'max:100'],
            'customer_email'   => ['nullable', 'email', 'max:150'],
            'customer_phone'   => ['required', 'string', 'max:20'],
            'customer_destination_detail' => ['nullable', 'string', 'max:4000'],
            'visit_date'       => ['required', 'date', 'after_or_equal:today'],
            'departure_date'   => ['required', 'date', 'after_or_equal:visit_date'],
            'referral_sources' => ['nullable', 'array'],
            'affiliate_code'   => ['nullable', 'string'],
        ];

        if ($hasCottages) {
            $rules['cottage_id'] = ['required', 'exists:cottages,id'];
        } else {
            $rules['cottage_id'] = ['nullable'];
            $rules['customer_destination_detail'] = ['required', 'string', 'max:4000'];
        }
        $validated = $request->validate($rules);

        //cottage logic
        $cottage = null;
        if ($hasCottages && $request->filled('cottage_id')) {
            $cottage = Cottage::findOrFail($validated['cottage_id']);
            if ($cottage->destination_id != $destination->id) {
                return back()->withErrors(['cottage_id' => 'Pondok tidak valid untuk destinasi ini.'])->withInput();
            }
        }

        //affiliate logic
        $referralSources = $request->has('referral_sources') ? implode(',', $request->referral_sources) : null;
        $affiliateId = null;
        if ($request->filled('affiliate_code')) {
            $affiliate = Affiliate::where('code', strtoupper($request->affiliate_code))->first();
            if ($affiliate) {
                $affiliateId = $affiliate->id;
                $referralSources = $referralSources ? $referralSources . ',Afiliasi' : 'Afiliasi';
            }
        }

        $code = $this->generateUniqueTicketCode();

        //create
        $ticket = Ticket::create([
            'code'            => $code,
            'destination_id'  => $destination->id,
            'cottage_id'      => $cottage ? $cottage->id : null,
            'affiliate_id'    => $affiliateId,
            'customer_name'   => $validated['customer_name'],
            'customer_email'  => $validated['customer_email'] ?? null,
            'customer_phone'  => $validated['customer_phone'],
            'customer_destination_detail' => $validated['customer_destination_detail'] ?? null,
            'visit_date'      => $validated['visit_date'],
            'departure_date'  => $validated['departure_date'],
            'referral_source' => $referralSources,
            'ticket_price'    => $cottage ? $cottage->price : 0,
            'payment_status'  => 'pending',
            'ticket_status'   => 'active',
        ]);

        session()->flash('ticket_success_id', $ticket->id);
        session()->forget('affiliate_ref');

        return redirect()->route('destinations.tickets.success', $destination->slug);
    }

    //render success page
    public function success()
    {
        $ticketId = session()->pull('ticket_success_id');
        if (!$ticketId) {
            return redirect()->route('destinations.index');
        }

        $ticket = Ticket::with('destination')->findOrFail($ticketId);
        $whatsappNumber = Setting::where('key', 'whatsapp_number')->value('value') ?? '6281234567890';

        return view('landing.destination-buy-success', compact('ticket', 'whatsappNumber'));
    }

    //HELPER: generate ticket code
    private function generateUniqueTicketCode(): string
    {
        do {
            $code = strtoupper('TKT-' . Str::random(8));
        } while (Ticket::where('code', $code)->exists());

        return $code;
    }
}
