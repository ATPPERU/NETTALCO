<!-- resources/views/empleados/index.blade.php -->
@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Roles</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Gestión de Roles</li>
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
                        <h3 class="card-title">Listado de Roles</h3>
                        <div class="card-tools">
                       @if (tienePermiso('roles', 'crear'))
                            <a href="#" class="btn btn-sm btn-primary mb-3 shadow" data-toggle="modal" data-target="#modalAgregarRol">
                                <i class="fas fa-plus-circle mr-1"></i> Agregar Rol
                            </a>
                        @endif

                        </div>
                    </div>
                    <div class="card-body">
                        
                        <table id="example1" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del Rol</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roless as $rol)
                                    <tr>
                                        <td>{{ $rol->id }}</td>
                                        <td>{{ $rol->nombre }}</td>
                                        <td class="text-center">
                                            @if (tienePermiso('roles', 'editar'))
                                            <a href="javascript:void(0);" class="btn btn-sm btn-warning btn-editar-rol mr-1" title="Editar" data-id="{{ $rol->id }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            @if (tienePermiso('roles', 'eliminar'))
                                            <button type="button" class="btn btn-sm btn-danger btn-eliminar-rol" title="Eliminar" data-id="{{ $rol->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            @endif
                                            <a href="javascript:void(0);" class="btn btn-sm btn-info btn-permisos-rol" title="Permisos" data-id="{{ $rol->id }}">
                                                <i class="fas fa-shield-alt"></i>
                                            </a>


                                        </td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAgregarRol" tabindex="-1" role="dialog" aria-labelledby="modalAgregarRolLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formCrearRol">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #031f3b;">
                    <h5 class="modal-title text-white"  id="modalAgregarRolLabel">Agregar Rol</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre del Rol</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required maxlength="15">
                    </div>
                    
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Rol -->
<div class="modal fade" id="modalEditarRol" tabindex="-1" role="dialog" aria-labelledby="editarRolLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="formEditarRol">
      @csrf
      @method('PUT')
      <input type="hidden" id="editarRolId">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #031f3b;">
            <h5 class="modal-title text-white">Editar Rol</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="editarNombre">Nombre del Rol</label>
            <input type="text" class="form-control" id="editarNombre" name="nombre" required maxlength="15">
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Modal: Asignar Permisos al Rol -->

<div class="modal fade" id="modalPermisos" tabindex="-1" role="dialog" aria-labelledby="permisosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form id="formPermisos" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Permisos del Rol: <span id="nombreRol"></span></h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="rol_id" id="rol_id">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Módulo</th>
                <th>Ver</th>
                <th>Crear</th>
                <th>Editar</th>
                <th>Eliminar</th>
              </tr>
            </thead>
            <tbody id="tablaPermisos">
              {{-- Filas generadas por JS --}}
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar Permisos</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </form>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- tabla -->
<script>
    $(document).ready(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": [
                {
                    extend: 'collection',
                    text: 'Exportar',
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
<!-- agregar -->
<script>
    $(document).ready(function () {
        $('#formCrearRol').on('submit', function (e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('roles.store') }}",
                method: "POST",
                data: formData,
                success: function (response) {
                    $('#modalAgregarRol').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Rol creado!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                },
                error: function (xhr) {
                    let errores = xhr.responseJSON.errors;
                    let mensaje = '';

                    for (let campo in errores) {
                        mensaje += errores[campo][0] + '\n';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: mensaje
                    });
                }
            });
        });
    });
</script>
<!-- editar -->
<script>
    $(document).ready(function() {

        // Abrir modal y cargar datos del rol
        $('.btn-editar-rol').on('click', function () {
            let rolId = $(this).data('id');
            $.get("/roles/" + rolId + "/edit", function (data) {
                $('#editarRolId').val(data.id);
                $('#editarNombre').val(data.nombre);
               
                $('#modalEditarRol').modal('show');
            });
        });

        // Enviar actualización por AJAX
        $('#formEditarRol').on('submit', function (e) {
            e.preventDefault();
            let id = $('#editarRolId').val();
            let formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT',
                nombre: $('#editarNombre').val()
            };

            $.ajax({
                url: "/roles/" + id,
                method: 'POST',
                data: formData,
                success: function (response) {
                    $('#modalEditarRol').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Rol actualizado',
                        text: response.message || 'El rol fue editado correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                },
                error: function (xhr) {
                    let errorText = 'Ocurrió un error.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorText = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorText
                    });
                }
            });
        });
    });
</script>
<!-- eliminar -->
<script>
    $(document).ready(function () {

        $('.btn-eliminar-rol').on('click', function () {
            let rolId = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "/roles/" + rolId,
                        method: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: 'DELETE'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: response.message || 'Rol eliminado correctamente',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        },
                        error: function (xhr) {
                            let errorText = 'No se pudo eliminar el rol.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorText = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorText
                            });
                        }
                    });

                }
            });
        });

    });

</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const modulos = [
        'roles',
        'usuarios',
        
        'Llben',
        'Travis',
        'Lacoste',
        'historico',
        'activar_2fa'
    ];

    // Evento click en el botón "Permisos"
    document.querySelectorAll('.btn-permisos-rol').forEach(btn => {
        btn.addEventListener('click', function () {
            const rolId = this.dataset.id;

            fetch(`/roles/${rolId}/permisos`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('rol_id').value = rolId;
                    document.getElementById('nombreRol').innerText = data.rol_nombre;
                    const permisos = data.permisos || {};
                    const tbody = document.getElementById('tablaPermisos');
                    tbody.innerHTML = '';

                    modulos.forEach(modulo => {
                        const p = permisos[modulo] || { puede_ver: 0, puede_crear: 0, puede_editar: 0, puede_eliminar: 0 };

                        const fila = `
                            <tr>
                                <td>${modulo.charAt(0).toUpperCase() + modulo.slice(1)}</td>
                                <td><input type="checkbox" name="permisos[${modulo}][ver]" ${p.puede_ver ? 'checked' : ''}></td>
                                <td><input type="checkbox" name="permisos[${modulo}][crear]" ${p.puede_crear ? 'checked' : ''}></td>
                                <td><input type="checkbox" name="permisos[${modulo}][editar]" ${p.puede_editar ? 'checked' : ''}></td>
                                <td><input type="checkbox" name="permisos[${modulo}][eliminar]" ${p.puede_eliminar ? 'checked' : ''}></td>
                            </tr>`;
                        tbody.insertAdjacentHTML('beforeend', fila);
                    });

                    $('#modalPermisos').modal('show');
                });
        });
    });

    // Evento submit del formulario de permisos
    document.getElementById('formPermisos').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const rolId = formData.get('rol_id');

        fetch(`/roles/${rolId}/permisos`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error("Error en la respuesta del servidor");
            return response.json();
        })
        .then(data => {
            $('#modalPermisos').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Permisos actualizados',
                text: 'Los permisos fueron guardados correctamente.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();  // Recarga la página después de cerrar la alerta
            });
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al guardar los permisos.',
            });
            console.error(error);
        });
    });


});
</script>




@endsection
