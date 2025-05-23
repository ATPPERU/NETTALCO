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
                       
                            <a href="#" class="btn btn-sm btn-primary mb-3 shadow" data-toggle="modal" data-target="#modalAgregarRol">
                                 Agregar Rol
                            </a>
                        

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
                                            <a href="javascript:void(0);" class="btn btn-sm btn-warning btn-editar-rol mr-1" title="Editar" data-id="{{ $rol->id }}">
                                                <i class="fas fa-edit text-white"></i>
                                            </a>
                                            
                                            <button type="button" class="btn btn-sm btn-danger btn-eliminar-rol" title="Eliminar" data-id="{{ $rol->id }}">
                                                <i class="fas fa-trash-alt text-white"></i>
                                            </button>
                                            
                                            <a href="javascript:void(0);" class="btn btn-sm btn-info btn-permisos-rol" title="Permisos" data-id="{{ $rol->id }}">
                                                <i class="fas fa-shield-alt text-white"></i>
                                            </a>
                                            
                                            <a href="javascript:void(0);" class="btn btn-sm btn-success btn-permisos-rol-full" title="Permisos completos" data-id="{{ $rol->id }}">
                                                <i class="fas fa-lock-open text-white"></i> 
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
  <div class="modal-dialog modal-md" role="document"><!-- modal-md para más pequeño -->
    <form id="formPermisos" method="POST">
      @csrf
      <div class="modal-content">
         <div class="modal-header" style="background-color: #031f3b;">
          <h5 class="modal-title text-white">Permisos del Rol: <span id="nombreRol"></span></h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="rol_id" id="rol_id">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Módulo</th>
                <th class="text-center">Habilitar</th> <!-- texto centrado -->
                <!--<th>Crear</th>
                <th>Editar</th>
                <th>Eliminar</th>-->
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


<div class="modal fade" id="modalPermisosFull" tabindex="-1" role="dialog" aria-labelledby="permisosLabelFull" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form id="formPermisosFull" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header" style="background-color: #031f3b;">
          <h5 class="modal-title text-white">Permisos completos del Rol: <span id="nombreRolFull"></span></h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="rol_id" id="rol_id_full">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Módulo</th>
                <th class="text-center">Ver</th>
                <th class="text-center">Crear</th>
                <th class="text-center">Editar</th>
                <th class="text-center">Eliminar</th>
              </tr>
            </thead>
            <tbody id="tablaPermisosFull">
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
        // Configuración global de Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "1000"
        };

        $('#formCrearRol').on('submit', function (e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('roles.store') }}",
                method: "POST",
                data: formData,
                success: function (response) {
                    $('#modalAgregarRol').modal('hide');

                    toastr.success(response.message, '¡Rol creado!');

                    setTimeout(() => location.reload(), 500);
                },
                error: function (xhr) {
                    let errores = xhr.responseJSON.errors;
                    let mensaje = '';

                    for (let campo in errores) {
                        mensaje += errores[campo][0] + '\n';
                    }

                    toastr.error(mensaje, 'Error');
                }
            });
        });
    });
</script>

<!-- editar -->
<script>
    $(document).ready(function() {

        // Configuración global de Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "100"
        };

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
                    toastr.success(response.message || 'El rol fue editado correctamente.', 'Rol actualizado');
                    setTimeout(() => location.reload());
                },
                error: function (xhr) {
                    let errorText = 'Ocurrió un error.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorText = xhr.responseJSON.message;
                    }
                    toastr.error(errorText, 'Error');
                }
            });
        });
    });
</script>

