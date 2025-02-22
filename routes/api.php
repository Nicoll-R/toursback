<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//use App\Models\Cliente;
// routes/api.php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\PagoController;

Route::get('/clientes', [UserController::class, 'index']);
Route::get('/clientes/{id_user}', [UserController::class, 'show']);
Route::post('/clientes', [UserController::class, 'store']);
Route::put('/clientes/{id_user}', [UserController::class, 'update']);
Route::delete('/clientes/{id_user}', [UserController::class, 'destroy']);

Route::get('/tour', [TourController::class, 'index']);
Route::get('/tour/{id_tour}', [TourController::class, 'show']);
Route::post('/tour', [TourController::class, 'store']);
Route::put('/tour/{id_tour}', [TourController::class, 'update']);
Route::delete('/tour/{id_tour}', [TourController::class, 'destroy']);

Route::get('/reserva', [ReservaController::class, 'index']);
Route::get('/reserva/{id_reserva}', [ReservaController::class, 'show']);
Route::post('/reserva', [ReservaController::class, 'store']);
Route::put('/reserva/{id_reserva}', [ReservaController::class, 'update']);
Route::delete('/reserva/{id_reserva}', [ReservaController::class, 'destroy']);

Route::get('/pago', [PagoController::class, 'index']);
Route::get('/pago/{id_pago}', [PagoController::class, 'show']);
Route::post('/pago', [PagoController::class, 'store']);
Route::put('/pago/{id_pago}', [PagoController::class, 'update']);
Route::delete('/pago/{id_pago}', [PagoController::class, 'destroy']);



/* Route::get('/clientes', function (){
    $clientes = Cliente::all();  // Obtiene todos los clientes
    return response()->json(['clientes' => $clientes]); 
});

Route::get('/clientes/{id}', function ($id){
    $cliente = Cliente::find($id);

    if ($cliente) {
        return response()->json(['cliente' => $cliente]);
    } else {
        return response()->json(['message' => 'Cliente no encontrado'], 404);
    }
});

Route::post('/clientes', function (Request $request){
    $request->validate([
        'nmr' => 'required|string|max:255',
        'nombre' => 'required|string|max:255',
    ]);

    $cliente = Cliente::create($request->only(['nmr', 'nombre']));

    return response()->json([
        'message' => 'Cliente creado exitosamente',
        'cliente' => $cliente
    ], 201);
});

Route::put('/clientes/{id}', function (Request $request, $id){
    $cliente = Cliente::find($id);

    if ($cliente) {
        $request->validate([
            'nmr' => 'sometimes|string|max:255',
            'nombre' => 'sometimes|string|max:255',
        ]);

        $cliente->update($request->only(['nmr', 'nombre']));

        return response()->json([
            'message' => 'Cliente actualizado exitosamente',
            'cliente' => $cliente
        ]);
    } else {
        return response()->json(['message' => 'Cliente no encontrado'], 404);
    }
});

Route::delete('/clientes/{id}', function ($id){
    $cliente = Cliente::find($id);

    if ($cliente) {
        $cliente->delete();
        return response()->json(['message' => 'Cliente eliminado exitosamente']);
    } else {
        return response()->json(['message' => 'Cliente no encontrado'], 404);
    }
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/


