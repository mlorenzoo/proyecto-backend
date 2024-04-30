<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomerSubscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = CustomerSubscription::all();
        return response()->json($subscriptions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'sub_id' => 'required|exists:subscriptions,id',
            'pay_id' => 'required|exists:payments,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'state' => 'required|string|in:Activa,Cancelada,Finalizada',
            // Agrega más reglas de validación si es necesario
        ]);

        $subscription = CustomerSubscription::create($validatedData);
        return response()->json($subscription, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subscription = CustomerSubscription::findOrFail($id);
        return response()->json($subscription);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subscription = CustomerSubscription::findOrFail($id);

        $validatedData = $request->validate([
            'client_id' => 'exists:clients,id',
            'sub_id' => 'exists:subscriptions,id',
            'pay_id' => 'exists:payments,id',
            'start_date' => 'date',
            'end_date' => 'date',
            'state' => 'string|in:Activa,Cancelada,Finalizada',
            // Agrega más reglas de validación si es necesario
        ]);

        $subscription->update($validatedData);
        return response()->json($subscription);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CustomerSubscription::findOrFail($id)->delete();
        return response()->json(['message' => 'Customer subscription deleted successfully']);
    }
}
