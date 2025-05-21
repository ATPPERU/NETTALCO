<!-- resources/views/empleados/index.blade.php -->
@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Empleados</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Gestión de Empleados</li>
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
                        <h3 class="card-title">Editar Empleados</h3>
                        <div class="card-tools">
                        

                        </div>
                    </div>
                    <div class="card-body">
                                                
                        <form id="formEditarEmpleado">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id_empleado" value="{{ $empleado->id }}">
                            {{-- Datos Personales --}}
                            <div class="card mb-3">
                                <div class="card-header text-white" style="background-color: #031f3b;">
                                    <h5><i class="fas fa-id-card"></i> Datos Personales</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="form-group col-md-6">
                                        <label>DNI</label>
                                        <input type="text" name="dni" class="form-control" required value="{{ old('dni', $empleado->dni) }}" maxlength="8">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Nombres</label>
                                        <input type="text" name="nombres" class="form-control" required value="{{ old('nombres', $empleado->nombre) }}" maxlength="20" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+" title="Solo letras y espacios">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Apellidos</label>
                                        <input type="text" name="apellidos" class="form-control" required value="{{ old('apellidos', $empleado->apellido) }}" maxlength="30" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+" title="Solo letras y espacios">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Teléfono</label>
                                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $empleado->telefono) }}" maxlength="12" inputmode="numeric" pattern="[0-9]*" title="Solo números">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Dirección</label>
                                        <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $empleado->direccion) }}" maxlength="40">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Fecha de Ingreso</label>
                                        <input type="date" name="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso', $empleado->fecha_ingreso) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Datos de Usuario --}}
                            <div class="card mb-3">
                                <div class="card-header text-white" style="background-color: #031f3b;">
                                    <h5><i class="fas fa-user"></i> Datos de Usuario</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="form-group col-md-6">
                                        <label>Usuario</label>
                                        <input type="text" name="usuario" class="form-control" required value="{{ old('usuario', $empleado->usuario->usuario) }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" required value="{{ old('email', $empleado->usuario->email) }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Nueva Contraseña (opcional)</label>
                                        <input type="password" name="password" class="form-control"
                                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                                                title="La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo." maxlength="15">

                                    </div>
                                </div>
                            </div>

                            {{-- Roles --}}
                            <div class="card mb-3">
                                <div class="card-header text-white" style="background-color: #031f3b;">
                                    <h5><i class="fas fa-user-shield"></i> Roles</h5>
                                </div>
                                <div class="card-body">
                                    <select name="roles[]" class="form-control select2" multiple required>
                                        @foreach($roles as $rol)
                                            <option value="{{ $rol->id }}" {{ $empleado->usuario->roles->pluck('id')->contains($rol->id) ? 'selected' : '' }}>
                                                {{ $rol->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="text-end">
                                <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Actualizar
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

<!-- validacion de numeros y letras -->
<script>
    document.querySelector('input[name="dni"]').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    document.querySelector('input[name="nombres"]').addEventListener('input', function () {
        this.value = this.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, '');
    });
</script>
<!-- select-->
<script>
    $(document).ready(function () {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Seleccione roles"
        });
    });
</script>

<!-- editar -->
<script>
    $('#formEditarEmpleado').submit(function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        let empleadoId = $('input[name="id_empleado"]').val();

        $.ajax({
            url: `/empleados/${empleadoId}`,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: response.message
                }).then(() => {
                    window.location.href = "{{ route('empleados.index') }}";
                });
            },
            error: function(xhr) {
                let mensaje = xhr.responseJSON?.message || 'Error al actualizar';
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: mensaje
                });
            }
        });
    });
</script>


@endsection
