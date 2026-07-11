<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    //get ticket by code
    public function showByCode($code)
    {
        $ticket = Ticket::where('code', $code)
            ->with('destination:id,name,address,ticket_price')
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail tiket berhasil diambil.',
            'data'    => [
                'id'              => $ticket->id,
                'code'            => $ticket->code,
                'customer_name'   => $ticket->customer_name,
                'customer_phone'  => $ticket->customer_phone,
                'customer_email'  => $ticket->customer_email,
                'visit_date'      => $ticket->visit_date?->toDateString(),
                'departure_date'  => $ticket->departure_date?->toDateString(),
                'ticket_price'    => $ticket->ticket_price,
                'payment_status'  => $ticket->payment_status,
                'ticket_status'   => $ticket->ticket_status,
                'destination'     => $ticket->destination ? [
                    'name'    => $ticket->destination->name,
                    'address' => $ticket->destination->address,
                    'price'   => $ticket->destination->ticket_price,
                ] : null,
                'checked_in_at'   => $ticket->checked_in_at?->toDateTimeString(),
                'created_at'      => $ticket->created_at->toDateTimeString(),
            ],
        ]);
    }

    //mark ticket as checked in
    public function checkIn($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan.',
            ], 404);
        }

        if ($ticket->ticket_status === 'checked_in') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah check‑in sebelumnya.',
            ], 422);
        }

        if ($ticket->ticket_status !== 'active' || $ticket->payment_status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak valid untuk check‑in. Pastikan pembayaran telah lunas dan tiket masih aktif.',
            ], 422);
        }

        $ticket->update([
            'ticket_status' => 'checked_in',
            'checked_in_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check‑in berhasil.',
            'data'    => [
                'checked_in_at' => $ticket->checked_in_at->toDateTimeString(),
            ],
        ]);
    }

    //test api key validity
    public function ping()
    {
        return response()->json([
            'success' => true,
            'message' => 'API key is valid.',
        ]);
    }
}
