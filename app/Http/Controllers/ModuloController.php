<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ModuloController extends Controller
{
    public function index()
    {
        $modulos = Modulo::all();
        return view('modulos.index', compact('modulos'));
    }

    public function create()
    {
        return view('modulos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:modulos',
            'descripcion' => 'nullable|string|max:255'
        ]);

        Modulo::create($request->all());

        return redirect()->route('modulos.index')->with('success', 'Módulo creado correctamente.');
    }

    public function edit($id)
    {
        $modulo = Modulo::findOrFail($id);
        return response()->json($modulo);
    }


    public function update(Request $request, Modulo $modulo)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:modulos,nombre,' . $modulo->id,
            'descripcion' => 'nullable|string|max:255'
        ]);

        $modulo->update($request->only(['nombre', 'descripcion']));

        return response()->json(['success' => true, 'message' => 'Módulo actualizado correctamente']);
    }


    public function destroy(Modulo $modulo)
    {
        try {
            $modulo->delete();
            return response()->json(['success' => true, 'message' => 'Módulo eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar módulo'], 500);
        }
    }

}
