<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use App\Models\Rol;
use Illuminate\Http\Request;
use App\Models\Modulo;
use App\Models\Permiso;
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

    private $modulos = [
        'roles',
        'usuarios',
        'Llben',
        
        'Travis',
        'Lacoste',
        'historico',
        'activar_2fa'
    ];


public function getPermisos($id)
{
    $rol = Rol::findOrFail($id);

    $permisos = $rol->permisos->keyBy('modulo')->map(function ($permiso) {
        return [
            'puede_ver' => (int)$permiso->puede_ver,
            'puede_crear' => (int)$permiso->puede_crear,
            'puede_editar' => (int)$permiso->puede_editar,
            'puede_eliminar' => (int)$permiso->puede_eliminar,
        ];
    });

    return response()->json([
        'rol_nombre' => $rol->nombre,
        'permisos' => $permisos
    ]);
}
public function guardarPermisos(Request $request, $id)
{
    $rol = Rol::findOrFail($id);
    $data = $request->input('permisos') ?? [];

    // Lista completa de módulos que deberían tener permisos
    $modulos = [
        'roles',
        'usuarios',
        'Llben',
        'Travis',
        'Lacoste',
        'historico',
        'activar_2fa'
    ];

    foreach ($modulos as $modulo) {
        $acciones = $data[$modulo] ?? [];

        $permiso = Permiso::firstOrNew([
            'rol_id' => $rol->id,
            'modulo' => $modulo
        ]);

        $permiso->puede_ver     = isset($acciones['ver']) ? 1 : 0;
        $permiso->puede_crear   = isset($acciones['crear']) ? 1 : 0;
        $permiso->puede_editar  = isset($acciones['editar']) ? 1 : 0;
        $permiso->puede_eliminar= isset($acciones['eliminar']) ? 1 : 0;

        $permiso->save();
    }

    return response()->json(['success' => true]);
}








}
