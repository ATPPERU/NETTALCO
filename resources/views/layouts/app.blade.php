<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">


  <title>Sistema Reporte - V1</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css') }}">
    <!-- CSS de Toastr -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
<!-- Bootstrap 5.3.2 CSS -->





    <style>
      .select2-container .select2-selection--single {
          box-sizing: border-box;
          cursor: pointer;
          display: block;
          height: 34px;
          user-select: none;
          -webkit-user-select: none;
      }

      [class*=sidebar-dark-] {
          background-color: #031f3b;
      }

            .nav-item.menu-open > .nav-link {
          background-color: #007bff !important;
          color: #fff !important;
      }
      [class*=sidebar-dark-] .nav-sidebar>.nav-item>.nav-treeview {
          background-color:rgba(85, 83, 83, 0.4);
      }

      .modal-content {
        border: 1px solid #007bff; /* borde azul más grueso */
        border-radius: 1.0rem; /* un poco más redondeado si quieres */
     }

     .form-check-label {
        word-break: break-word;
        white-space: normal;
    }

    .reporte-titulo {
        margin-bottom: 2rem; /* 3 rem, más espacio */
    }
#colvis-body label {
  display: block; /* o inline-block según diseño */
  cursor: pointer;
  margin-bottom: 0.5rem;
}

#colvis-body label input[type="checkbox"] {
  margin-right: 0.5rem;
}
.ml-11px {
        margin-left: 11px !important;
    }

    </style>
    
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!--
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Inicio</a>
        
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contacto</a>
      </li>
    </ul>
-->
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search 
     

      
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>-->
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div style="text-align: center;">
    <a href="#" class="brand-link">
        <img src="{{ asset('images/123654.png') }}" style="max-width: 200px;" class="brand-text font-weight-light">    
    </a>
</div>


    <div class="sidebar">
        <!-- Información del usuario -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img 
                    src="{{ Auth::user()->empleado && Auth::user()->empleado->foto 
                        ? asset(Auth::user()->empleado->foto) 
                        : asset('adminlte/dist/img/user2-160x160.jpg') }}" 
                    class="img-circle elevation-2" 
                    alt="User Image">
            </div>
            <div class="info">
                
                <a href="#" class="d-block">
               
                <!-- Nombre del empleado o username -->
                {{ Auth::user()->usuario }} 
                
                </a>
                
                <small class="d-block text-muted">
                 {{ Auth::user()->roles->pluck('nombre')->join(', ') }}
                </small>

            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Módulo de Gestión de Usuarios -->
                @if(Auth::user()->roles->pluck('nombre')->contains('Administrador'))
                    <!-- Menú visible solo para administradores -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Gestión de Usuarios<i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (tienePermiso('roles', 'ver'))
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}" class="nav-link">
                                    <i class="fas fa-user-shield nav-icon"></i>
                                    <p>Roles</p>
                                </a>
                            </li>
                            @endif
                            @if (tienePermiso('usuarios', 'ver'))
                            <li class="nav-item">
                                <a href="{{ route('empleados.index') }}" class="nav-link">
                                    <i class="fas fa-user-friends nav-icon"></i>
                                    <p>Usuarios</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif


                <!-- Marcas -->
                @if (tienePermiso('Travis', 'ver') || tienePermiso('Llben', 'ver') || tienePermiso('Lacoste', 'ver'))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Marcas<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (tienePermiso('Travis', 'ver'))
                        <li class="nav-item">
                            <a href="{{ route('orders.index') }}" class="nav-link">
                                <i class="fas fa-truck nav-icon"></i>
                                <p>Travis</p>
                            </a>
                        </li>
                        @endif
                        @if (tienePermiso('Llben', 'ver'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-clipboard-list nav-icon"></i>
                                <p>LLben</p>
                            </a>
                        </li>
                        @endif
                        @if (tienePermiso('Lacoste', 'ver'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-tshirt nav-icon"></i>
                                <p>Lacoste</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif


                <!-- Mi Perfil -->
                <li class="nav-item">
                    <a href="{{ route('perfil') }}" class="nav-link">
                        <i class="fas fa-id-badge nav-icon"></i> <!-- icono para perfil -->
                        <p>Mi Perfil</p>
                    </a>
                </li>

                <!-- Reportes -->
                 @if (tienePermiso('historico', 'ver'))
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-bar"></i> <!-- icono para reportes -->
                            <p>Reportes<i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            
                            <li class="nav-item">
                                <a href="{{ route('reporte.buscar') }}" class="nav-link">
                                    <i class="fas fa-history nav-icon"></i> <!-- icono histórico -->
                                    <p>Historico</p>
                                </a>
                            </li>
                        

                        </ul>
                    </li>
                @endif

                <!-- Cerrar Sesión -->
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt nav-icon text-danger"></i>
                            <p>Cerrar Sesión</p>
                        </a>
                    </form>
                </li>

            </ul>
        </nav>



        <!-- /.sidebar-menu -->
    </div>

</aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      STRM V.1
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2025 <a href="https://atp.com.pe/" target="_blank" rel="noopener noreferrer">ATP</a>.</strong> | Todos Los Derechos Reservados.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

<!-- DataTables  & Plugins -->
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- Select2 JS -->
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>



<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Bootstrap Switch JS -->
<script src="{{ asset('adminlte/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<!-- JS de Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
</html>
