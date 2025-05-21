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
                        <h3 class="card-title">Crear Empleados</h3>
                        <div class="card-tools">
                        

                        </div>
                    </div>
                    <div class="card-body">
                                                
                        <form id="form-empleado" action="{{ route('empleados.store') }}" method="POST">

                            @csrf

                            {{-- Sección: Datos Personales --}}
                            <div class="card mb-3">
                                <div class="card-header text-white" style="background-color: #031f3b;">
                                    <h5 class="mb-0"><i class="fas fa-id-card"></i> Datos Personales</h5>
                                </div>

                                <div class="card-body row">
                                    <div class="form-group col-md-6">
                                        <label for="dni"><i class="fas fa-address-card mr-1"></i> DNI</label>
                                        <input type="text" name="dni" class="form-control" required value="{{ old('dni') }}" maxlength="8" inputmode="numeric" pattern="[0-9]*" title="Solo números">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="nombres"><i class="fas fa-user mr-1"></i> Nombres</label>
                                        <input type="text" name="nombres" class="form-control" required value="{{ old('nombres') }}" maxlength="20" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+" title="Solo letras y espacios">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="apellidos"><i class="fas fa-user-tag mr-1"></i> Apellidos</label>
                                        <input type="text" name="apellidos" class="form-control" required value="{{ old('apellidos') }}" maxlength="30" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+" title="Solo letras y espacios">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="telefono"><i class="fas fa-phone-alt mr-1"></i> Teléfono</label>
                                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" maxlength="12" inputmode="numeric" pattern="[0-9]*" title="Solo números">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="direccion"><i class="fas fa-map-marker-alt mr-1"></i> Dirección</label>
                                        <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}" maxlength="40">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="fecha_ingreso"><i class="fas fa-calendar-alt mr-1"></i> Fecha de Ingreso</label>
                                        <input type="date" name="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Sección: Datos de Usuario --}}
                            <div class="card mb-3">
                                <div class="card-header text-white" style="background-color: #031f3b;">
                                    <h5 class="mb-0"><i class="fas fa-user"></i> Datos de Usuario</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="form-group col-md-6">
                                        <label for="usuario"><i class="fas fa-user-circle mr-1"></i> Nombre de Usuario</label>
                                        <input type="text" name="usuario" class="form-control" required value="{{ old('usuario') }}" maxlength="10">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email"><i class="fas fa-envelope mr-1"></i> Correo Electrónico</label>
                                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="password"><i class="fas fa-lock mr-1"></i> Contraseña</label>
                                        <input type="password" name="password" class="form-control"
                                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                                                title="La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo." maxlength="15">

                                    </div>
                                </div>
                            </div>

                            {{-- Sección: Asignar Roles --}}
                            <div class="card mb-3">
                                <div class="card-header text-white" style="background-color: #031f3b;">
                                    <h5 class="mb-0"><i class="fas fa-user-shield"></i> Roles</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="roles"><i class="fas fa-users-cog mr-1"></i> Seleccionar Rol(es)</label>
                                        <select name="roles[]" class="form-control select2" multiple required>
                                            @foreach($roles as $rol)
                                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="text-end">
                                <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar
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
    $(document).ready(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": [
                {
                    extend: 'collection',
                    text: 'Opciones',
                    buttons: [
                        {
                            extend: 'copy',
                            text: 'Copiar', // Cambia el texto del botón Copy a 'Copiar'
                        },
                        {
                            extend: 'csv',
                            text: 'CSV'
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            title: 'Listado de Roles', // Cambia el título del archivo a 'Requisitos'
                        }
                    ]
                },
                {
                    extend: 'colvis',
                    text: 'Visibilidad de Columna', // Cambia el texto del botón colvis a 'Visibilidad de Columna'
                }
            ],
            "paging": true,
            "searching": true,
            "order": [],
            "info": true,
            "language": {
                "search": "Buscar:",
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                "paginate": {
                    "first": "Primero",
                    "previous": "Anterior",
                    "next": "Siguiente",
                    "last": "Último"
                },
            }
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
<!-- validacion de numeros y letras -->
<script>
    document.querySelector('input[name="dni"]').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    document.querySelector('input[name="nombres"]').addEventListener('input', function () {
        this.value = this.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, '');
    });
</script>
<!-- select  -->
<script>
    $(document).ready(function () {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Seleccione roles"
        });
    });
</script>

<!-- agregar -->
<script>
    $(document).ready(function () {
        $('#form-empleado').on('submit', function (e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let formData = new FormData(this);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    Swal.fire({
                        title: 'Guardando...',
                        text: 'Espere un momento',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Registro exitoso!',
                        text: 'El empleado ha sido creado correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('empleados.index') }}";
                    });
                },
                error: function (xhr) {
                    Swal.close();
                    let errores = xhr.responseJSON?.errors || {};

                    let mensaje = "Ocurrió un error al guardar los datos.";
                    if (Object.keys(errores).length) {
                        mensaje = Object.values(errores).map(m => `<li>${m}</li>`).join('');
                        mensaje = `<ul class="text-left">${mensaje}</ul>`;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: mensaje
                    });
                }
            });
        });
    });
</script>

@endsection
