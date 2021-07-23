@extends('layouts.admin_custom')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Bookings</h1>
      </div>
      <div class="col-sm-6">
        <a class="btn btn-primary float-sm-right m-r-20" href="{{url('bookings')}}">Back</a>
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
          <form method="post" action="{{url('bookingUpdate')}}">
            {{ csrf_field() }}
            <input type="hidden" name="e_booking_id" value="{{$booking->id}}">
            <div class="card-body row d-flex align-items-center">
              <a href="{{ $booking->avatar_url }}" class="fancybox-button" data-rel="fancybox-button">
                <img class="img-circle img-bordered-sm" src="{{ correctProImgPath($booking->avatar_url) }}" onerror="noExistProImg(this)" alt="" style="width:50px;display:inline-block;">
              </a>
              <span class="m-l-20">{{$booking->name}}</span>
              <span class="m-l-20">({{$booking->departure}} / {{$booking->arrival}})</span>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="e_departure">Departure</label>
                    <input type="text" class="form-control" id="e_departure" name="e_departure" placeholder="Departure" value="{{$booking->b_departure}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="e_arrival">Arrival</label>
                    <input type="text" class="form-control" id="e_arrival" name="e_arrival" placeholder="{{$booking->b_arrival}}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="e_price">Price</label>
                    <input type="text" class="form-control" id="e_price" name="e_price" placeholder="Price" value="{{$booking->b_price}}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="e_passengers">Passengers</label>
                    <input type="text" class="form-control" id="e_passengers" name="e_passengers" placeholder="{{$booking->b_passengers}}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label ">Leave Time</label>
                    <div class="input-icon">
                      <i class="fa fa-clock-o"></i>
                      <input type="text" class="form-control timepicker timepicker-24" name="e_leave_time" value="{{$booking->b_leave_time}}">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Arrive Time</label>
                    <div class="input-icon">
                      <i class="fa fa-clock-o"></i>
                      <input type="text" class="form-control timepicker timepicker-24" name="e_arrive_time" value="{{$booking->b_arrive_time}}">
                    </div>
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