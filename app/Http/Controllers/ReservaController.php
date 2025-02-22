<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controllers;
use Illuminate\Http\Request;
use App\Models\Reserva;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtiene todas las relaciones cliente-etiqueta
        return response()->json(Reserva::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los campos requeridos
        $request->validate([
            'id_user' => 'required|integer|exists:usuario,id_user', // Asegura que el cliente exista
            'id_tour' => 'required|integer|exists:tour,id_tour', // Asegura que la etiqueta exista
            'fecha_reserva' => 'required|date_format:Y-m-d',
            'estado' => 'required|string|max:255',
        ]);

        // Creación de una nueva relación cliente-etiqueta
        $reserva = Reserva::create($request->only(['id_user', 'id_tour', 'fecha_reserva', 'estado']));

        return response()->json($reserva, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_reserva)
    {
        // Busca la relación cliente-etiqueta por su ID
        $reserva = Reserva::find($id_reserva);

        if ($reserva) {
            return response()->json($reserva);
        } else {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_reserva)
    {
        // Busca la relación cliente-etiqueta por su ID
        $reserva = Reserva::find($id_reserva);

        if ($reserva) {
            // Validación de los campos opcionales
            $request->validate([
                'id_user' => 'required|integer|exists:usuario,id_user', // Asegura que el cliente exista
                'id_tour' => 'required|integer|exists:tour,id_tour', // Asegura que la etiqueta exista
                'fecha_reserva' => 'required|date_format:Y-m-d',
                'estado' => 'required|string|max:255',
            ]);

            // Actualiza la relación
            $reserva->update($request->only(['id_user', 'id_tour', 'fecha_reserva', 'estado']));

            return response()->json($reserva);
        } else {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_reserva)
    {
        // Busca la relación cliente-etiqueta por su ID
        $reserva = Reserva::find($id_reserva);

        if ($reserva) {
            $reserva->delete();
            return response()->json(['message' => 'Reserva eliminada exitosamente']);
        } else {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }
    }

    /*
    public function getNmrByEtiqueta(Request $request)
    {
        $etiquetaId = $request->query('etiqueta');

        // Obtener los registros de ClienteEtiqueta y cargar los datos del cliente
        $clienteEtiquetas = ClienteEtiqueta::with('cliente') // Cargar la relación con el cliente
            ->where('idetiq', $etiquetaId)
            ->get();

        // Transformar la respuesta para que coincida con el formato deseado
        $response = $clienteEtiquetas->map(function ($clienteEtiqueta) {
            return [
                'id' => $clienteEtiqueta->id,
                'cliente' => [
                    'cod_contacto' => $clienteEtiqueta->cliente->cod_contacto, // Ajusta según tu estructura
                    'nombre' => $clienteEtiqueta->cliente->nombre,
                    'nmr' => $clienteEtiqueta->cliente->nmr,
                ],
                'etiqueta' => [
                    'idetiq' => $clienteEtiqueta->idetiq,
                    'nombretiq' => $clienteEtiqueta->etiqueta->nombretiq,
                    // Agrega más campos de la etiqueta si es necesario
                ],
            ];
        });

        return response()->json($response);
    }*/
}
