<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    // Mostrar lista de roles
    public function index()
    {
        $roless = Rol::all();
        return view('roles.index', compact('roless'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('roles.create');
    }

    // Guardar un nuevo rol
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:roles,nombre',
            
        ]);

        Rol::create($request->only('nombre'));

        return response()->json(['message' => 'Rol creado correctamente.']);
    }

      // Mostrar el formulario para editar un rol
    public function edit($id)
    {
        $rol = Rol::findOrFail($id);
        return response()->json($rol);
    }


    // Actualizar un rol
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            
        ]);

        $rol = Rol::findOrFail($id);
        $rol->nombre = $request->nombre;
        
        $rol->save();

        return response()->json(['message' => 'Rol actualizado correctamente.']);
    }


    // Eliminar un rol
    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);
        $rol->delete();

        return response()->json(['message' => 'Rol eliminado correctamente.']);
    }
}
