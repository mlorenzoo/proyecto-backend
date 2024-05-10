<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barbershop;

class BarbershopsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barbershops = Barbershop::all();
        return response()->json(['success' => true, 'barbershops' => $barbershops]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:barbershops',
            'ubication' => 'required|string|unique:barbershops',
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
            'gestor_id' => 'nullable|exists:users,id',
            'barber_id' => 'nullable|exists:barbers,id',
        ]);

        $barbershop = Barbershop::create($data);
        return response()->json(['success' => true, 'message' => 'Barbershop created successfully', 'barbershop' => $barbershop], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barbershop = Barbershop::findOrFail($id);
        return response()->json(['success' => true, 'barbershop' => $barbershop]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $barbershop = Barbershop::findOrFail($id);

        $data = $request->validate([
            'name' => 'string|unique:barbershops,name,' . $id,
            'ubication' => 'string|unique:barbershops,ubication,' . $id,
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
            'gestor_id' => 'nullable|exists:users,id'
        ]);

        $barbershop->update($data);
        return response()->json(['success' => true, 'message' => 'Barbershop updated successfully', 'barbershop' => $barbershop]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barbershop = Barbershop::findOrFail($id);
        $barbershop->delete();
        return response()->json(['success' => true, 'message' => 'Barbershop deleted successfully']);
    }
}
