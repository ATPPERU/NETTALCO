@component('mail::message')

{{-- Logo centrado --}}
<div style="text-align: center; margin-bottom: 30px;">
    <img src="https://utp.hiringroomcampus.com/assets/media/utp/company_641c7c1786cb2ac7c60f1c82.png" alt="Logo {{ config('app.name') }}" style="width: 160px; border-radius: 8px;">
</div>


# ¡Hola {{ $user->name ?? 'usuario' }}! 

Recibimos una solicitud para restablecer tu contraseña en NETTALCO.

@component('mail::button', ['url' => $url, 'color' => 'success'])
 Restablecer contraseña
@endcomponent

Si no realizaste esta solicitud, puedes ignorar este mensaje sin problema.

---

### 🔗 ¿Problemas con el botón?
Copia y pega este enlace en tu navegador:

[{{ $url }}]({{ $url }})

---

Gracias por confiar en nosotros,<br>
**El equipo de NETTALCO**

@endcomponent
