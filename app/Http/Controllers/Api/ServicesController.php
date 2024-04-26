<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los servicios
        $services = Service::all();

        // Devolver una respuesta JSON con los servicios
        return response()->json($services);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ejemplo de validación de imagen (opcional)
            // Agrega más reglas de validación si es necesario
        ]);

        // Crear un nuevo servicio
        $service = Service::create($request->all());

        // Devolver una respuesta JSON con el servicio creado
        return response()->json($service, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Buscar el servicio por su ID
        $service = Service::findOrFail($id);

        // Devolver una respuesta JSON con el servicio encontrado
        return response()->json($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar el servicio por su ID
        $service = Service::findOrFail($id);

        // Validar los datos de la solicitud
        $request->validate([
            'description' => 'string',
            'price' => 'numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ejemplo de validación de imagen (opcional)
            // Agrega más reglas de validación si es necesario
        ]);

        // Actualizar el servicio con los datos proporcionados
        $service->update($request->all());

        // Devolver una respuesta JSON con el servicio actualizado
        return response()->json($service);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar el servicio por su ID y eliminarlo
        Service::findOrFail($id)->delete();

        // Devolver una respuesta JSON indicando que el servicio fue eliminado
        return response()->json(['message' => 'Service deleted successfully']);
    }
}