<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use App\Models\BarberSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtén los parámetros de la solicitud
        $barberId = $request->query('barber_id');
        $date = $request->query('date');

        if (!$barberId || !$date) {
            return response()->json(['message' => 'Barber ID and date are required'], 400);
        }

        // Convierte la fecha a un objeto Carbon
        $date = Carbon::parse($date);
        $dayOfWeek = $date->format('l');

        // Obtén el horario del barbero para el día especificado
        $barberSchedule = BarberSchedule::where('barber_id', $barberId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$barberSchedule) {
            return response()->json(['message' => 'No schedule available for the barber on this date'], 400);
        }

        // Calcula la hora de inicio y fin del horario del barbero
        $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d').' '.$barberSchedule->start_time);
        $endDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d').' '.$barberSchedule->end_time);

        // Construye una lista con todas las horas dentro del horario del barbero
        $currentDateTime = clone $startDateTime;
        $allHours = [];
        while ($currentDateTime->lt($endDateTime)) {
            $allHours[] = $currentDateTime->format('H:i');
            $currentDateTime->addMinutes(60); // Siguiente hora disponible
        }

        // Obtén todas las citas programadas para el barbero en el día especificado
        $existingAppointments = Appointment::where('barber_id', $barberId)
            ->whereDate('date', $date)
            ->get();

        // Marca las horas que ya tienen citas como no disponibles
        $availableHours = [];
        foreach ($allHours as $hour) {
            $isAvailable = true;
            foreach ($existingAppointments as $appointment) {
                if ($appointment->hour === $hour) {
                    $isAvailable = false;
                    break;
                }
            }
            if ($isAvailable) {
                $availableHours[] = $hour;
            }
        }

        return response()->json(['available_hours' => $availableHours]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'barber_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'hour' => 'required|regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', // Validar formato de hora HH:MM
            'state' => 'required|in:programada,confirmada,completada,cancelada',
            'notes' => 'nullable|string',
        ]);

        // Obtén el horario del barbero para el día de la cita
        $barberSchedule = BarberSchedule::where('barber_id', $validatedData['barber_id'])
            ->where('day_of_week', Carbon::parse($validatedData['date'])->format('l')) // Obtén el día de la semana para la fecha
            ->first();

        if (!$barberSchedule) {
            return response()->json(['message' => 'No hay horario disponible para el barbero en esta fecha'], 400);
        }

        // Verifica si la hora propuesta está dentro del horario del barbero
        $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $validatedData['date'].' '.$validatedData['hour']);
        $barberStartDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $validatedData['date'].' '.$barberSchedule->start_time);
        $barberEndDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $validatedData['date'].' '.$barberSchedule->end_time);

        if ($startDateTime->lt($barberStartDateTime) || $startDateTime->gte($barberEndDateTime)) {
            return response()->json(['message' => 'La hora seleccionada no está dentro del horario del barbero'], 400);
        }

        // Verifica si ya hay una cita programada en la misma hora
        $existingAppointment = Appointment::where('barber_id', $validatedData['barber_id'])
            ->whereDate('date', $validatedData['date'])
            ->where('hour', $validatedData['hour'])
            ->first();

        if ($existingAppointment) {
            return response()->json(['message' => 'Ya hay una cita programada en la misma hora'], 400);
        }

        // Crea la cita
        $appointment = Appointment::create($validatedData);
        return response()->json($appointment, 201);
    }

    public function show($barberId)
    {
        // Obtén el horario del barbero para el día de hoy
        $dayOfWeek = Carbon::now()->format('l');
        $barberSchedule = BarberSchedule::where('barber_id', $barberId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$barberSchedule) {
            return response()->json(['message' => 'No hay horario disponible para el barbero hoy'], 400);
        }

        // Calcula la hora de inicio y fin del horario del barbero
        $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->format('Y-m-d').' '.$barberSchedule->start_time);
        $endDateTime = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->format('Y-m-d').' '.$barberSchedule->end_time);

        // Construye una lista con todas las horas dentro del horario del barbero
        $currentDateTime = clone $startDateTime;
        $allHours = [];
        while ($currentDateTime->lt($endDateTime)) {
            $allHours[] = $currentDateTime->format('H:i');
            $currentDateTime->addMinutes(60); // Siguiente hora disponible
        }

        // Obtén todas las citas programadas para el barbero en el día de hoy
        $existingAppointments = Appointment::where('barber_id', $barberId)
            ->whereDate('date', Carbon::today())
            ->get();

        // Marca las horas que ya tienen citas como no disponibles
        $availableHours = [];
        foreach ($allHours as $hour) {
            $isAvailable = true;
            foreach ($existingAppointments as $appointment) {
                if ($appointment->hour === $hour) {
                    $isAvailable = false;
                    break;
                }
            }
            $availableHours[$hour] = $isAvailable;
        }

        return response()->json(['available_hours' => $availableHours]);
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
            //'services_id' => 'exists:services,id',
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
