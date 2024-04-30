<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::all();
        return response()->json($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'barber_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:users,id',
            'services_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'state' => 'required|in:programada,confirmada,completada,cancelada',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::create($validatedData);
        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = Appointment::findOrFail($id);
        return response()->json($appointment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validatedData = $request->validate([
            'barber_id' => 'exists:users,id',
            'client_id' => 'exists:users,id',
            'services_id' => 'exists:services,id',
            'date' => 'date',
            'state' => 'in:programada,confirmada,completada,cancelada',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validatedData);
        return response()->json($appointment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Appointment::findOrFail($id)->delete();
        return response()->json(['message' => 'Appointment deleted successfully']);
    }
}
