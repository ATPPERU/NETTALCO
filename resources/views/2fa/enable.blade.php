
<!-- resources/views/empleados/index.blade.php -->
@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Activar 2FA</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Activar 2FA</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Activar 2FA</h3>
                        <div class="card-tools">
                        
                        </div>
                    </div>
                    <div class="card-body">
                        
                        
                        <form id="form-2fa" action="{{ route('2fa.enable') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Generar clave 2FA</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
$(document).ready(function () {
    $('#form-2fa').submit(function (e) {
        e.preventDefault();

        Swal.fire({
            icon: 'warning',
            title: '¿Estás seguro?',
            html: 'Si ya tienes un código 2FA, este será <strong>reemplazado</strong> y deberás configurarlo nuevamente.<br><br>¿Deseas continuar?',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary ', // margen derecho para separación
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        })
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('2fa.enable') }}",
                    type: "POST",
                    data: $('#form-2fa').serialize(),
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Clave generada!',
                            html: `<p>Tu clave secreta es: <strong>${response.secret}</strong></p>
                                   <div class="my-3">${response.qr_code_svg}</div>`,
                            showCancelButton: true,
                            confirmButtonText: 'Confirmar 2FA',
                            cancelButtonText: 'Cerrar',
                            customClass: {
                                confirmButton: 'btn btn-primary me-2',
                                cancelButton: 'btn btn-secondary'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('2fa.confirm.form') }}";
                            }
                        });
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo generar la clave 2FA.'
                        });
                    }
                });
            }
        });
    });
});
</script>









@endsection
