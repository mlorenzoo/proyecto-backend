<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barber;
use App\Models\BarberSchedule;

class BarbersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los barberos
        $barbers = Barber::with('user', 'barbershop')->get();

        // Devolver una respuesta JSON con los barberos
        return response()->json(['success' => true, 'data' => $barbers]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'id' => 'required|exists:users,id',
            'bio' => 'nullable|string',
            'experience' => 'nullable|string',
            'specialties' => 'nullable|string',
            'pics' => 'nullable|string',
            'barbershop_id' => 'nullable|exists:barbershops,id',
        ]);

        // Crear un nuevo barbero
        $barber = Barber::create($request->all());

        // Devolver una respuesta JSON con el barbero creado
        return response()->json($barber, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Buscar el barbero por su ID
        $barber = Barber::findOrFail($id);

        // Devolver una respuesta JSON con el barbero encontrado
        return response()->json($barber);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar el barbero por su ID
        $barber = Barber::findOrFail($id);

        // Validar los datos de la solicitud
        $request->validate([
            'user_id' => 'exists:users,id',
            'bio' => 'nullable|string',
            'experience' => 'nullable|string',
            'specialties' => 'nullable|string',
            'pics' => 'nullable|string',
        ]);

        // Actualizar el barbero con los datos proporcionados
        $barber->update($request->all());

        // Devolver una respuesta JSON con el barbero actualizado
        return response()->json(['success' => true, 'data' => $barber]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar el barbero por su ID y eliminarlo
        Barber::findOrFail($id)->delete();

        // Devolver una respuesta JSON indicando que el barbero fue eliminado
        return response()->json(['message' => 'Barber deleted successfully']);
    }

    public function getSchedules(string $id)
    {
        // Buscar el barbero por su ID
        $barber = Barber::findOrFail($id);

        // Obtener los horarios del barbero
        $schedules = BarberSchedule::where('barber_id', $barber->id)->get();

        // Devolver una respuesta JSON con los horarios del barbero
        return response()->json(['success' => true, 'data' => $schedules]);
    }

    /**
     * Update the specified schedule of the specified barber.
     */
    public function updateSchedule(Request $request, string $barberId, string $scheduleId)
    {
        // Buscar el horario del barbero por su ID
        $schedule = BarberSchedule::where('barber_id', $barberId)->findOrFail($scheduleId);

        // Validar los datos de la solicitud
        $request->validate([
            'day_of_week' => 'integer|between:1,7', // Validar que el día de la semana esté entre 1 y 7 (Lunes a Domingo)
            'start_time' => 'date_format:H:i:s', // Validar el formato de la hora de inicio
            'end_time' => 'date_format:H:i:s', // Validar el formato de la hora de fin
            'month' => 'string', // Puedes agregar más validaciones según tus necesidades
        ]);

        // Actualizar el horario del barbero con los datos proporcionados
        $schedule->update($request->all());

        // Devolver una respuesta JSON con el horario actualizado y éxito true
        return response()->json(['success' => true, 'data' => $schedule]);
    }
}