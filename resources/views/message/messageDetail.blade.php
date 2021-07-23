@extends('layouts.admin_custom')

@section('content')
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Messages</h1>
        </div>
        <div class="col-sm-6">
          <a class="btn btn-primary float-sm-right m-r-20" href="{{url('messages')}}">Back</a>
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
              <h3 class="card-title">Message</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body row">
              <div class="col-md-3">
                <div class="form-group">
                  <img src="{{correctProImgPath($message->sender_url)}}" alt="" onerror="noExistProImg(this)" class="img-circle" style="width: 70px; height: 70px;"/>
                  <label for="e_name" class="m-l-20">{{$message->sender}}</label>
                </div>
                <div class="form-group">
                  <img src="{{correctProImgPath($message->receiver_url)}}" alt="" onerror="noExistProImg(this)" class="img-circle" style="width: 70px; height: 70px;"/>
                  <label for="e_email" class="m-l-20">{{$message->receiver}}</label>
                </div>
              </div>
              <div class="col-md-7">
                <div class="form-group">
                  <textarea class="form-control" rows="5">{{$message->content}}</textarea>
                </div>
              </div>
              <div class="col-md-2 d-flex align-items-end">
                <span>{{$message->msg_date}}</span>
              </div>
            </div>
              <!-- /.card-body -->

              
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
    </div>
  </section>

@endsection