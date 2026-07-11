<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    //render affiliates page
    public function index(Request $request)
    {
        //cards
        $totalAffiliates = Affiliate::count();
        $totalPoints     = Affiliate::sum('total_points');

        //filters
        $search  = $request->input('search');
        $sort    = $request->input('sort', 'newest');
        $perPage = $request->input('per_page', 30);

        if (!in_array($perPage, [30, 50, 80])) {
            $perPage = 30;
        }

        $query = Affiliate::query();

        //search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', '%' . $search . '%')
                    ->orWhere('full_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
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

        $affiliates = $query->paginate($perPage)->withQueryString();

        return view('admin.affiliate.index', compact(
            'totalAffiliates',
            'totalPoints',
            'affiliates',
            'search',
            'sort',
            'perPage'
        ));
    }

    //render detail page
    public function show($id)
    {
        $affiliate = Affiliate::with(['points' => function ($q) {
            $q->with('ticket')->latest();
        }])->findOrFail($id);

        return view('admin.affiliate.show', compact('affiliate'));
    }
}
