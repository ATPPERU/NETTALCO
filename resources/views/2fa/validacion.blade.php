<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación 2FA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px;">
        <h4 class="mb-3 text-center">Autenticación de Dos Factores (2FA)</h4>
        <p class="text-muted text-center">Ingresa el código generado por tu app de autenticación.</p>

        <form id="form-2fa">
            <div class="mb-3">
                <label for="one_time_password" class="form-label">Código 2FA</label>
                <input type="text" name="one_time_password" id="one_time_password" maxlength="6" required autofocus>

            </div>
            <button type="submit" class="btn btn-primary w-100">Verificar Código</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#form-2fa').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "/2fa", // Ruta de verificación
                method: "POST",
                data: {
                    one_time_password: $('#one_time_password').val(), // 👈 CORRECTO AHORA
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: "¡Código válido!",
                            icon: "success",
                            confirmButtonText: "Continuar"
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    } else {
                        Swal.fire({
                            title: "Código inválido",
                            text: response.message || "El código no es correcto.",
                            icon: "error",
                            confirmButtonText: "Intentar de nuevo"
                        });
                    }
                },
                error: function(xhr) {
                    let msg = "Ocurrió un error al verificar el código.";
                    if (xhr.responseJSON?.errors?.one_time_password) {
                        msg = xhr.responseJSON.errors.one_time_password[0];
                    }
                    Swal.fire({
                        title: "Error",
                        text: msg,
                        icon: "error"
                    });
                }
            });
        });
    });
</script>

</body>
</html>
