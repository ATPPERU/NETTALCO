<!-- resources/views/empleados/index.blade.php -->
@extends('layouts.app')

</style>
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Reportes de WIP Historicos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Reportes de WIP Historicos</li>
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
                        <h3 class="card-title">Listado de WIP Historicos</h3>
                        <div class="card-tools">
                        
                        </div>
                    </div>
                    <div class="card-body">

                        <!-- 🔎 Sección de Búsqueda y Acciones -->
                        <div class="mb-4">
                            <h1>Buscar Reporte por Código</h1>

                            <form id="form-buscar-reporte" class="form-inline mb-3">
                                <input type="text" id="codigo-reporte" class="form-control mr-2" placeholder="Ej: A-00000001" required>
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </form>

                            <button id="btn-columnas" class="btn btn-outline-primary d-none">
                                Seleccionar Columnas
                            </button>
                        </div>

                        <!-- 📊 Sección de Resultados -->
                        <div id="contenedor-tabla"></div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<!-- Modal Seleccionar Columnas -->
<!-- Modal para visibilidad de columnas -->
<div class="modal fade" id="modalColvis" tabindex="-1" role="dialog" aria-labelledby="modalColvisLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg border-0 rounded-3">
      <div class="modal-header" style="background-color: #031f3b;">
        <h5 class="modal-title text-white" id="modalColvisLabel">Seleccionar Columnas</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="colvis-body" style="max-height: 60vh; overflow-y: auto;">
        <input type="text" id="filtro-columnas" class="form-control mb-3" placeholder="Buscar columna...">
        <div class="form-row" id="checkboxes-columnas">
          <div class="form-group col-md-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="col1" checked>
              <label class="form-check-label" for="col1">Columna 1</label>
            </div>
          </div>
          <div class="form-group col-md-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="col2">
              <label class="form-check-label" for="col2">Columna 2</label>
            </div>
          </div>
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
    let table = null;

    function construirModalColumnas() {
        if (!table) return;

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
                    <label class="form-check-label text-truncate d-block" for="col-${index}" title="${title}">
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

    $(document).on('click', '#btn-columnas', function () {
        construirModalColumnas();
        $('#modalColvis').modal('show');
    });

    $(document).on('change', '#colvis-body input[type="checkbox"]', function () {
        const colIndex = parseInt(this.value);
        const visible = this.checked;
        table.column(colIndex).visible(visible);
    });

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
                            <div class="reporte-titulo">
                                <h3>Reporte: ${response.codigo}</h3>
                                <h5>${response.nombre}</h5>
                            </div>
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped w-100">
                                    <thead><tr>`;

                        headers.forEach(header => {
                            tabla += `<th>${header}</th>`;
                        });

                        tabla += `</tr></thead><tbody>`;

                        rows.forEach(row => {
                            tabla += `<tr>`;
                            headers.forEach((header, index) => {
                                const value = row[index] !== undefined ? row[index] : '';
                                tabla += `<td class="text-center">${value}</td>`;
                            });
                            tabla += `</tr>`;
                        });

                        tabla += `</tbody></table></div>`;

                        $('#contenedor-tabla').html(tabla);

                        table = $('#example1').DataTable({
                            responsive: false,
                            scrollX: true,
                            lengthChange: true,
                            autoWidth: false,
                            paging: true,
                            searching: true,
                            order: [],
                            info: true,
                            buttons: [
                                {
                                    extend: 'collection',
                                    text: 'Exportar',
                                    buttons: [
                                        { extend: 'copy', text: 'Copiar' },
                                        { extend: 'csv', text: 'CSV' },
                                        { extend: 'excel', text: 'Excel', title: 'Listado de Órdenes' }
                                    ]
                                }
                            ],
                            language: {
                                emptyTable: "Aún no se ha cargado ningún dato",
                                infoEmpty: "Sin datos disponibles",
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
                        const buttonContainer = table.buttons().container();
                        buttonContainer.appendTo('#example1_wrapper .col-md-6:eq(0)');

                        // Agregar botón independiente para columnas con margen aumentado
                        $('<button id="btn-columnas" class="btn btn-secondary ms-4">Seleccionar Columnas</button>')
                            .appendTo(buttonContainer);

                    } else {
                        Swal.fire('No encontrado', response.message, 'warning');
                        $('#contenedor-tabla').empty();
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
