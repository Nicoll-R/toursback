<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controllers;
use Illuminate\Http\Request;
use App\Models\Pago;

class PagoController extends Controller
{
       /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtiene todas las relaciones cliente-etiqueta
        return response()->json(Pago::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los campos requeridos
        $request->validate([
            'feca_pago' => 'required|date_format:Y-m-d',
            'metodo' => 'required|string|max:255', // Asegura que la etiqueta exista
            'monto' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/',
            'id_reserva' => 'required|integer|exists:reserva,id_reserva',
        ]);

        // Creación de una nueva relación cliente-etiqueta
        $pago = Pago::create($request->only(['feca_pago', 'metodo', 'monto', 'id_reserva']));

        return response()->json($pago, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_pago)
    {
        // Busca la relación cliente-etiqueta por su ID
        $pago = Pago::find($id_pago);

        if ($pago) {
            return response()->json($pago);
        } else {
            return response()->json(['message' => 'Pago no encontrada'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_pago)
    {
        // Busca la relación cliente-etiqueta por su ID
        $pago = Pago::find($id_pago);

        if ($pago) {
            // Validación de los campos opcionales
            $request->validate([
                'feca_pago' => 'required|date_format:Y-m-d',
                'metodo' => 'required|string|max:255', // Asegura que la etiqueta exista
                'monto' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/',
                'id_reserva' => 'required|integer|exists:reserva,id_reserva',
            ]);

            // Actualiza la relación
            $pago->update($request->only(['feca_pago', 'metodo', 'monto', 'id_reserva']));

            return response()->json($pago);
        } else {
            return response()->json(['message' => 'Pago no encontrada'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_pago)
    {
        // Busca la relación cliente-etiqueta por su ID
        $pago = Pago::find($id_pago);

        if ($pago) {
            $pago->delete();
            return response()->json(['message' => 'Pago eliminada exitosamente']);
        } else {
            return response()->json(['message' => 'Pago no encontrada'], 404);
        }
    }

}
