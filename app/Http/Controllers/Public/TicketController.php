<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\Destination;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    //render buy ticket page
    public function create($slug)
    {
        $destination = Destination::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

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
        $destination = Destination::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $validated = $request->validate([
            'customer_name'    => ['required', 'string', 'max:100'],
            'customer_email'   => ['nullable', 'email', 'max:150'],
            'customer_phone'   => ['required', 'string', 'max:20'],
            'visit_date'       => ['nullable', 'date'],
            'departure_date'   => ['nullable', 'date', 'after_or_equal:visit_date'],
            'referral_sources' => ['nullable', 'array'],
            'affiliate_code'   => ['nullable', 'string'],
        ]);

        $referralSources = $request->has('referral_sources')
            ? implode(',', $request->referral_sources)
            : null;

        $affiliateId = null;
        if ($request->filled('affiliate_code')) {
            $affiliate = Affiliate::where('code', strtoupper($request->affiliate_code))->first();
            if ($affiliate) {
                $affiliateId = $affiliate->id;
                $referralSources = $referralSources
                    ? $referralSources . ',Afiliasi'
                    : 'Afiliasi';
            }
        }

        $code = $this->generateUniqueTicketCode();

        $ticket = Ticket::create([
            'code'            => $code,
            'destination_id'  => $destination->id,
            'affiliate_id'    => $affiliateId,
            'customer_name'   => $validated['customer_name'],
            'customer_email'  => $validated['customer_email'] ?? null,
            'customer_phone'  => $validated['customer_phone'],
            'visit_date'      => $validated['visit_date'] ?? null,
            'departure_date'  => $validated['departure_date'] ?? null,
            'referral_source' => $referralSources,
            'ticket_price'    => $destination->ticket_price,
            'payment_status'  => 'pending',
            'ticket_status'   => 'active',
        ]);

        session()->flash('ticket_success_id', $ticket->id);
        session()->forget('affiliate_ref');

        return redirect()->route('destinations.tickets.success', $destination->slug);
    }

    //render success page
    public function success($slug)
    {
        $ticketId = session()->pull('ticket_success_id');
        if (!$ticketId) {
            return redirect()->route('destinations.index');
        }

        $ticket = Ticket::with('destination')->findOrFail($ticketId);
        return view('landing.destination-buy-success', compact('ticket'));
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