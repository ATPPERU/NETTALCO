@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Datos combinados: JSON + Excel</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Datos combinados: JSON + Excel</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Datos combinados: JSON + Excel</h3>
        <div class="card-tools">
            <button id="guardar-reporte" class="btn btn-success btn-sm">Finalizar WIP</button>
        </div>
    </div>
    <div class="card-body">
        @if(isset($headers) && isset($rows))
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            @foreach($headers as $header)
                                <th style="white-space: nowrap;">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $row)
                            <tr>
                                @foreach($headers as $header)
                                    <td class="text-center"  contenteditable="true">{{ $row[$header] ?? '' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning">
                No hay datos disponibles para mostrar.
            </div>
        @endif
    </div>
</div>


<!-- Modal para visibilidad de columnas -->
<div class="modal fade" id="modalColvis" tabindex="-1" role="dialog" aria-labelledby="modalColvisLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header" style="background-color: #031f3b;">
        <h5 class="modal-title text-white" id="modalColvisLabel">Seleccionar Columnas</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body p-3" style="max-height: 400px; overflow-y: auto;">
        <input type="text" id="filtro-columna" class="form-control mb-3" placeholder="Buscar columna...">
        <div id="colvis-body">
          <!-- Aquí se insertarán dinámicamente los checkboxes -->
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>







<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let table;

    $(document).ready(function () {
        // Inicialización de DataTable
        table = $("#example1").DataTable({
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
                        { extend: 'excel', text: 'Excel', title: 'Listado de Roles' }
                    ]
                },
                {
                    text: 'Visibilidad de Columna',
                    action: function () {
                        construirModalColumnas();
                        $('#modalColvis').modal('show');
                    }
                }
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

        // Guardar reporte
        $('#guardar-reporte').on('click', function () {
            const headers = [];
            $('#tabla-combinada thead th').each(function () {
                headers.push($(this).text());
            });

            const rows = [];
            $('#tabla-combinada tbody tr').each(function () {
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
    });

    // Construye los checkboxes del modal
    function construirModalColumnas() {
        const container = document.getElementById('colvis-body');
        container.innerHTML = '';

        const columnas = table.columns();
        let row = document.createElement('div');
        row.className = 'row';

        columnas.every(function (index) {
            const column = this;
            const title = column.header().innerText || `Columna ${index + 1}`;
            const checked = column.visible() ? 'checked' : '';

            const col = document.createElement('div');
            col.className = 'col-md-4 mb-2';
            col.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="${index}" id="col-${index}" ${checked}>
                    <label class="form-check-label text-truncate" for="col-${index}" title="${title}">
                        ${title}
                    </label>
                </div>
            `;

            row.appendChild(col);

            if ((index + 1) % 3 === 0) {
                container.appendChild(row);
                row = document.createElement('div');
                row.className = 'row';
            }
        });

        if (row.children.length > 0) {
            container.appendChild(row);
        }
    }

    // Manejador de cambio en checkboxes del modal
    $(document).on('change', '#colvis-body input[type="checkbox"]', function () {
        const colIndex = parseInt(this.value);
        const visible = this.checked;
        table.column(colIndex).visible(visible);
    });

    // Filtro para los checkboxes en el modal
    // Filtro para los checkboxes en el modal
    $(document).on('keyup', '#filtro-columna', function () {
        const filtro = $(this).val().toLowerCase();
        $('#colvis-body .form-check').each(function () {
            const texto = $(this).find('label').text().toLowerCase();
            if (texto.indexOf(filtro) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });




</script>

@endsection
