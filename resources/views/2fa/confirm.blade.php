
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
                        <h3 class="card-title">Validar 2FA</h3>
                        <div class="card-tools">
                        
                        </div>
                    </div>
                    <div class="card-body">
                        
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('2fa.confirm') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="code">Ingresa el código que genera tu app 2FA</label>
                                <input type="text" name="code" id="code" class="form-control" required autofocus>
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">Confirmar 2FA</button>
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
$(document).ready(function() {
    $('#form-2fa').submit(function(e) {
        e.preventDefault(); // evitar que recargue la página

        $.ajax({
            url: "{{ route('2fa.enable') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Clave generada!',
                    html: `<p>Tu clave secreta es: <strong>${response.secret}</strong></p>
                           <div>${response.qr_code_svg}</div>`,
                    confirmButtonText: 'Cerrar'
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo generar la clave 2FA.'
                });
            }
        });
    });
});
</script>






@endsection
