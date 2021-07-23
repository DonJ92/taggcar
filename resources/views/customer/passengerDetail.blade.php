@extends('layouts.admin_custom')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Profile</h1>
        </div>
        <div class="col-sm-6">
          <a class="btn btn-primary float-sm-right m-r-20" href="{{url('customer/passengers')}}">back</a>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
	<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle" style="height:100px"
                       src="{{$passenger->avatar_url == "" ?  url("assets/img/avatar/Avatar.png") :  url("assets/img/avatar/".$passenger->avatar_url) }}"
                       alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{$passenger->name}}</h3>
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Email</b> <a class="float-right">{{$passenger->email}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Phone</b> <a class="float-right">{{$passenger->phone}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Birthday</b> <a class="float-right">{{$passenger->birthday}}</a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Passenger</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Experience</strong>
                <p class="text-muted">{{$passenger->experience}}</p>
                <hr>
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Age</strong>
                <p class="text-muted text-center">{{$passenger->age}}</p>
                <hr>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <b>Bookings</b>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                    <!-- Post -->
                    @foreach($bookings as $booking)
                    <div class="post">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="{{$passenger->avatar_url == "" ?  url("assets/img/avatar/Avatar.png") :  url("assets/img/avatar/".$passenger->avatar_url) }}" alt="user image">
                        <span class="username">
                          <a href="#">{{$booking->name}}</a>
                          <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                        </span>
                        <span class="description">{{$booking->departure}} - {{$booking->arrival}}</span>
                      </div>
                      <!-- /.user-block -->
                      <p>
                        {{$booking->b_departure}} - {{$booking->b_arrival}}
                      </p>
                      <p>{{$booking->b_leave_time}} - {{$booking->b_arrive_time}}</p>

                      <p>
                        <a href="#" class="link-black text-sm mr-2"><i class="fas fa-shopping-cart"></i> {{$booking->b_price}} / {{$booking->price}}$</a>
                        <a href="#" class="link-black text-sm"><i class="fas fa-users mr-1"></i>{{$booking->b_passengers}} / {{$booking->passengers}}</a>
                      </p>
                    </div>
                    @endforeach
                    <!-- /.post -->
                  </div>
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>

@endsection