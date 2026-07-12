<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    //show
    public function index(Request $request)
    {
        //summary cards
        $total      = Destination::count();
        $active     = Destination::where('status', 'active')->count();
        $inactive   = Destination::where('status', 'inactive')->count();

        //filters
        $search = $request->input('search');
        $sort   = $request->input('sort', 'newest');
        $perPage = $request->input('per_page', 30);

        //limits
        $allowedPerPage = [30, 50, 80];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 30;
        }

        $query = Destination::query();

        //search
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        //sorting
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
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

        $destinations = $query->paginate($perPage)->withQueryString();

        return view('admin.destination.index', compact(
            'total',
            'active',
            'inactive',
            'destinations',
            'search',
            'sort',
            'perPage'
        ));
    }

    //show create page
    public function new()
    {
        return view('admin.destination.new');
    }

    //insert
    public function store(Request $request)
    {
        //ONLY 1 DESTINATION
        if (Destination::count() >= 1) {
            return redirect()->back()
                ->with('swal_error', 'Hanya satu destinasi yang diizinkan. Anda dapat mengedit destinasi yang sudah ada.')
                ->withInput();
        }
        //validate
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:200'],
            'description'   => ['nullable', 'string'],
            'address'       => ['nullable', 'string'],
            'latitude'      => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'     => ['nullable', 'numeric', 'between:-180,180'],
            'status'        => ['required', 'in:active,inactive'],
            'thumbnail'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],

            'cottages'                 => ['nullable', 'array'],
            'cottages.*.name'          => ['required', 'string', 'max:200'],
            'cottages.*.description'   => ['nullable', 'string'],
            'cottages.*.price'         => ['required', 'numeric', 'min:0'],
        ]);

        //upload thumbnail
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        //create destination
        $destination = Destination::create([
            'name'          => $validated['name'],
            'slug'          => $this->generateUniqueSlug($validated['name']),
            'description'   => $validated['description'] ?? null,
            'address'       => $validated['address'] ?? null,
            'latitude'      => $validated['latitude'] ?? null,
            'longitude'     => $validated['longitude'] ?? null,
            'status'        => $validated['status'],
            'thumbnail'     => $thumbnailPath,
        ]);

        //save cottages if provided
        if (!empty($validated['cottages'])) {
            foreach ($validated['cottages'] as $cottageData) {
                $destination->cottages()->create([
                    'name'        => $cottageData['name'],
                    'description' => $cottageData['description'] ?? null,
                    'price'       => $cottageData['price'],
                ]);
            }
        }

        return redirect()
            ->route('admin.destination.index')
            ->with('swal_success', 'Destinasi "' . $destination->name . '" berhasil ditambahkan.');
    }

    //render detail page
    public function show($id)
    {
        $destination = Destination::with('cottages')->findOrFail($id);
        return view('admin.destination.show', compact('destination'));
    }

    //update
    public function update(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:200'],
            'description'   => ['nullable', 'string'],
            'address'       => ['nullable', 'string'],
            'latitude'      => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'     => ['nullable', 'numeric', 'between:-180,180'],
            'status'        => ['required', 'in:active,inactive'],
            'thumbnail'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        // Handle name change -> regenerate slug
        $nameChanged = $destination->name !== $validated['name'];
        $slug = $nameChanged ? $this->generateUniqueSlug($validated['name'], $destination->id) : $destination->slug;

        // Handle thumbnail removal / upload
        if ($request->has('remove_thumbnail') && $request->remove_thumbnail) {
            // Delete old thumbnail from storage
            if ($destination->thumbnail) {
                Storage::disk('public')->delete($destination->thumbnail);
            }
            $thumbnailPath = null;
        } elseif ($request->hasFile('thumbnail')) {
            // New file uploaded – delete old one if exists
            if ($destination->thumbnail) {
                Storage::disk('public')->delete($destination->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        } else {
            // No removal, no new file – keep existing
            $thumbnailPath = $destination->thumbnail;
        }

        $destination->update([
            'name'          => $validated['name'],
            'slug'          => $slug,
            'description'   => $validated['description'] ?? $destination->description,
            'address'       => $validated['address'] ?? $destination->address,
            'latitude'      => $validated['latitude'] ?? $destination->latitude,
            'longitude'     => $validated['longitude'] ?? $destination->longitude,
            'status'        => $validated['status'],
            'thumbnail'     => $thumbnailPath,
        ]);

        return redirect()
            ->route('admin.destination.show', $destination->id)
            ->with('swal_success', 'Destinasi ' . $destination->name . ' berhasil diperbarui.');
    }

    //delete
    public function destroy($id)
    {
        $destination = Destination::findOrFail($id);

        //delete the thumbnail
        if ($destination->thumbnail) {
            Storage::disk('public')->delete($destination->thumbnail);
        }

        $destination->delete();

        return redirect()
            ->route('admin.destination.index')
            ->with('swal_success', 'Destinasi ' . $destination->name . ' berhasil dihapus.');
    }

    //add cottage
    public function addCottage(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
        ]);

        $destination->cottages()->create($validated);

        return redirect()->route('admin.destination.show', $destination->id)
            ->with('swal_success', 'Pondok berhasil ditambahkan.');
    }

    //delete cottage
    public function deleteCottage($id, $cottageId)
    {
        $destination = Destination::findOrFail($id);
        $cottage = $destination->cottages()->findOrFail($cottageId);
        $cottage->delete();

        return redirect()->route('admin.destination.show', $destination->id)
            ->with('swal_success', 'Pondok berhasil dihapus.');
    }

    //unique slug
    private function generateUniqueSlug(string $name, $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        $query = Destination::withTrashed()->where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
            $query = Destination::withTrashed()->where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
}
