<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();
        return response()->json(['success' => true, 'data' => $payments], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $payment = Payment::create($request->all());
        
        if ($payment) {
            return response()->json(['success' => true, 'data' => $payment], 201);
        } else {
            return response()->json(['success' => false, 'message' => 'Error al crear el pago'], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::findOrFail($id);
        return response()->json(['success' => true, 'data' => $payment], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);
        
        if ($payment->update($request->all())) {
            return response()->json(['success' => true, 'data' => $payment], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Error al actualizar el pago'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        
        if ($payment->delete()) {
            return response()->json(['success' => true, 'message' => 'Pago eliminado correctamente'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Error al eliminar el pago'], 400);
        }
    }
}
