<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use App\Models\BarberSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class AppointmentController extends Controller
{
    public function index(Request $request, $barberId)
    {
        $date = $request->query('date');
    
        if (!$barberId) {
            return response()->json(['message' => 'Barber ID is required'], 400);
        }
    
        if (!$date) {
            return response()->json(['message' => 'Date is required'], 400);
        }
    
        $date = Carbon::parse($date);
        $dayOfWeek = $date->format('l');
        Log::info($dayOfWeek);
    
        $barberSchedule = BarberSchedule::forBarberAndDay($barberId, $dayOfWeek)->first();
    
        Log::info('Barber Schedule: ' . $barberSchedule);
    
        if (!$barberSchedule) {
            return response()->json(['message' => 'No schedule available for the barber on this date'], 400);
        }
    
        $startDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $barberSchedule->start_time);
        $endDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $barberSchedule->end_time);
    
        $existingAppointments = Appointment::where('barber_id', $barberId)
            ->whereDate('date', $date)
            ->pluck('hour');
    
        $availableHours = [];
        for ($time = $startDateTime; $time->lt($endDateTime); $time->addHour()) {
            $hour = $time->format('H:i');
            if (!$existingAppointments->contains($hour)) {
                $availableHours[] = $hour;
            }
        }
    
        return response()->json(['available_hours' => $availableHours]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'barber_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'hour' => 'required|regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/',
            'state' => 'required|in:programada,confirmada,completada,cancelada',
            'notes' => 'nullable|string',
        ]);

        $date = Carbon::parse($validatedData['date']);
        $dayOfWeek = $date->format('l');

        $barberSchedule = BarberSchedule::where('barber_id', $validatedData['barber_id'])
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$barberSchedule) {
            return response()->json(['message' => 'No hay horario disponible para el barbero en esta fecha'], 400);
        }

        $appointmentTime = Carbon::parse($validatedData['date'] . ' ' . $validatedData['hour']);
        $startTime = Carbon::parse($validatedData['date'] . ' ' . $barberSchedule->start_time);
        $endTime = Carbon::parse($validatedData['date'] . ' ' . $barberSchedule->end_time);

        if ($appointmentTime->lt($startTime) || $appointmentTime->gte($endTime)) {
            return response()->json(['message' => 'La hora seleccionada no está dentro del horario del barbero'], 400);
        }

        $existingAppointment = Appointment::where('barber_id', $validatedData['barber_id'])
            ->whereDate('date', $validatedData['date'])
            ->where('hour', $validatedData['hour'])
            ->exists();

        if ($existingAppointment) {
            return response()->json(['message' => 'Ya hay una cita programada en la misma hora'], 400);
        }

        $appointment = Appointment::create($validatedData);
        return response()->json($appointment, 201);
    }

    public function show($barberId)
    {
        $dayOfWeek = Carbon::now()->format('l');
        $barberSchedule = BarberSchedule::where('barber_id', $barberId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$barberSchedule) {
            return response()->json(['message' => 'No hay horario disponible para el barbero hoy'], 400);
        }

        $startDateTime = Carbon::now()->startOfDay()->addHours($barberSchedule->start_time);
        $endDateTime = Carbon::now()->startOfDay()->addHours($barberSchedule->end_time);

        $existingAppointments = Appointment::where('barber_id', $barberId)
            ->whereDate('date', Carbon::today())
            ->pluck('hour');

        $availableHours = [];
        for ($time = $startDateTime; $time->lt($endDateTime); $time->addHour()) {
            $hour = $time->format('H:i');
            $availableHours[$hour] = !$existingAppointments->contains($hour);
        }

        return response()->json(['available_hours' => $availableHours]);
    }

    public function update(Request $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validatedData = $request->validate([
            'barber_id' => 'exists:users,id',
            'client_id' => 'exists:users,id',
            'date' => 'date',
            'state' => 'in:programada,confirmada,completada,cancelada',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validatedData);
        return response()->json($appointment);
    }

    public function destroy(string $id)
    {
        Appointment::findOrFail($id)->delete();
        return response()->json(['message' => 'Appointment deleted successfully']);
    }
}