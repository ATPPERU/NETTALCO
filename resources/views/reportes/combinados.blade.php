@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">📋 Datos combinados: JSON + Excel</h3>
        <div class="card-tools">
            <button id="guardar-reporte" class="btn btn-success btn-sm">💾 Guardar Reporte</button>
        </div>
    </div>
    <div class="card-body">
        @if(isset($headers) && isset($rows))
            <div class="table-responsive">
                <table id="tabla-combinada" class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            @foreach($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $row)
                            <tr>
                                @foreach($headers as $header)
                                    <td contenteditable="true">{{ $row[$header] ?? '' }}</td>
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




<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $("#tabla-combinada").DataTable({
            "responsive": false,
            "scrollX": true,
            autoWidth: false,
            paging: true,
            ordering: true,
            searching: true,
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
            },
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'collection',
                    text: '📁 Exportar',
                    buttons: [
                        { extend: 'copy', text: 'Copiar' },
                        { extend: 'csv', text: 'CSV' },
                        { extend: 'excel', text: 'Excel', title: 'Reporte Combinado' }
                    ]
                },
                { extend: 'colvis', text: '👁️ Columnas' }
            ]
        });
    });

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
</script>
@endsection
