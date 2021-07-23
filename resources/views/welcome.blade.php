<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Backend</title>

    <!-- favicon -->
    <link rel="icon"  type="image/x-icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">


  </head>
  
  <body class="logo-body">
    <div class="logo-screen">
      <div class="container">
        <div class="row link-content flex justify-center">
          <img src="{{asset('assets/img/logo/logo.png')}}" style="height: 80px; margin: 30px 0px">
          <div class="col-md-12 col-sm-12 col-xl-12 flex justify-center p-b-5">
            <a class="pc uppercase btn btn-danger" style="background:#ef7977; border-color: #ef7977;" href="{{url('/register')}}">
                get started for free
            </a>
            <a class="mobile uppercase btn btn-danger" style="background:#ef7977; border-color: #ef7977;" href="{{url('/register')}}">
                Create new account
            </a>
          </div>
          <div class="col-md-12 col-sm-12 col-xl-12 flex justify-center p-b-5">
            <a class="pc" style="color:#007bffb5;" href="{{url('/login')}}">
              Already have an account? Log in now.
            </a>
            <a class="mobile uppercase btn btn-success" style="background:#5ccdb1; border-color: #5ccdb1;" href="{{url('/login')}}">
              login
            </a>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
