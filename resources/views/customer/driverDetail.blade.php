@extends('layouts.admin_custom')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Profile</h1>
        </div>
        <div class="col-sm-6">
          <a class="btn btn-primary float-sm-right m-r-20" href="{{url('customer/drivers')}}">back</a>
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
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{correctProImgPath($driver->avatar_url) }}"
                       alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{$driver->name}}</h3>
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Email</b> <a class="float-right">{{$driver->email}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Phone</b> <a class="float-right">{{$driver->phone}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Birthday</b> <a class="float-right">{{$driver->birthday}}</a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Driver</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Experience</strong>
                <p class="text-muted">{{$driver->experience}}</p>
                <hr>
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Age</strong>
                <p class="text-muted text-center">{{$driver->age}}</p>
                <hr>
                <strong><i class="far fa-file-alt mr-1"></i> Activation</strong>
                <div class="d-flex justify-content-around align-items-center">
                	<?php if($driver->is_allow=='Y') echo '<p class="m-b-0" style="color: #007bff;">Active</p><button class="btn btn-danger" onclick="inactive('.$driver->id.')">Inactive</button>'; else echo '<p class="m-b-0" style="color:#dc3545;">Inactive</p><button class="btn btn-primary" onclick="active('.$driver->id.')">Active</button>'; ?>
              	</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <b>Trips</b>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                    <!-- Post -->
                    @foreach($trips as $trip)
                    <div class="post">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="{{correctProImgPath($trip->avatar_url)}}" alt="user image">
                        <span class="username">
                          <a href="#">{{$trip->departure}} - {{$trip->arrival}}</a>
                        </span>
                        <span class="description">{{$trip->start_date}}  {{$trip->leave_time}} - {{$trip->arrive_time}}</span>
                      </div>
                      <!-- /.user-block -->
                      <p>
                        {{$trip->path}}
                      </p>

                      <p>
                        <a href="#" class="link-black text-sm mr-2"><i class="fas fa-users mr-1"></i> {{$trip->passengers}}</a>
                        <a href="#" class="link-black text-sm"><i class="fas fa-shopping-cart mr-1"></i> {{$trip->price}} $</i></a>
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

  @push('scripts')
  	<script>
  		function active(id) {
  			$.get('{{url('customer/driverActive')}}' + "/" + id, function(data) {
  				location.href="";
  			});
  		}

  		function inactive(id) {
  			$.get('{{url('customer/driverInactive')}}' + "/" + id, function(data) {
  				location.href="";
  			});
  		}
  	</script>
  @endpush
@endsection