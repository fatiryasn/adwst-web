<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Ticket;
use App\Models\Affiliate;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDestinations = Destination::count();
        $totalTickets      = Ticket::count();
        $totalAffiliates   = Affiliate::count();

        return view('admin.dashboard', compact(
            'totalDestinations',
            'totalTickets',
            'totalAffiliates'
        ));
    }
}
