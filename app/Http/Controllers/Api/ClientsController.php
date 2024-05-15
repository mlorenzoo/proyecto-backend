<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los clientes
        $clients = Client::all();

        // Devolver una respuesta JSON con los clientes y éxito true
        return response()->json(['success' => true, 'data' => $clients]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscribed' => 'nullable|boolean',
        ]);

        // Crear el cliente
        $client = Client::create($data);

        // Devolver una respuesta JSON con el cliente creado y éxito true
        return response()->json(['success' => true, 'data' => $client], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Buscar el cliente por su ID
        $client = Client::findOrFail($id);

        // Devolver una respuesta JSON con el cliente encontrado y éxito true
        return response()->json(['success' => true, 'data' => $client]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Buscar el cliente por su ID
        $client = Client::findOrFail($id);

        // Validar los datos de la solicitud
        $data = $request->validate([
            'user_id' => 'exists:users,id',
            'subscribed' => 'nullable|boolean',
        ]);

        // Actualizar el cliente con los datos proporcionados
        $client->update($data);

        // Devolver una respuesta JSON con el cliente actualizado y éxito true
        return response()->json(['success' => true, 'data' => $client]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Buscar el cliente por su ID y eliminarlo
        $client = Client::findOrFail($id);
        $client->delete();

        // Devolver una respuesta JSON indicando si el cliente fue eliminado con éxito
        return response()->json(['success' => true, 'message' => 'Client deleted successfully']);
    }
}

