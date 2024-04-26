<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barber;

class BarbersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los barberos
        $barbers = Barber::with('user')->get();

        // Devolver una respuesta JSON con los barberos
        return response()->json($barbers);
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
        return response()->json($barber);
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
}