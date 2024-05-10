<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


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
        $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:Admin,Gestor,Barbero,Cliente',
            'pfp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',            
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        if ($request->hasFile('pfp')) {
            // Guardar la imagen en la carpeta "profile" del almacenamiento público
            $path = $request->file('pfp')->store('profile', 'public');
        } else {
            $path = null;
        }        
        // Hash de la contraseña utilizando Bcrypt
        $password = Hash::make($request->password);

        // Crear un nuevo usuario con la contraseña hasheada
        $user = User::create(array_merge($request->all(), ['password' => $password, 'pfp' => $path]));
        // Devolver una respuesta JSON con el usuario creado y éxito true
        return response()->json(['success' => true, 'data' => $user], 201);    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Buscar el usuario por su ID
        $user = User::findOrFail($id);

        // Devolver una respuesta JSON con el usuario encontrado y éxito true
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
    
            $path = $request->file('pfp')->store('profile', 'public');
            $userData['pfp'] = $path;
        }
    
        // Actualizar el usuario con los datos proporcionados
        $user->update($userData);
    
        // Devolver una respuesta JSON con el usuario actualizado y éxito true
        return response()->json(['success' => true, 'data' => $user]);
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
