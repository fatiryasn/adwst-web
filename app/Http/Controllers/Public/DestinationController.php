<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    //render destinations page
    public function index(Request $request)
    {
        $sort   = $request->input('sort', 'newest');
        $search = $request->input('search');

        $query = Destination::where('status', 'active');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        switch ($sort) {
            case 'az':
                $query->orderBy('name', 'asc');
                break;
            case 'za':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $perPage      = 15;
        $destinations = $query->paginate($perPage);

        if ($request->ajax()) {
            $html = '';
            foreach ($destinations as $dest) {
                $html .= view('landing.partials.destination-card', compact('dest'))->render();
            }

            return response()->json([
                'html'     => $html,
                'hasMore'  => $destinations->hasMorePages(),
                'nextPage' => $destinations->currentPage() + 1,
            ]);
        }

        return view('landing.destination', compact('destinations', 'sort', 'search'));
    }

    //render destination detail page
    public function show($slug)
    {
        $destination = Destination::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        return view('landing.destination-detail', compact('destination'));
    }
}
