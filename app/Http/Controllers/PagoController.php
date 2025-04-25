<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class PagoController extends Controller
{
    /**
     * Muestra una lista de todos los pagos.
     */
    public function index()
    {
        return response()->json(Pago::all());
    }

    /**
     * Guarda un nuevo pago en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'feca_pago' => 'required|date_format:Y-m-d',
            'metodo' => 'required|string|max:255',
            'monto' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/',
            'id_reserva' => 'required|integer|exists:reserva,id_reserva',
        ]);

        $pago = Pago::create($request->only(['feca_pago', 'metodo', 'monto', 'id_reserva']));

        return response()->json($pago, 201);
    }

    /**
     * Muestra un pago específico.
     */
    public function show(string $id_pago)
    {
        $pago = Pago::find($id_pago);

        if ($pago) {
            return response()->json($pago);
        }

        return response()->json(['message' => 'Pago no encontrado'], 404);
    }

    /**
     * Actualiza un pago existente.
     */
    public function update(Request $request, string $id_pago)
    {
        $pago = Pago::find($id_pago);

        if (!$pago) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        $request->validate([
            'feca_pago' => 'required|date_format:Y-m-d',
            'metodo' => 'required|string|max:255',
            'monto' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/',
            'id_reserva' => 'required|integer|exists:reserva,id_reserva',
        ]);

        $pago->update($request->only(['feca_pago', 'metodo', 'monto', 'id_reserva']));

        return response()->json($pago);
    }

    /**
     * Elimina un pago específico.
     */
    public function destroy(string $id_pago)
    {
        $pago = Pago::find($id_pago);

        if (!$pago) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        $pago->delete();

        return response()->json(['message' => 'Pago eliminado exitosamente']);
    }

    /**
     * Buscar pagos por método de pago (GET /pagos/buscar?metodo=Efectivo).
     */
    public function searchByMetodo(Request $request)
    {
        $metodo = $request->query('metodo');

        if (!$metodo) {
            return response()->json(['message' => 'Debe proporcionar un método de pago'], 400);
        }

        $pagos = Pago::where('metodo', 'LIKE', '%' . $metodo . '%')->get();

        return response()->json($pagos);
    }

    /**
     * Filtra pagos por un rango de fechas.
     */
    public function filterByDateRange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'desde' => 'required|date',
            'hasta' => 'required|date|after_or_equal:desde',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pagos = Pago::whereBetween('feca_pago', [$request->desde, $request->hasta])->get();

        return response()->json($pagos);
    }

    /**
     * Exportar pagos como JSON o CSV.
     */
    public function export(Request $request)
    {
        $format = $request->query('format', 'json');

        $pagos = Pago::all();

        if ($format === 'csv') {
            $headers = [
                'Content-type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=pagos.csv',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate',
                'Expires' => '0'
            ];

            $callback = function () use ($pagos) {
                $output = fopen('php://output', 'w');
                fputcsv($output, ['ID', 'Fecha Pago', 'Método', 'Monto', 'ID Reserva']);
                foreach ($pagos as $pago) {
                    fputcsv($output, [
                        $pago->id,
                        $pago->feca_pago,
                        $pago->metodo,
                        $pago->monto,
                        $pago->id_reserva
                    ]);
                }
                fclose($output);
            };

            return Response::stream($callback, 200, $headers);
        }

        // Default JSON export
        return response()->json($pagos);
    }
}
