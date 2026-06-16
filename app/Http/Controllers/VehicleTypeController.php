<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $vehicleTypes = VehicleType::when($search, function ($query, $search) {
            return $query->where('jenis', 'like', '%' . $search . '%');
        })->orderBy('id', 'asc')->paginate(10);

        return view('vehicle_types.index', compact('vehicleTypes', 'search'));
    }

    public function create()
    {
        return view('vehicle_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:motorcycle,car,other',
            'perjam_pertama' => 'required|integer|min:0',
            'perjam_berikutnya' => 'required|integer|min:0',
            'max_perhari' => 'required|integer|min:0',
        ]);

        VehicleType::create($request->only([
            'jenis',
            'perjam_pertama',
            'perjam_berikutnya',
            'max_perhari',
        ]));

        return redirect()->route('vehicle-types.index')
            ->with('success', 'New Vehicle Type was successfully saved!');
    }

    public function edit(VehicleType $vehicleType)
    {
        return view('vehicle_types.edit', compact('vehicleType'));
    }

    public function update(Request $request, VehicleType $vehicleType)
    {
        $request->validate([
            'jenis' => 'required|in:motorcycle,car,other',
            'perjam_pertama' => 'required|integer|min:0',
            'perjam_berikutnya' => 'required|integer|min:0',
            'max_perhari' => 'required|integer|min:0',
        ]);

        $vehicleType->update($request->only([
            'jenis',
            'perjam_pertama',
            'perjam_berikutnya',
            'max_perhari',
        ]));

        return redirect()->route('vehicle-types.index')
            ->with('success', 'Vehicle Type berhasil diupdate!');
    }

    public function destroy(VehicleType $vehicleType)
    {
        $vehicleType->delete();

        return redirect()->route('vehicle-types.index')
            ->with('success', 'Vehicle Type berhasil dihapus!');
    }
}
