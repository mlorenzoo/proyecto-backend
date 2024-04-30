<?php

namespace App\Http\Controllers\Api;

use App\Models\BarbersService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BarbersServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los servicios de barbería
        $barberServices = BarbersService::all();

        // Devolver una respuesta JSON con los servicios de barbería
        return response()->json($barberServices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'user_id' => 'required|exists:users,id,role,Barbero',
            'service_id' => 'required|exists:services,id',
            // Agrega más reglas de validación si es necesario
        ]);

        // Crear un nuevo servicio de barbería
        $barberService = BarbersService::create($request->all());

        // Devolver una respuesta JSON con el servicio de barbería creado
        return response()->json($barberService, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Buscar el servicio de barbería por su ID
        $barberService = BarbersService::findOrFail($id);

        // Devolver una respuesta JSON con el servicio de barbería encontrado
        return response()->json($barberService);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar el servicio de barbería por su ID y eliminarlo
        BarbersService::findOrFail($id)->delete();

        // Devolver una respuesta JSON indicando que el servicio de barbería fue eliminado
        return response()->json(['message' => 'Barber service deleted successfully']);
    }
}
