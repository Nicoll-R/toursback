<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    /**
     * Listar todos los usuarios.
     */
    public function index()
    {
        return response()->json(Usuario::all());
    }

    /**
     * Mostrar un usuario por ID.
     */
    public function show($id_user)
    {
        $user = Usuario::find($id_user);

        if ($user) {
            return response()->json($user);
        }

        return response()->json(['message' => 'Cliente no encontrado'], 404);
    }

    /**
     * Crear un nuevo usuario.
     */
    public function store(Request $request)
    {
        $this->validateUser($request);

        $user = Usuario::create($request->only(['name', 'lastname', 'telf', 'direccion']));

        return response()->json($user, 201);
    }

    /**
     * Actualizar información de un usuario.
     */
    public function update(Request $request, $id_user)
    {
        $user = Usuario::find($id_user);

        if (!$user) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $this->validateUser($request);

        $user->update($request->only(['name', 'lastname', 'telf', 'direccion']));

        return response()->json($user);
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy($id_user)
    {
        $user = Usuario::find($id_user);

        if (!$user) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Cliente eliminado exitosamente']);
    }

    /**
     * Buscar usuarios por nombre o apellido.
     */
    public function search(Request $request)
    {
        $query = $request->query('q');

        if (!$query) {
            return response()->json(['message' => 'Debe enviar un término de búsqueda'], 400);
        }

        $users = Usuario::where('name', 'LIKE', "%$query%")
                    ->orWhere('lastname', 'LIKE', "%$query%")
                    ->get();

        return response()->json($users);
    }

    /**
     * Filtrar usuarios por número de teléfono exacto.
     */
    public function filterByPhone(Request $request)
    {
        $phone = $request->query('telf');

        if (!$phone) {
            return response()->json(['message' => 'Número telefónico requerido'], 400);
        }

        $users = Usuario::where('telf', $phone)->get();

        return response()->json($users);
    }

    /**
     * Activar o desactivar un usuario.
     */
    public function toggleStatus($id_user)
    {
        $user = Usuario::find($id_user);

        if (!$user) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $user->active = !$user->active;
        $user->save();

        return response()->json(['message' => $user->active ? 'Usuario activado' : 'Usuario desactivado']);
    }

    /**
     * Exportar todos los usuarios (JSON o CSV).
     */
    public function export(Request $request)
    {
        $format = $request->query('format', 'json');
        $usuarios = Usuario::all();

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=usuarios.csv',
            ];

            $callback = function () use ($usuarios) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['ID', 'Nombre', 'Apellido', 'Teléfono', 'Dirección', 'Activo']);
                foreach ($usuarios as $usuario) {
                    fputcsv($handle, [
                        $usuario->id_user,
                        $usuario->name,
                        $usuario->lastname,
                        $usuario->telf,
                        $usuario->direccion,
                        $usuario->active ? 'Sí' : 'No'
                    ]);
                }
                fclose($handle);
            };

            return Response::stream($callback, 200, $headers);
        }

        return response()->json($usuarios);
    }

    /**
     * Validación centralizada para usuarios.
     */
    private function validateUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'telf' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
        ]);
    }
}
