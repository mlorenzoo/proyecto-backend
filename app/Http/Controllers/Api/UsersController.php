<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Barber;
use App\Models\Client;
use App\Models\BarberSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Necesario para trabajar con almacenamiento de archivos

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los usuarios
        $users = User::all();

        // Devolver una respuesta JSON con los usuarios y éxito true
        return response()->json(['success' => true, 'data' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $userData = $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:Admin,Gestor,Barbero,Cliente',
            'pfp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        // Crear el usuario
        $password = Hash::make($userData['password']);
        $path = null;
        if ($request->hasFile('pfp')) {
            $path = $request->file('pfp')->store('profile', 'public');
        }
        $user = User::create(array_merge($userData, ['password' => $password, 'pfp' => $path]));

        // Verificar si el rol es "Barbero" y crear el registro de barbero asociado al usuario
        if ($userData['role'] === 'Barbero') {
            $barberData = Barber::create([
                'user_id' => $user->id,
                'bio' => null,
                'experience' => null,
                'specialties' => null,
                'pics' => null,
                'barbershop_id' => null
            ]);

            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'September', 'October', 'November', 'December'];
            $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

            foreach ($months as $month) {
                if ($month !== 'August') { // Si no es agosto
                    foreach ($daysOfWeek as $dayOfWeek) {
                        BarberSchedule::create([
                            'barber_id' => $barberData->id,
                            'day_of_week' => $dayOfWeek,
                            'start_time' => '09:00:00', // Horario de inicio predeterminado
                            'end_time' => '17:00:00', // Horario de fin predeterminado
                            'month' => $month,
                        ]);
                    }
                }
            }

            Log::debug($barberData);
            return response()->json(['success' => true, 'data' => ['user' => $user, 'barber' => $barberData]], 201);
        }

        // Verificar si el usuario es un cliente y crearlo si es necesario
        if ($userData['role'] === 'Cliente') {
            $clientData = Client::create([
                'user_id' => $user->id,
                'subscribed' => false,
            ]);
            Log::debug($clientData);
        }

        // Devolver una respuesta JSON con el usuario creado y éxito true
        return response()->json(['success' => true, 'data' => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Buscar el usuario por su ID
        $user = User::findOrFail($id);

        // Verificar si el usuario es de tipo "Barbero"
        if ($user->role === 'Barbero') {
            // Si es barbero, obtener los datos del barbero asociado
            $barber = Barber::where('user_id', $user->id)->first();

            // Verificar si se encontró un registro de barbero asociado
            if ($barber) {
                // Si se encontró, agregar los datos del barbero al objeto del usuario
                $user->barber = $barber;

                // Obtener los horarios del barbero
                $barberSchedules = BarberSchedule::where('barber_id', $barber->id)->get();

                // Agregar los horarios al objeto del barbero
                $user->barber->schedules = $barberSchedules;
            }
        }

        // Devolver una respuesta JSON con el usuario (y sus datos de barbero y horarios si corresponde) y éxito true
        return response()->json(['success' => true, 'data' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar el usuario por su ID
        $user = User::findOrFail($id);

        // Validar los datos de la solicitud
        $userData = $request->validate([
            'name' => 'string',
            'surname' => 'string',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'string|min:8',
            'role' => 'string|in:Admin,Gestor,Barbero,Cliente',
            'pfp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);
        if ($request->hasFile('pfp')) {
            // Eliminar la imagen anterior si existe
            if ($user->pfp) {
                Storage::disk('public')->delete($user->pfp);
            }

            // Almacenar la nueva imagen y actualizar la ruta en los datos del usuario
            $path = $request->file('pfp')->store('profile', 'public');
            $userData['pfp'] = $path;
        }

        // Actualizar el usuario con los datos proporcionados
        $user->update($userData);

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function updateBarberSchedule(Request $request, $barberId, $scheduleId)
    {
        // Buscar el horario del barbero por su ID
        $schedule = BarberSchedule::where('barber_id', $barberId)->findOrFail($scheduleId);

        // Validar los datos de la solicitud
        $scheduleData = $request->validate([
            'day_of_week' => 'integer|between:1,7', // Validar que el día de la semana esté entre 1 y 7 (Lunes a Domingo)
            'start_time' => 'date_format:H:i:s', // Validar el formato de la hora de inicio
            'end_time' => 'date_format:H:i:s', // Validar el formato de la hora de fin
            'month' => 'string', // Puedes agregar más validaciones según tus necesidades
        ]);

        // Actualizar el horario del barbero con los datos proporcionados
        $schedule->update($scheduleData);

        // Devolver una respuesta JSON con el horario actualizado y éxito true
        return response()->json(['success' => true, 'data' => $schedule]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar el usuario por su ID y eliminarlo
        $user = User::findOrFail($id);
        $user->delete();

        // Devolver una respuesta JSON indicando si el usuario fue eliminado con éxito
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}
