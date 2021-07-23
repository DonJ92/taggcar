@extends('layouts.admin_custom')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Rides</h1>
      </div>
      <div class="col-sm-6">
        <a class="btn btn-primary float-sm-right m-r-20" href="{{url('trips')}}">Back</a>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<section class="content">
	<div class="container-fluid">
		<div class="row">
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form method="post" action="{{url('tripUpdate')}}">
            {{ csrf_field() }}
            <input type="hidden" name="e_trip_id" value="{{$trip->id}}">
            <div class="card-body row d-flex align-items-center">
              <a href="{{ $trip->avatar_url }}" class="fancybox-button" data-rel="fancybox-button">
                <img class="img-circle img-bordered-sm" src="{{ correctProImgPath($trip->avatar_url) }}" onerror="noExistProImg(this)" alt="" style="width:50px;display:inline-block;">
              </a>
              <span class="m-l-20">{{$trip->name}}</span>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="e_departure">Departure</label>
                    <input type="text" class="form-control" id="e_departure" name="e_departure" placeholder="Departure" value="{{$trip->departure}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="e_arrival">Arrival</label>
                    <input type="text" class="form-control" id="e_arrival" name="e_arrival" value="{{$trip->arrival}}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="e_price">Price</label>
                    <input type="text" class="form-control" id="e_price" name="e_price" placeholder="Price" value="{{$trip->price}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="e_passengers">Passengers</label>
                    <input type="text" class="form-control" id="e_passengers" name="e_passengers" value ="{{$trip->passengers}}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="e_path">Path</label>
                    <input type="text" class="form-control" id="e_path" name="e_path" placeholder="Path" value="{{$trip->path}}">
                  </div>
                </div>
                <div class="col-md-6">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label col-md-12">Start With Years</label>
                    <div class="input-group input-medium date date-picker" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
                      <input type="text" class="form-control" name="e_start_date" readonly value="{{$trip->start_date}}">
                      <span class="input-group-btn">
                      <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Leave Time</label>
                    <!-- <div class="input-icon"> -->
                      <i class="fa fa-clock-o"></i>
                      <input type="text" class="form-control timepicker timepicker-24" name="e_leave_time" value="{{$trip->leave_time}}">
                    <!-- </div> -->
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Arrive Time</label>
                      <!-- <div class="input-icon"> -->
                        <i class="fa fa-clock-o"></i>
                        <input type="text" class="form-control timepicker timepicker-24" name="e_arrive_time" value="{{$trip->arrive_time}}">
                      <!-- </div> -->
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button class="btn btn-primary" type="submit">Submit</button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
  	</div>
  </div>
</section>

@push('scripts')

@endpush
@endsection