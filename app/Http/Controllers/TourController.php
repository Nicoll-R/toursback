<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TourController extends Controller
{
    /**
     * Devuelve una lista de todos los tours.
     */
    public function index()
    {
        $tours = Tour::all();

        if ($tours->isEmpty()) {
            return response()->json(['message' => 'No hay tours disponibles'], 204);
        }

        return response()->json($tours, 200);
    }

    /**
     * Muestra los detalles de un tour específico.
     */
    public function show($id_tour)
    {
        $tour = Tour::where('id_tour', $id_tour)->first();

        if (!$tour) {
            return response()->json(['message' => 'Tour no encontrado'], 404);
        }

        return response()->json($tour, 200);
    }

    /**
     * Crea un nuevo tour en la base de datos.
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
            $tour = Tour::create($request->only([
                'nombreto', 'descripcion', 'precio',
                'duracion', 'fecha_inicio', 'destino'
            ]));

            return response()->json([
                'message' => 'Tour creado exitosamente',
                'data' => $tour
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear tour: ' . $e->getMessage());
            return response()->json(['message' => 'Error al guardar el tour'], 500);
        }
    }

    /**
     * Actualiza un tour existente.
     */
    public function update(Request $request, $id_tour)
    {
        $tour = Tour::where('id_tour', $id_tour)->first();

        if (!$tour) {
            return response()->json(['message' => 'Tour no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tour->update($request->only([
                'nombreto', 'descripcion', 'precio',
                'duracion', 'fecha_inicio', 'destino'
            ]));

            return response()->json([
                'message' => 'Tour actualizado exitosamente',
                'data' => $tour
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar tour: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar el tour'], 500);
        }
    }

    /**
     * Elimina un tour existente.
     */
    public function destroy($id_tour)
    {
        $tour = Tour::where('id_tour', $id_tour)->first();

        if (!$tour) {
            return response()->json(['message' => 'Tour no encontrado'], 404);
        }

        try {
            $tour->delete();
            return response()->json(['message' => 'Tour eliminado exitosamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al eliminar tour: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar el tour'], 500);
        }
    }

    /**
     * Reglas de validación reutilizables.
     */
    private function validationRules()
    {
        return [
            'nombreto' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'precio' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/',
            'duracion' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date_format:Y-m-d|after_or_equal:today',
            'destino' => 'required|string|max:255',
        ];
    }
}
