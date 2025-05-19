<!-- resources/views/empleados/index.blade.php -->
@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Reportes Historicos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Reportes Historicos</li>
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
                        <h3 class="card-title">Listado de Historicos</h3>
                        <div class="card-tools">
                        
                        </div>
                    </div>
                    <div class="card-body">
                        
                        

                        <!-- Formulario para buscar un reporte por código -->
                        <h1>Buscar Reporte por Código</h1>

                        <form id="form-buscar-reporte" class="form-inline mb-3">
                            <input type="text" id="codigo-reporte" class="form-control mr-2" placeholder="Ej: A-00000001" required>
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </form>



                        <!-- Aquí se mostrará la tabla -->
                        <div id="contenedor-tabla"></div>

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
        $('#form-buscar-reporte').on('submit', function (e) {
            e.preventDefault();

            const codigo = $('#codigo-reporte').val().trim();

            if (!codigo) {
                Swal.fire('Error', 'Debe ingresar un código.', 'warning');
                return;
            }

            $.ajax({
                url: `/api/reporte/${codigo}`,
                method: 'GET',
                success: function (response) {
                    if (response.success) {
                        const headers = Array.isArray(response.headers) ? response.headers : JSON.parse(response.headers);
                        const rows = Array.isArray(response.rows) ? response.rows : JSON.parse(response.rows);

                        let tabla = `
                            <h3>Reporte: ${response.codigo}</h3>
                            <h5>${response.nombre}</h5>
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead><tr>`;

                        headers.forEach(header => {
                            tabla += `<th>${header}</th>`;
                        });

                        tabla += `</tr></thead><tbody>`;

                        rows.forEach(row => {
                            tabla += `<tr>`;
                            headers.forEach((header, index) => {
                                const value = row[index] !== undefined ? row[index] : '';
                                tabla += `<td>${value}</td>`;
                            });
                            tabla += `</tr>`;
                        });

                        tabla += `</tbody></table></div>`;

                        $('#contenedor-tabla').html(tabla);

                        const table = $('#example1').DataTable({
                            responsive: false,
                            scrollX: true,
                            lengthChange: true,
                            autoWidth: false,
                            buttons: [
                                {
                                    extend: 'collection',
                                    text: 'Opciones',
                                    buttons: [
                                        { extend: 'copy', text: 'Copiar' },
                                        { extend: 'csv', text: 'CSV' },
                                        { extend: 'excel', text: 'Excel', title: 'Listado de Órdenes' }
                                    ]
                                },
                                { extend: 'colvis', text: 'Visibilidad de Columna' }
                            ],
                            paging: true,
                            searching: true,
                            order: [],
                            info: true,
                            language: {
                                search: "Buscar:",
                                lengthMenu: "Mostrar _MENU_ registros por página",
                                info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                                paginate: {
                                    first: "Primero",
                                    previous: "Anterior",
                                    next: "Siguiente",
                                    last: "Último"
                                },
                            }
                        });

                        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    } else {
                        Swal.fire('No encontrado', response.message, 'warning');
                        $('#contenedor-tabla').empty(); // Limpia resultados anteriores
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Hubo un problema al buscar el reporte.', 'error');
                }
            });
        });
    });
</script>











@endsection
