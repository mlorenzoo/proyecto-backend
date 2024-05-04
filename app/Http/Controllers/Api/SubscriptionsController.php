<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las suscripciones
        $subscriptions = Subscription::all();

        // Devolver una respuesta JSON con las suscripciones
        return response()->json([
            'success' => true,
            'data' => $subscriptions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $validator = $request->validate([
            'plan' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'duration' => 'required|string',
            // Agrega más reglas de validación si es necesario
        ]);

        // Crear una nueva suscripción
        $subscription = Subscription::create($request->all());

        // Devolver una respuesta JSON con la suscripción creada
        return response()->json([
            'success' => true,
            'data' => $subscription
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Buscar la suscripción por su ID
        $subscription = Subscription::findOrFail($id);

        // Devolver una respuesta JSON con la suscripción encontrada
        return response()->json([
            'success' => true,
            'data' => $subscription
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar la suscripción por su ID
        $subscription = Subscription::findOrFail($id);

        // Validar los datos de la solicitud
        $validator = $request->validate([
            'plan' => 'string',
            'price' => 'numeric',
            'description' => 'nullable|string',
            'duration' => 'string',
            // Agrega más reglas de validación si es necesario
        ]);

        // Actualizar la suscripción con los datos proporcionados
        $subscription->update($request->all());

        // Devolver una respuesta JSON con la suscripción actualizada
        return response()->json([
            'success' => true,
            'data' => $subscription
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar la suscripción por su ID y eliminarla
        Subscription::findOrFail($id)->delete();

        // Devolver una respuesta JSON indicando que la suscripción fue eliminada
        return response()->json([
            'success' => true,
            'message' => 'Subscription deleted successfully'
        ]);
    }
}
