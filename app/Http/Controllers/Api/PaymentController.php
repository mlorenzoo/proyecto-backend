<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();
        return response()->json($payments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'pay_date' => 'required|date',
            'amount' => 'required|numeric',
            'pay_method' => 'required|string',
            // Agrega más reglas de validación si es necesario
        ]);

        $payment = Payment::create($validatedData);
        return response()->json($payment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::findOrFail($id);
        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);

        $validatedData = $request->validate([
            'client_id' => 'exists:clients,id',
            'pay_date' => 'date',
            'amount' => 'numeric',
            'pay_method' => 'string',
            // Agrega más reglas de validación si es necesario
        ]);

        $payment->update($validatedData);
        return response()->json($payment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Payment::findOrFail($id)->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
