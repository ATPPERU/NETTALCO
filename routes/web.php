<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\ModuloController;

// Página principal: redirige a login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas para login/logout (solo invitados)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    //Rutas de recuperación de contraseña
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // 2FA - Mostrar formulario para activar 2FA
    Route::get('/2fa/enable', [TwoFactorController::class, 'showEnableForm'])->name('2fa.enable.form');

    // 2FA - Procesar activación 2FA
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable2FA'])->name('2fa.enable');

    // 2FA - Mostrar formulario para confirmar código 2FA
    Route::get('/2fa/confirm', [TwoFactorController::class, 'showConfirmForm'])->name('2fa.confirm.form');

    // 2FA - Confirmar código 2FA (POST)
    Route::post('/2fa/confirm', [TwoFactorController::class, 'confirm2FA'])->name('2fa.confirm');

    // Órdenes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/simular-api', [OrderController::class, 'simularApi'])->name('simular.api.orders');
    Route::get('/orders/cargar-api', [OrderController::class, 'cargarDesdeApiSimulada'])->name('cargar.api.simulada');
    Route::post('/orders/update-field', [OrderController::class, 'updateField'])->name('orders.update-field');
    Route::post('/reporte/store', [OrderController::class, 'store'])->name('reporte.store');

    // Reportes
    Route::get('/reporte/buscar', [ReporteController::class, 'searchForm'])->name('reporte.buscar');
    Route::get('/reporte/buscar/resultados', [ReporteController::class, 'search'])->name('reporte.buscar.resultados');
    Route::post('/reportes/guardar', [ReporteController::class, 'guardar'])->name('reportes.guardar');
    Route::post('/importar-excel', [ReporteController::class, 'importarExcel'])->name('importar.excel');
    Route::get('/api/reporte/{codigo}', [ReporteController::class, 'buscarPorCodigo']);

    // Recursos
    Route::get('/roles/{id}/permisos', [RolController::class, 'getPermisos']);
Route::post('/roles/{id}/permisos', [RolController::class, 'guardarPermisos']);

    Route::resource('roles', RolController::class);
    Route::get('/perfil', [EmpleadoController::class, 'perfil'])->name('perfil');
    Route::post('/perfil', [EmpleadoController::class, 'actualizarPerfil'])->name('perfil.actualizar');
    Route::resource('empleados', EmpleadoController::class);
 

        



});

// Rutas 2FA para usuarios autenticados pero que deben verificar 2FA después del login
Route::middleware(\App\Http\Middleware\TwoFactorMiddleware::class)->group(function () {
    // Mostrar formulario para ingresar código 2FA
    Route::get('/2fa', [TwoFactorController::class, 'show2faForm'])->name('2fa.verify.form');

    // Verificar código 2FA
    Route::post('/2fa', [TwoFactorController::class, 'verify2fa'])->name('2fa.verify');
});
