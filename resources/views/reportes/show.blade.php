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
                    <div class="card-header">
                        <h3 class="card-title">Listado de Planillas</h3>
                        <div class="card-tools">
                        <button id="generar-reporte" class="btn btn-success mt-3">Generar Reporte</button>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <h1>Buscar Reporte por Código</h1>

                        <!-- Formulario para buscar un reporte por código -->
                        <form action="{{ route('reporte.buscar') }}" method="GET">
                            <input type="text" name="codigo" placeholder="Ingrese el código del reporte" required>
                            <button type="submit">Buscar</button>
                        </form>

                        <!-- Si existe un reporte encontrado, se muestra -->
                        @if(isset($reporte))
                            <h2>Reporte: {{ $reporte->codigo }}</h2>
                            <h3>Nombre: {{ $reporte->nombre }}</h3>

                            <!-- Mostrar la tabla -->
                            <table>
                                <thead>
                                    <tr>
                                        @foreach ($reporte->headers as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reporte->rows as $row)
                                        <tr>
                                            @foreach ($row as $cell)
                                                <td>{{ $cell }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif(isset($error))
                            <p>{{ $error }}</p>
                        @endif

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
    "responsive": false, // Desactiva el modo responsivo
    "scrollX": true,     // Agrega scroll horizontal
    "lengthChange": true,
    "autoWidth": false,
    "buttons": [
        {
            extend: 'collection',
            text: 'Exportar',
            buttons: [
                { extend: 'copy', text: 'Copiar' },
                { extend: 'csv', text: 'CSV' },
                { extend: 'excel', text: 'Excel', title: 'Listado de Ordenes' }
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
}).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
</script>








@endsection
