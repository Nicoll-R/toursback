<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controllers;
use Illuminate\Http\Request;
use App\Models\Usuario;

class UserController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Usuario::all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id_user)
    {
        $user = Usuario::where('id_user', $id_user)->first();

        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'telf' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
        ]);

        $user = Usuario::create($request->only(['name', 'lastname', 'telf', 'direccion']));

        return response()->json($user, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_user)
    {
        $user = Usuario::where('id_user', $id_user)->first();

        if ($user) {
            $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'telf' => 'required|string|max:255',
                'direccion' => 'required|string|max:255',
            ]);

            $user->update($request->only(['name', 'lastname', 'telf', 'direccion']));

            return response()->json($user);
        } else {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
// En el controlador
    public function destroy($id_user)
    {
        $user = Usuario::where('id_user', $id_user)->first();  // Corregido

        if ($user) {
            $user->delete();
            return response()->json(['message' => 'Cliente eliminado exitosamente']);
        } else {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
    }

}
