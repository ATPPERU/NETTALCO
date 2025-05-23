
@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Editar Perfil</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Editar Perfil</li>
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
                        <h3 class="card-title">Editar Perfil</h3>
                        <div class="card-tools">
                        


                        </div>
                    </div>
                    <div class="card-body">
                        
                        <form id="perfil-form" enctype="multipart/form-data" method="POST">

                            @csrf
                            <div class="card-body">

                                <!-- Sección: Datos personales -->
                                <div class="mb-4">
                                    <h5 class="text-primary border-bottom pb-2"><i class="fas fa-user mr-1"></i> Información Personal</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombres">Nombres</label>
                                                <input type="text" name="nombres" class="form-control" value="{{ $empleado->nombre }}" maxlength="20" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="apellidos">Apellidos</label>
                                                <input type="text" name="apellidos" class="form-control" value="{{ $empleado->apellido }}" maxlength="20" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="telefono">Teléfono</label>
                                                <input type="text" name="telefono" class="form-control" value="{{ $empleado->telefono }}" maxlength="12">
                                            </div>
                                            <div class="form-group">
                                                <label for="direccion">Dirección</label>
                                                <input type="text" name="direccion" class="form-control" value="{{ $empleado->direccion }}" maxlength="40">
                                            </div>
                                        </div>


                                        <!-- Segunda columna -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Correo Electrónico</label>
                                                <input type="email" name="email" class="form-control" value="{{ $empleado->usuario->email }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="password">Nueva Contraseña (opcional)</label>
                                                <input type="password" name="password" class="form-control"
                                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                                                title="La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo." maxlength="15">

                                            </div>

                                            <div class="form-group">
                                                <label for="password_confirmation">Confirmar Contraseña</label>
                                                <input type="password" name="password_confirmation" class="form-control" maxlength="15">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="foto">Foto de Perfil</label>
                                            <input type="file" name="foto" class="form-control-file" accept="image/*">
                                            @if($empleado->foto)
                                                <div class="mt-2">
                                                    <img src="{{ asset($empleado->foto) }}" alt="Foto actual" style="max-width: 180px;" class="img-thumbnail">
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                <!-- Sección: Seguridad -->
                                <div class="mt-4 pt-3 border-top">
                                    <h5 class="text-primary mb-3"><i class="fas fa-shield-alt mr-1"></i> Seguridad</h5>
                                    <div class="form-group">
                                        <a href="{{ route('2fa.enable.form') }}" class="btn btn-outline-dark">
                                            <i class="fas fa-lock mr-1"></i> Activar verificación en dos pasos (2FA)
                                        </a>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Actualizar Perfil
                                </button>
                            </div>
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
    $('#perfil-form').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this); // ✅ permite enviar archivos

    $.ajax({
        url: "{{ route('perfil.actualizar') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            toastr.success(response.message || 'Perfil actualizado con éxito');
        },
        error: function (xhr) {
            let errors = xhr.responseJSON?.errors;
            let msg = 'Ocurrió un error inesperado.';
            if (errors) {
                msg = Object.values(errors).flat().join('<br>');
            } else if (xhr.responseJSON?.message) {
                msg = xhr.responseJSON.message;
            }

            toastr.error(msg);
        }
    });
});

</script>







@endsection
