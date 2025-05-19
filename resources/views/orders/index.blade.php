<!-- resources/views/empleados/index.blade.php -->
@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Planillas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Gestión de Planillas</li>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">📋 Listado de Planillas</h3>
                        <button id="guardar-reporte" class="btn btn-success">
                            <i class="fas fa-save"></i> Generar Reporte
                        </button>
                    </div>

                    <div class="card-body">
                        {{-- Formulario para cargar Excel --}}
                        <div class="mb-4">
                            <h5><i class="fas fa-file-excel text-success"></i> Importar Excel</h5>
                            <form id="form-excel" action="{{ route('importar.excel') }}" method="POST" enctype="multipart/form-data" class="form-inline">
                                @csrf
                                <div class="form-group mr-2">
                                    <input type="file" class="form-control-file" name="archivo_excel" id="archivo_excel" accept=".xlsx,.xls" required>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Cargar Excel
                                </button>
                            </form>
                        </div>

                        {{-- Botón para cargar datos desde la API simulada --}}
                        <div class="mb-4">
                            <h5><i class="fas fa-database text-danger"></i> Datos desde API simulada</h5>
                            <form action="{{ route('cargar.api.simulada') }}" method="GET">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-download"></i> Cargar datos desde API simulada
                                </button>
                            </form>
                        </div>

                        {{-- Tabla con los datos --}}
                        @if(isset($headers) && isset($orders))
                            <div class="table-responsive">
                                <button class="btn btn-outline-secondary mb-3" data-bs-toggle="modal" data-bs-target="#columnToggleModal">
                                    Gestionar Columnas
                                </button>
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            @foreach($headers as $header)
                                                <th>{{ $header }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $row)
                                            <tr data-id="{{ $row[0] }}">
                                                @foreach($row as $value)
                                                    <td contenteditable="true">{{ $value }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>





<div class="modal fade" id="columnToggleModal" tabindex="-1" aria-labelledby="columnToggleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="columnToggleModalLabel">Mostrar/Ocultar Columnas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div id="column-toggles" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-2">
          <!-- Se genera con JavaScript -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
    if ($('#example1').length) {
        const table = $("#example1").DataTable({
            "responsive": false,
            "scrollX": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": [
                {
                    extend: 'collection',
                    text: 'Opciones',
                    buttons: [
                        { extend: 'copy', text: 'Copiar' },
                        { extend: 'csv', text: 'CSV' },
                        { extend: 'excel', text: 'Excel', title: 'Listado de Órdenes' },
                    ]
                },
                { extend: 'colvis', text: 'Visibilidad de Columna' }
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
        });

        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        // Crear los checkboxes al mostrar el modal
        $('#columnToggleModal').on('show.bs.modal', function () {
            const container = $('#column-toggles');
            container.empty(); // Limpiar checkboxes antiguos

            table.columns().every(function (index) {
                const column = this;
                const title = $(column.header()).text().trim();

                container.append(`
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="${index}" id="toggleCol${index}" ${column.visible() ? 'checked' : ''}>
                        <label class="form-check-label" for="toggleCol${index}">
                            ${title || 'Columna ' + (index + 1)}
                        </label>
                    </div>
                `);
            });
        });

        // Cambiar visibilidad al cambiar los checkboxes
        $('#column-toggles').on('change', 'input[type=checkbox]', function () {
            const colIndex = $(this).val();
            const column = table.column(colIndex);
            column.visible($(this).is(':checked'));
        });
    }
});

</script>



<script>
    $('#guardar-reporte').on('click', function () {
        const headers = [];
        $('#example1 thead th').each(function () {
            headers.push($(this).text());
        });

        const rows = [];
        $('#example1 tbody tr').each(function () {
            const row = [];
            $(this).find('td').each(function () {
                row.push($(this).text());
            });
            rows.push(row);
        });

        $.ajax({
            url: '{{ route("reportes.guardar") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                headers: headers,
                rows: rows
            },
            success: function (response) {
                Swal.fire('Guardado', 'Reporte almacenado correctamente con ID ' + response.id, 'success');
            },
            error: function () {
                Swal.fire('Error', 'Ocurrió un problema al guardar el reporte', 'error');
            }
        });
    });
</script>








@endsection
