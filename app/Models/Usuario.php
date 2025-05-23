<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPasswordNotification;
class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'usuario',
        'email',
        'password',
        'activo',
        'two_factor_secret',          // Campo para el secreto 2FA
        'two_factor_enabled_at',      // Timestamp para saber cuándo se activó 2FA (opcional)
    ];

    protected $hidden = [
        'usuario',
        'email',
        'password',
        'two_factor_secret',          // Ocultar el secreto 2FA al serializar
    ];

    // Relación con Roles (muchos a muchos)
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_rol', 'usuario_id', 'rol_id');
    }

    // Relación uno a uno con Empleado
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'usuario_id');
    }

    // Mutator para encriptar la contraseña
    //public function setPasswordAttribute($value)
    //{
        //$this->attributes['password'] = bcrypt($value);
    //}

    // Helper para verificar si el usuario tiene 2FA activado
    public function hasTwoFactorEnabled()
    {
        return !empty($this->two_factor_secret);
    }
    
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
