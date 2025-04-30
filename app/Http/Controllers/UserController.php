<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios.
     */
    public function index()
    {
        $usuarios = Usuario::all();

        if ($usuarios->isEmpty()) {
            return response()->json(['message' => 'No hay usuarios registrados'], 204);
        }

        return response()->json($usuarios, 200);
    }

    /**
     * Muestra los detalles de un usuario específico.
     */
    public function show($id_user)
    {
        $user = Usuario::where('id_user', $id_user)->first();

        if (!$user) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return response()->json($user, 200);
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Usuario::create($request->only(['name', 'lastname', 'telf', 'direccion']));
            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Actualiza los datos de un usuario existente.
     */
    public function update(Request $request, $id_user)
    {
        $user = Usuario::where('id_user', $id_user)->first();

        if (!$user) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update($request->only(['name', 'lastname', 'telf', 'direccion']));
            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar el usuario'], 500);
        }
    }

    /**
     * Elimina un usuario existente.
     */
    public function destroy($id_user)
    {
        $user = Usuario::where('id_user', $id_user)->first();

        if (!$user) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        try {
            $user->delete();
            return response()->json(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar el usuario'], 500);
        }
    }

    /**
     * Reglas de validación reutilizables.
     */
    private function validationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'telf' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
        ];
    }
}
