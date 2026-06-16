<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $locations = Location::when($search, function ($query, $search) {
            return $query->where('location_name', 'like', '%' . $search . '%');
        })->orderBy('id', 'asc')->paginate(10);

        return view('locations.index', compact('locations', 'search'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:100',
            'max_motorcycle' => 'required|integer|min:0',
            'max_car' => 'required|integer|min:0',
            'max_other' => 'required|integer|min:0',
        ]);

        Location::create($request->only([
            'location_name',
            'max_motorcycle',
            'max_car',
            'max_other',
        ]));

        return redirect()->route('locations.index')
            ->with('success', 'New Location was successfully saved!');
    }

    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'location_name' => 'required|string|max:100',
            'max_motorcycle' => 'required|integer|min:0',
            'max_car' => 'required|integer|min:0',
            'max_other' => 'required|integer|min:0',
        ]);

        $location->update($request->only([
            'location_name',
            'max_motorcycle',
            'max_car',
            'max_other',
        ]));

        return redirect()->route('locations.index')
            ->with('success', 'Location berhasil diupdate!');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', 'Location berhasil dihapus!');
    }
}
