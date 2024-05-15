<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\User;

class UserClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Buscar el usuario por su ID
        $user = User::findOrFail($id);

        // Obtener el client_id si el usuario es un cliente
        $clientId = null;
        if ($user->role === 'Cliente') {
            $client = Client::where('user_id', $id)->first();
            if ($client) {
                $clientId = $client->id;
            }
        }

        // Devolver una respuesta JSON con el usuario encontrado y éxito true
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'client_id' => $clientId
            ]
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
