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
use Illuminate\Support\Facades\Storage;


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

            $this->createDefaultBarberSchedules($barberData->id);

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
        return response()->json(['success' => true, 'data' => $user], 201);    }

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
                $user->barber = $barber;
                $barberSchedules = BarberSchedule::where('barber_id', $barber->id)->get();
                $user->barber->schedules = $barberSchedules;
            }
        }
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

        // Si se ha enviado una nueva contraseña, hashearla
        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        if ($request->hasFile('pfp')) {
            // Eliminar la imagen anterior si existe
            if ($user->pfp) {
                Storage::disk('public')->delete($user->pfp);
            }

            $path = $request->file('pfp')->store('profile', 'public');
            $userData['pfp'] = $path;
        }


        // Actualizar el usuario con los datos proporcionados
        $user->update($userData);

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function updateBarberSchedule(Request $request, $barberId, $scheduleId)
    {
        $schedule = BarberSchedule::where('barber_id', $barberId)->findOrFail($scheduleId);

        $scheduleData = $request->validate([
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
        ]);

        $schedule->update($scheduleData);

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

    public function updateProfilePicture(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'pfp' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($user->pfp) {
            Storage::disk('public')->delete($user->pfp);
        }

        $path = $request->file('pfp')->store('profile', 'public');

        $user->update(['pfp' => $path]);

        return response()->json(['success' => true, 'message' => 'Profile picture updated successfully']);
    }
}