<!-- eliminar -->
<script>
    $(document).ready(function () {

        // Configuración global de Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "2000"
        };

        $('.btn-eliminar-rol').on('click', function () {
            let rolId = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd', // Bootstrap primary
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
                            toastr.success(response.message || 'Rol eliminado correctamente.', 'Eliminado');
                            setTimeout(() => location.reload(), 2000);
                        },
                        error: function (xhr) {
                            let errorText = 'No se pudo eliminar el rol.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorText = xhr.responseJSON.message;
                            }
                            toastr.error(errorText, 'Error');
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

        // Configuración global de Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "2000"
        };

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
                            const p = permisos[modulo] || {
                                puede_ver: 0,
                                puede_crear: 0,
                                puede_editar: 0,
                                puede_eliminar: 0
                            };

                            const fila = `
                                <tr>
                                    <td>${modulo.charAt(0).toUpperCase() + modulo.slice(1)}</td>
                                    <td class="text-center">
                                        <input type="hidden" name="permisos[${modulo}][ver]" value="0">
                                        <input type="checkbox" name="permisos[${modulo}][ver]" value="1" ${p.puede_ver ? 'checked' : ''}>
                                    </td>
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
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || "Error al procesar los permisos.");
                }
                return data;
            })
            .then(data => {
                $('#modalPermisos').modal('hide');
                toastr.success('Permisos guardados correctamente.');

                setTimeout(() => {
                    location.reload();
                }, 2000);
            })
            .catch(error => {
                toastr.error(error.message || 'Hubo un problema al guardar los permisos.');
                console.error(error);
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

        // Configuración global de Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "2000"
        };

        // Evento click para abrir modal completo
        document.querySelectorAll('.btn-permisos-rol-full').forEach(btn => {
            btn.addEventListener('click', function () {
                const rolId = this.dataset.id;

                fetch(`/roles/${rolId}/permisos`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('rol_id_full').value = rolId;
                        document.getElementById('nombreRolFull').innerText = data.rol_nombre;
                        const permisos = data.permisos || {};
                        const tbody = document.getElementById('tablaPermisosFull');
                        tbody.innerHTML = '';

                        modulos.forEach(modulo => {
                            const p = permisos[modulo] || {
                                puede_ver: 0,
                                puede_crear: 0,
                                puede_editar: 0,
                                puede_eliminar: 0
                            };

                            const fila = `
                                <tr>
                                    <td>${modulo.charAt(0).toUpperCase() + modulo.slice(1)}</td>
                                    <td class="text-center">
                                        <input type="hidden" name="permisos[${modulo}][ver]" value="0">
                                        <input type="checkbox" name="permisos[${modulo}][ver]" value="1" ${p.puede_ver ? 'checked' : ''}>
                                    </td>
                                    <td class="text-center">
                                        <input type="hidden" name="permisos[${modulo}][crear]" value="0">
                                        <input type="checkbox" name="permisos[${modulo}][crear]" value="1" ${p.puede_crear ? 'checked' : ''}>
                                    </td>
                                    <td class="text-center">
                                        <input type="hidden" name="permisos[${modulo}][editar]" value="0">
                                        <input type="checkbox" name="permisos[${modulo}][editar]" value="1" ${p.puede_editar ? 'checked' : ''}>
                                    </td>
                                    <td class="text-center">
                                        <input type="hidden" name="permisos[${modulo}][eliminar]" value="0">
                                        <input type="checkbox" name="permisos[${modulo}][eliminar]" value="1" ${p.puede_eliminar ? 'checked' : ''}>
                                    </td>
                                </tr>`;
                            tbody.insertAdjacentHTML('beforeend', fila);
                        });

                        $('#modalPermisosFull').modal('show');
                    });
            });
        });

        // Evento submit para guardar permisos completos
        document.getElementById('formPermisosFull').addEventListener('submit', function (e) {
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
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || "Error al procesar los permisos.");
                }
                return data;
            })
            .then(data => {
                $('#modalPermisosFull').modal('hide');
                toastr.success('Permisos guardados correctamente.');

                setTimeout(() => {
                    location.reload();
                }, 2000);
            })
            .catch(error => {
                toastr.error(error.message || 'Hubo un problema al guardar los permisos.');
                console.error(error);
            });
        });
    });
</script>





@endsection
