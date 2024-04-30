<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los usuarios
        $users = User::all();

        // Devolver una respuesta JSON con los usuarios
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:Admin,Gestor,Barbero,Cliente',
            'pfp' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        // Crear un nuevo usuario
        $user = User::create($request->all());

        // Devolver una respuesta JSON con el usuario creado
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Buscar el usuario por su ID
        $user = User::findOrFail($id);

        // Devolver una respuesta JSON con el usuario encontrado
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar el usuario por su ID
        $user = User::findOrFail($id);

        // Validar los datos de la solicitud
        $request->validate([
            'name' => 'string',
            'surname' => 'string',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'string|min:8',
            'role' => 'string|in:Admin,Gestor,Barbero,Cliente',
            'pfp' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        // Actualizar el usuario con los datos proporcionados
        $user->update($request->all());

        // Devolver una respuesta JSON con el usuario actualizado
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar el usuario por su ID y eliminarlo
        User::findOrFail($id)->delete();

        // Devolver una respuesta JSON indicando que el usuario fue eliminado
        return response()->json(['message' => 'User deleted successfully']);
    }
}
