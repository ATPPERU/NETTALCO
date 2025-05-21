<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('tienePermiso')) {
    function tienePermiso(string $modulo, string $accion): bool
    {
        $usuario = Auth::user();
        if (!$usuario) return false;

        // Carga roles y permisos si no están cargados
        if (!$usuario->relationLoaded('roles')) {
            $usuario->load('roles.permisos');
        }

        foreach ($usuario->roles as $rol) {
            $permiso = $rol->permisos->firstWhere('modulo', $modulo);
            if ($permiso) {
                $tiene = match($accion) {
                    'ver' => (bool) $permiso->puede_ver,
                    'crear' => (bool) $permiso->puede_crear,
                    'editar' => (bool) $permiso->puede_editar,
                    'eliminar' => (bool) $permiso->puede_eliminar,
                    default => false,
                };
                if ($tiene) {
                    return true;
                }
            }
        }

        return false;
    }
}