<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controllers;
use Illuminate\Http\Request;
use App\Models\Tour;

class TourController extends Controller
{
            /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Tour::all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id_tour)
    {
        $tour = Tour::where('id_tour', $id_tour)->first();

        if ($tour) {
            return response()->json($tour);
        } else {
            return response()->json(['message' => 'Tour no encontrado'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombreto' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'precio' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/',
            'duracion' => 'required|integer',
            'fecha_inicio' => 'required|date_format:Y-m-d',
            'destino' => 'required|string|max:255',
        ]);

        $tour = Tour::create($request->only(['nombreto', 'descripcion', 'precio', 'duracion', 'fecha_inicio', 'destino']));

        return response()->json($tour, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_tour)
    {
        $tour = Tour::where('id_tour', $id_tour)->first();

        if ($tour) {
            $request->validate([
                'nombreto' => 'required|string|max:255',
                'descripcion' => 'required|string|max:255',
                'precio' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/',
                'duracion' => 'required|integer',
                'fecha_inicio' => 'required|date_format:Y-m-d',
                'destino' => 'required|string|max:255',
            ]);

            $tour->update($request->only(['nombreto', 'descripcion', 'precio', 'duracion', 'fecha_inicio', 'destino']));

            return response()->json($tour);
        } else {
            return response()->json(['message' => 'Tour no encontrado'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
// En el controlador
    public function destroy($id_tour)
    {
        $id_tour = Tour::where('id_tour', $id_tour)->first();  // Corregido

        if ($id_tour) {
            $id_tour->delete();
            return response()->json(['message' => 'Tour eliminado exitosamente']);
        } else {
            return response()->json(['message' => 'Tour no encontrado'], 404);
        }
    }
}
