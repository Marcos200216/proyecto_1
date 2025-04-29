<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listar usuarios
    public function index()
    {
        return User::all(); // Devuelve todos los usuarios (puedes agregar filtros si es necesario)
    }

    // Ver un usuario específico
    public function show($id)
    {
        return User::findOrFail($id); // Devuelve el usuario con el ID especificado
    }

    // Actualizar un usuario
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}
