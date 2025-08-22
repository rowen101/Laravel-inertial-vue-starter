<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $vehicles = Vehicle::orderBy('created_at', 'desc')->get();
        
        return Inertia::render('fleet/VehicleRegistration', [
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|unique:vehicles',
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'vin' => 'required|string|unique:vehicles',
            'status' => 'required|in:active,maintenance,out_of_service',
            'mileage' => 'nullable|numeric|min:0',
            'registration_expiry' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        Vehicle::create($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle): Response
    {
        return Inertia::render('fleet/VehicleShow', [
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|unique:vehicles,vehicle_number,' . $vehicle->id,
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'vin' => 'required|string|unique:vehicles,vin,' . $vehicle->id,
            'status' => 'required|in:active,maintenance,out_of_service',
            'mileage' => 'nullable|numeric|min:0',
            'registration_expiry' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
