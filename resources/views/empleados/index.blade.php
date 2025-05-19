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
                        <a href="{{ route('empleados.create') }}" class="btn btn-sm btn-primary mb-3 shadow">
                            <i class="fas fa-plus-circle mr-1"></i> Agregar Usuario
                        </a>


                        </div>
                    </div>
                    <div class="card-body">
                        
                        
                        <table id="example1" class="table table-bordered table-hover">
                            <thead >
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($empleados as $empleado)
                                    <tr>
                                        <td>{{ $empleado->id }}</td>
                                        <td>{{ $empleado->nombre }} {{ $empleado->apellido }}</td>
                                        <td>{{ $empleado->usuario->usuario ?? '-' }}</td>
                                        <td>{{ $empleado->usuario->email ?? '-' }}</td>
                                        <td>
                                            @foreach($empleado->usuario->roles as $rol)
                                                <span class="badge badge-info">{{ $rol->nombre }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('empleados.show', $empleado->id) }}" class="btn btn-info btn-sm" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-danger btn-sm btn-eliminar-empleado"
                                                    data-id="{{ $empleado->id }}"
                                                    title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>

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


<script>
    $(document).on('click', '.btn-eliminar-empleado', function () {
        let empleadoId = $(this).data('id');

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
                    url: `/empleados/${empleadoId}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.message
                        }).then(() => {
                            location.reload(); // O eliminar la fila con JS si prefieres
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Ocurrió un error inesperado.'
                        });
                    }
                });
            }
        });
    });
</script>





@endsection
