<!DOCTYPE html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Backend</title>

    <!-- favicon -->
    <link rel="icon"  type="image/x-icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
    <link href="{{ asset('assets/css/custom1.css') }}" rel="stylesheet">
    <link rel="stylesheet" href={{ asset('assets/css/jquery.gritter.css')}} rel="stylesheet">

  </head>
  <body class="logo-body">
    <div class="logo-screen">
      <div class="container">
        <div class="row link-content flex justify-center">
          <img src="{{asset('assets/img/logo/logo.png')}}" style="height: 80px; margin: 30px 0px">
          <form class="form-horizontal" role="form" method="POST" action="{{ url('/postLogin') }}" id="form-login">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-wizard">
              <div class="form-body">
                <div class="form-group">
                  <div class="col-md-12">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" autocomplete="off">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off">
                  </div>
                </div>
                <div class="form-group remember">
                  <input type="checkbox" name="remember">
                  remember me
                </div>
              </div>
              <div class="form-actions">
                <div class="row">
                  <div class="col-md-12 flex justify-center">
                    <button type="submit" class="uppercase btn btn-success p-l-r-50" style="background: #5ccdb1; border-color: #5ccdb1">Login</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="row flex justify-center login">
          <a class="pc" style="color:#007bffb5;" href="{{url('/register')}}">
            Create a new account.
          </a>
          <a class="mobile uppercase btn btn-danger" style="background: #ef7977; border-color: #ef7977" href="{{url('/register')}}">
            Register
          </a>
        </div>
      </div>
    </div>
  </body>

  <script src="{{ asset('assets/js/jquery.min.js') }}" type="text/javascript"></script>
  <script src={{asset("assets/js/plugins.js") }}></script>
  <script src={{asset('assets/js/login.js')}}></script>
  <script src={{asset('assets/js/jquery.gritter.min.js')}}></script>
  <script>$(function(){ Login.init(); });</script>
  @if(session('err') == '-2')
    <script type="text/javascript">
            $(document).ready(function(){
                $.gritter.add({
                    title: '<p class="font-menu" style="color:#ffffff;font-size: 14px; "></p>',
                    text: '<span class="font-menu textalert" style="font-size: 13px; ">Credentials Doesn\'t Match !</span>',
                    time: 3000
                });
            });
    </script>
  @endif
  @if(session('err') == '-1')
    <script type="text/javascript">
            $(document).ready(function(){
                $.gritter.add({
                    title: '<p class="font-menu" style="color:#ffffff;font-size: 14px; "></p>',
                    text: '<span class="font-menu textalert" style="font-size: 13px; ">Please enter your email and password correctly.</span>',
                    time: 3000
                });
            });
    </script>
  @endif
</html>
