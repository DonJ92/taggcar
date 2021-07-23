<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>AdminLTE 3 | Dashboard 2</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker3.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}"/>
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
  <!-- Customize style -->
  <link rel="stylesheet" href="{{ asset('assets/css/admin_custom.css') }}">

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
</head>
<script>
  var _token = "{{csrf_token()}}";
</script>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('dashboard')}}" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-danger navbar-badge">{{$Icount}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          @foreach($customers as $customer)
          <a href="{{url('customer/driverDetail')}}/{{$customer->id}}" class="dropdown-item">
            <div class="media">
              <img src="{{ correctProImgPath($customer->avatar_url) }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  {{$customer->name}}
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> {{$customer->created_at}}</p>
              </div>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          @endforeach
          
          <a href="{{url('customer/drivers')}}" class="dropdown-item dropdown-footer">See All requests</a>
        </div>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fas fa-th-large"></i></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="nav-icon fas fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="{{url('/logout')}}" class="dropdown-item dropdown-footer">Log out</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('dashboard')}}" class="brand-link">
      <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">BACKEND</span>
    </a>

    <!-- Sidebar -->
    @if(Auth::user())
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ correctProImgPath(Auth::user()->avatar_url) }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{url('/dashboard')}}" {!! (Request::is('dashboard') ? 'class="nav-link active"' : 'class="nav-link"') !!}>
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
            
          </li>
          <li {!! (Request::is('admins') || Request::is('customer/drivers') || Request::is('customer/passengers') ? 'class="nav-item has-treeview menu-open"' : 'class="nav-item has-treeview"') !!}>
            <a href="#" {!! (Request::is('customer/drivers') || Request::is('customer/passengers') ? 'class="nav-link has-treeview active"' : 'class="nav-link has-treeview"') !!}>
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('admins')}}" {!! (Request::is('admins') ? 'class="nav-link active"' : 'class="nav-link"') !!}>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Admins</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('customer/drivers')}}" {!! (Request::is('customer/drivers') ? 'class="nav-link active"' : 'class="nav-link"') !!}>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Drivers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('customer/passengers')}}" {!! (Request::is('customer/passengers') ? 'class="nav-link active"' : 'class="nav-link"') !!}>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Passengers</p>
                </a>
              </li>
            </ul>
          </li>
          <li {!! (Request::is('trips') || Request::is('bookings') ? 'class="nav-item has-treeview menu-open"' : 'class="nav-item has-treeview"') !!}>
            <a href="#" {!! (Request::is('trips') || Request::is('bookings') ? 'class="nav-link has-treeview active"' : 'class="nav-link has-treeview"') !!}>
              <i class="nav-icon fas fa-car"></i>
              <p>
                Trip & Booking
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('trips')}}" {!! (Request::is('trips') ? 'class="nav-link active"' : 'class="nav-link"') !!}>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Trips</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('bookings')}}" {!! (Request::is('bookings') ? 'class="nav-link active"' : 'class="nav-link"') !!}>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bookings</p>
                </a>
              </li>
            </ul>
          </li>
          <li {!! (Request::is('city/cities') || Request::is('city/places') ? 'class="nav-item has-treeview menu-open"' : 'class="nav-item has-treeview"') !!}>
            <a href="#" {!! (Request::is('city/cities') || Request::is('city/places') ? 'class="nav-link has-treeview active"' : 'class="nav-link has-treeview"') !!}>
              <i class="nav-icon fas fa-th"></i>
              <p>
                City & Place
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('city/cities')}}" {!! (Request::is('city/cities') ? 'class="nav-link active"' : 'class="nav-link"') !!}>
                  <i class="far fa-circle nav-icon"></i>
                  <p>City</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('city/places')}}" {!! (Request::is('city/places') ? 'class="nav-link active"' : 'class="nav-link"') !!}>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Place</p>
                </a>
              </li>
            </ul>
          </li>
          <li {!! (Request::is('ride/rideLists') || Request::is('ride/setting') ? 'class="nav-item has-treeview menu-open"' : 'class="nav-item has-treeview"') !!}>
            <a href="#" {!! (Request::is('ride/rideLists') || Request::is('ride/to_setting') ? 'class="nav-link has-treeview active"' : 'class="nav-link has-treeview"') !!}>
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Rides
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('ride/rideLists')}}" {!! (Request::is('ride/rideLists') ? 'class="nav-link active"' : 'class="nav-link"') !!}>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rides</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('ride/to_setting')}}" {!! (Request::is('ride/to_setting') ? 'class="nav-link active"' : 'class="nav-link"') !!}>
                  <i class="far fa-circle nav-icon"></i>
                  <p>Setting</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{url('/messages')}}" {!! (Request::is('messages') ? 'class="nav-link active"' : 'class="nav-link"') !!}>
              <i class="nav-icon fas fa-comments"></i>
              <p>
                Messages
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    @endif
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
    @yield('content')
    
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy;  <a href="http://adminlte.io"></a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b>
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>

<!-- Bootstrap -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="{{ asset('assets/dist/js/demo.js') }}"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="{{ asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('assets/plugins/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-mapael/maps/world_countries.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- InputMask -->
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.bundle.js')}}"></script>
<script src="{{ asset('assets/plugins/moment/moment.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<!-- PAGE SCRIPTS -->
<script src="{{ asset('assets/dist/js/pages/dashboard2.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('assets/plugins/fastclick/fastclick.js') }}"></script>
<!-- page script -->
<script src={{ asset("assets/js/custom.js") }} type="text/javascript"></script>


@stack('scripts')
<script>
     


  var table1=null;
  $(function () {
    table1 = $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
    $('.select2').select2();

    if (jQuery().datepicker) {
      $('.date-picker').datepicker({
          // rtl: Metronic.isRTL(),
          orientation: "left",
          autoclose: true
      });
        //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    }
    if (jQuery().timepicker) {
          $('.timepicker-default').timepicker({
              autoclose: true,
              showSeconds: true,
              minuteStep: 1
          });

          $('.timepicker-no-seconds').timepicker({
              autoclose: true,
              minuteStep: 5
          });

          $('.timepicker-24').timepicker({
              autoclose: true,
              minuteStep: 5,
              showSeconds: false,
              showMeridian: false
          });

          // handle input group button click
          $('.timepicker').parent('.input-group').on('click', '.input-group-btn', function(e){
              e.preventDefault();
              $(this).parent('.input-group').find('.timepicker').timepicker('showWidget');
          });
      }
  });
</script>
</body>
</html>
