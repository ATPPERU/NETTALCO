<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with('usuario.roles')->get();

        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $roles = Rol::all();
        return view('empleados.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni' => [
                'required', 'string', 'max:20',
                function ($attribute, $value, $fail) {
                    // Validar que el DNI no esté en empleados
                    if (\App\Models\Empleado::where('dni', $value)->exists()) {
                        $fail('El DNI ya está registrado en empleados.');
                    }

                    // Validar que el DNI no esté en usuarios (por si lo usas como usuario o ID)
                    if (\App\Models\Usuario::where('usuario', $value)->exists()) {
                        $fail('El DNI ya está registrado como nombre de usuario.');
                    }
                }
            ],
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email' => [
                'required', 'email',
                function ($attribute, $value, $fail) {
                    // Validar que el email no esté en usuarios ni empleados
                    if (\App\Models\Usuario::where('email', $value)->exists()) {
                        $fail('El correo electrónico ya está registrado en usuarios.');
                    }

                    
                }
            ],
            'usuario' => 'required|string|unique:usuarios,usuario',
            'password' => 'required|min:6',
            'roles' => 'required|array'
        ]);

        DB::beginTransaction();

        try {
            // Crear usuario
            $usuario = Usuario::create([
                'email' => $request->email,
                'usuario' => $request->usuario,
                'password' => $request->password, // bcrypt automático por mutator
                'activo' => 1
            ]);

            // Asignar roles
            $usuario->roles()->attach($request->roles);

            // Crear empleado
            Empleado::create([
                'dni' => $request->dni,
                'nombre' => $request->nombres,
                'apellido' => $request->apellidos,
                'telefono' => $request->telefono ?? null,
                'direccion' => $request->direccion ?? null,
                'fecha_ingreso' => $request->fecha_ingreso ?? now(),
                
                'usuario_id' => $usuario->id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Empleado y usuario creados correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el empleado.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function show($id)
    {
        $empleado = Empleado::with('usuario.roles')->findOrFail($id);
        return view('empleados.show', compact('empleado'));
    }

    public function edit($id)
    {
        $empleado = Empleado::with('usuario.roles')->findOrFail($id);
        $roles = Rol::all();
        return view('empleados.edit', compact('empleado', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::with('usuario')->findOrFail($id);

        $request->validate([
            'dni' => 'required|string|max:20|unique:empleados,dni,' . $empleado->id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'fecha_ingreso' => 'nullable|date',
            'email' => 'required|email|unique:usuarios,email,' . $empleado->usuario_id,
            'usuario' => 'required|string|unique:usuarios,usuario,' . $empleado->usuario_id,
            'password' => 'nullable|min:6',
            'roles' => 'required|array'
        ]);

        DB::beginTransaction();

        try {
            // Actualizar usuario
            $empleado->usuario->update([
                'email' => $request->email,
                'usuario' => $request->usuario,
                'password' => $request->password ? bcrypt($request->password) : $empleado->usuario->password,
            ]);

            $empleado->usuario->roles()->sync($request->roles);

            // Actualizar empleado
            $empleado->update([
                'dni' => $request->dni,
                'nombre' => $request->nombres,
                'apellido' => $request->apellidos,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'fecha_ingreso' => $request->fecha_ingreso,
            ]);

            DB::commit();

            return response()->json(['message' => 'Empleado actualizado correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }


    public function destroy($id)
{
    try {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete(); // Se eliminará también el usuario si está en cascada

        return response()->json(['message' => 'Empleado eliminado correctamente.']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al eliminar el empleado: ' . $e->getMessage()], 500);
    }
}

public function perfil()
{
    $usuario = Auth::user();

    // Aseguramos que tiene un empleado asociado
    $empleado = Empleado::with('usuario.roles')->where('usuario_id', $usuario->id)->firstOrFail();

    return view('empleados.perfil', compact('empleado'));
}
public function actualizarPerfil(Request $request)
{
    $user = auth()->user();
    $empleado = $user->empleado;

    $request->validate([
        'nombres' => 'required|string|max:100',
        'apellidos' => 'required|string|max:100',
        'telefono' => 'nullable|string|max:20',
        'direccion' => 'nullable|string|max:255',
        'email' => 'required|email|unique:usuarios,email,' . $user->id,
        'password' => 'nullable|min:6|confirmed',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:10', // ✅ validación para foto
    ]);

    DB::beginTransaction();

    try {
        //  Procesar la foto si fue enviada
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');

            // Crear carpeta si no existe
            $ruta = public_path('fotos');
            if (!File::exists($ruta)) {
                File::makeDirectory($ruta, 0755, true);
            }

            // Generar nombre único
            $nombreFoto = uniqid('empleado_') . '.' . $foto->getClientOriginalExtension();
            $foto->move($ruta, $nombreFoto);

            // Eliminar foto anterior si existe
            if ($empleado->foto && File::exists(public_path($empleado->foto))) {
                File::delete(public_path($empleado->foto));
            }

            // Guardar ruta relativa
            $empleado->foto = 'fotos/' . $nombreFoto;
        }

        // Actualizar usuario
        $user->update([
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        // Actualizar empleado
        $empleado->nombre = $request->nombres;
        $empleado->apellido = $request->apellidos;
        $empleado->telefono = $request->telefono;
        $empleado->direccion = $request->direccion;
        $empleado->save();

        DB::commit();

        return response()->json(['message' => 'Perfil actualizado correctamente.']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
    }
}


}
