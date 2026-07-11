<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliatePoint;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    //render tickets page
    public function index(Request $request)
    {
        //cards
        $total    = Ticket::count();
        $active   = Ticket::where('ticket_status', 'active')->count();
        $inactive = Ticket::whereIn('ticket_status', ['checked_in', 'expired', 'cancelled'])->count();

        //filters
        $search  = $request->input('search');
        $sort    = $request->input('sort', 'newest');
        $perPage = $request->input('per_page', 30);

        if (!in_array($perPage, [30, 50, 80])) {
            $perPage = 30;
        }

        $query = Ticket::with(['destination', 'affiliate']);

        //search
        if ($search) {
            $query->where('code', 'like', '%' . $search . '%');
        }

        //sorting
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $tickets = $query->paginate($perPage)->withQueryString();

        return view('admin.ticket.index', compact(
            'total',
            'active',
            'inactive',
            'tickets',
            'search',
            'sort',
            'perPage'
        ));
    }

    //render detail page
    public function show($id)
    {
        $ticket = Ticket::with(['destination', 'affiliate', 'verifiedBy'])->findOrFail($id);
        return view('admin.ticket.show', compact('ticket'));
    }

    //update status
    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->ticket_status === 'checked_in') {
            return back()->with('swal_error', 'Tiket sudah check‑in, tidak dapat diubah.');
        }

        $action = $request->input('action');

        switch ($action) {
            //pending to paid
            case 'mark_paid':
                if ($ticket->payment_status !== 'pending') {
                    return back()->with('swal_error', 'Status pembayaran tidak valid untuk aksi ini.');
                }

                DB::transaction(function () use ($ticket) {
                    $ticket->update([
                        'payment_status'      => 'paid',
                        'payment_verified_at' => now(),
                        'payment_verified_by' => Auth::id(),
                        'ticket_status'       => 'active',
                    ]);

                    if ($ticket->affiliate_id) {
                        $alreadyAwarded = AffiliatePoint::where('ticket_id', $ticket->id)
                            ->where('affiliate_id', $ticket->affiliate_id)
                            ->exists();

                        if (!$alreadyAwarded) {
                            AffiliatePoint::create([
                                'affiliate_id' => $ticket->affiliate_id,
                                'ticket_id'    => $ticket->id,
                                'points'       => 1,
                                'description'  => 'Poin dari tiket ' . $ticket->code,
                            ]);

                            Affiliate::where('id', $ticket->affiliate_id)
                                ->increment('total_points', 1);
                        }
                    }
                });

                $message = 'Pembayaran berhasil dikonfirmasi.';
                break;

            //pending to failed
            case 'mark_failed':
                if ($ticket->payment_status !== 'pending') {
                    return back()->with('swal_error', 'Status pembayaran tidak valid untuk aksi ini.');
                }
                $ticket->update([
                    'payment_status' => 'failed',
                    'ticket_status'  => 'cancelled', 
                ]);
                $message = 'Pembayaran ditandai gagal.';
                break;

            //paid to refund
            case 'mark_refunded':
                if ($ticket->payment_status !== 'paid') {
                    return back()->with('swal_error', 'Hanya tiket dengan status PAID yang dapat di‑refund.');
                }
                $ticket->update([
                    'payment_status' => 'refunded',
                    'ticket_status'  => 'cancelled',
                ]);
                $message = 'Tiket berhasil di‑refund dan dibatalkan.';
                break;

            //failed/refunded to pending
            case 'return_to_pending':
                if (!in_array($ticket->payment_status, ['failed', 'refunded'])) {
                    return back()->with('swal_error', 'Status pembayaran tidak valid untuk aksi ini.');
                }
                $ticket->update([
                    'payment_status' => 'pending',
                    'ticket_status'  => 'active',
                ]);
                $message = 'Status tiket dikembalikan ke PENDING.';
                break;

            default:
                return back()->with('swal_error', 'Aksi tidak dikenali.');
        }

        return redirect()->route('admin.ticket.show', $ticket->id)
            ->with('swal_success', $message);
    }

    //mark as checked-in
    public function checkIn($id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->ticket_status !== 'active' || $ticket->payment_status !== 'paid') {
            return back()->with('swal_error', 'Hanya tiket aktif dengan status PAID yang dapat check‑in.');
        }

        $ticket->update([
            'ticket_status'  => 'checked_in',
            'checked_in_at'  => now(),
        ]);

        return redirect()->route('admin.ticket.show', $ticket->id)
            ->with('swal_success', 'Tiket berhasil check‑in.');
    }

    public function updateNotes(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $ticket->update(['notes' => $request->notes]);

        return redirect()->route('admin.ticket.show', $ticket->id)
            ->with('swal_success', 'Catatan berhasil diperbarui.');
    }

    //
    public static function expirePendingTickets()
    {
        Ticket::where('payment_status', 'pending')
            ->where('ticket_status', 'active')
            ->where('created_at', '<', now()->subHours(24))
            ->update([
                'payment_status' => 'failed',
                'ticket_status'  => 'expired',
            ]);
    }
}
