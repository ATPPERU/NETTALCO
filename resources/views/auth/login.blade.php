<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRTM - V1</title>
    <!-- AdminLTE Styles -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- SweetAlert2 Styles -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#">
            
        </a>
    </div>

   <div class="login-box">
    <div class="login-logo mb-3">
        <img src="https://utp.hiringroomcampus.com/assets/media/utp/company_641c7c1786cb2ac7c60f1c82.png" alt="Logo UTP" style="max-width: 280px;">
        <h4 class="mt-2"><b>Iniciar Sesión</b></h4>
    </div>

    <div class="card shadow">
        <div class="card-body login-card-body">
            <p class="login-box-msg text-muted">Ingresa tus credenciales </p>

           <form id="loginForm" action="{{ route('login') }}" method="POST">
                @csrf

                {{-- Email --}}
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                {{-- Password --}}
                <div class="input-group mb-4">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="row">
                    <div class="col-12 mb-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            Acceder
                        </button>
                    </div>
                    <div class="col-12 text-center">
                        <a href="{{ route('password.request') }}">Olvidé mi clave</a>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

</div>

<!-- AdminLTE Scripts -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#loginForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: "{{ route('login') }}",
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    if (response.two_factor_required) {
                        // Redirigir a formulario 2FA
                        window.location.href = response.redirect_url;
                        return;
                    }
                    Swal.fire({
                        title: response.welcome_message,
                        icon: "success",
                        confirmButtonText: "Ir a Página Principal",
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = response.redirect_url;
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.message,
                        icon: "error",
                        confirmButtonText: "Aceptar",
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                }
            },
            error: function(xhr) {
                var errorMessage = 'Error en la solicitud.';
                if(xhr.responseJSON && xhr.responseJSON.errors){
                    errorMessage = '';
                    $.each(xhr.responseJSON.errors, function(key, messages){
                        errorMessage += messages.join(', ') + '\n';
                    });
                }
                Swal.fire({
                    title: "Error",
                    text: errorMessage,
                    icon: "error",
                    confirmButtonText: "Aceptar",
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            }
        });
    });
});
</script>







</body>
</html>
