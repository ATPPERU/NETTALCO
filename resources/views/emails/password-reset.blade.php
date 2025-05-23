{{-- resources/views/emails/password-reset.blade.php --}}
@component('mail::message')

{{-- Logo --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 150px;">
</div>

# 👋 ¡Hola {{ $user->name ?? 'usuario' }}!

Recibimos una solicitud para restablecer tu contraseña.

@component('mail::button', ['url' => $url, 'color' => 'primary'])
🔐 Restablecer contraseña
@endcomponent

Si no solicitaste este cambio, puedes ignorar este mensaje.

---

### 🔗 ¿Tienes problemas con el botón?
Copia y pega este enlace en tu navegador:

[{{ $url }}]({{ $url }})

Gracias por usar nuestra aplicación,<br>
**{{ config('app.name') }}**

@endcomponent
