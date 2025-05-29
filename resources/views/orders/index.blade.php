<!-- resources/views/empleados/index.blade.php -->
@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Optional: Bootstrap Icons (si usas <i class="bi ...">) -->
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Llbean</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Gestión de Llbean</li>
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
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h3 class="card-title mb-0">Listado de Llbean</h3>
                            <button id="guardar-reporte" class="btn btn-success">
                             Finalizar WIP
                            </button>
                        </div>
                    </div>



                    <div class="card-body">

                        <!-- Formulario para cargar Excel y datos desde API -->
                        <div class="row mb-4">
                            <!-- Columna: Datos desde API -->
                            <div class="col-md-6 mb-4">
                                <h5><i class="fas fa-database text-info"></i> Datos desde API</h5>
                                <form action="{{ route('cargar.api.simulada') }}" method="GET">
                                    <button type="submit" class="btn btn-info">Generar Datos</button>
                                </form>
                            </div>

                            <!-- Columna: Importar Excel -->
                            <div class="col-md-6 mb-4">
                                <h5><i class="fas fa-file-excel text-success"></i> Importar Excel</h5>
                                <form id="form-excel" action="{{ route('importar.excel') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="d-flex align-items-end gap-2">
                                        <div class="flex-grow-1">
                                            <input type="file" class="form-control" name="archivo_excel" id="archivo_excel" accept=".xlsx,.xls" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Cargar Excel</button>
                                    </div>
                                </form>
                            </div>

                           
                        </div>


                        <!-- Tabla de datos -->
                        @if(isset($headers) && isset($orders))
                            <div class="table-responsive mt-4">
                                
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead >
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
                                                    <td class="text-center" contenteditable="true">{{ $value }}</td>
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
  <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4 border-0">
      
      <!-- Encabezado -->
      <div class="modal-header" style="background-color: #031f3b;">
        <h5 class="modal-title d-flex align-items-center gap-2 text-white" id="columnToggleModalLabel">
          <i class="bi bi-layout-three-columns"></i>
          Mostrar u Ocultar Columnas
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Cuerpo -->
      <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
         <input type="text" id="filtro-columnas" class="form-control mb-3" placeholder="Buscar columna...">
        <div id="column-toggles" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
          <!-- Checkboxes generados dinámicamente -->
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer bg-light rounded-bottom-4 d-flex justify-content-between">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <!-- <button type="button" class="btn btn-primary" id="btnGuardarColumnas">Guardar selección</button> -->
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
            responsive: false,
            scrollX: true,
            lengthChange: true,
            autoWidth: false,
            buttons: [
                {
                    extend: 'collection',
                    text: 'Exportar',
                    buttons: [
                        { extend: 'copy', text: 'Copiar' },
                        { extend: 'csv', text: 'CSV' },
                        { extend: 'excel', text: 'Excel', title: 'Listado de Órdenes' },
                    ]
                },
                { extend: 'colvis', text: 'Visibilidad de Columna' }
            ],
            paging: true,
            searching: true,
            order: [],
            info: true,
            language: {
                emptyTable: "Aún no se ha cargado ningún dato",
                infoEmpty: "No hay datos disponibles. Por favor carga un archivo o genera datos.",
                info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros por página",
                paginate: {
                    first: "Primero",
                    previous: "Anterior",
                    next: "Siguiente",
                    last: "Último"
                }
            }
        });

        // Insertar los botones en el DOM
        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        // Agregar el botón "Gestionar Columnas"
        $('<button class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#columnToggleModal">Gestionar Columnas</button>')
            .appendTo('#example1_wrapper .col-md-6:eq(0)');

        // Crear checkboxes al abrir el modal
        $('#columnToggleModal').on('show.bs.modal', function () {
            const container = $('#column-toggles');
            container.empty();
            $('#filtro-columnas').val(''); // Limpiar filtro al abrir

            table.columns().every(function (index) {
                const column = this;
                const title = $(column.header()).text().trim() || `Columna ${index + 1}`;
                const checked = column.visible() ? 'checked' : '';

                const checkboxHTML = `
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="${index}" id="toggleCol${index}" ${checked}>
                            <label class="form-check-label text-truncate" for="toggleCol${index}" title="${title}">
                                ${title}
                            </label>
                        </div>
                    </div>
                `;

                container.append(checkboxHTML);
            });
        });

        // Manejar visibilidad de columnas
        $('#column-toggles').on('change', 'input[type=checkbox]', function () {
            const colIndex = $(this).val();
            const column = table.column(colIndex);
            column.visible($(this).is(':checked'));
        });

        // Filtro para el input de búsqueda de columnas
        $('#filtro-columnas').on('keyup', function () {
            const filtro = $(this).val().toLowerCase();

            $('#column-toggles .form-check').each(function () {
                const texto = $(this).find('label').text().toLowerCase();
                if (texto.indexOf(filtro) > -1) {
                    $(this).parent().show(); // Mostrar el div.col contenedor
                } else {
                    $(this).parent().hide(); // Ocultar el div.col contenedor
                }
            });
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
                Swal.fire({
                    title: 'Guardado',
                    text: 'Reporte almacenado correctamente con ID ' + response.id,
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            },
            error: function () {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un problema al guardar el reporte',
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            }
        });
    });
</script>









@endsection
