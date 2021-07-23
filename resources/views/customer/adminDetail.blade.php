@extends('layouts.admin_custom')

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 d-flex">
        <h1>Admins</h1>
      </div>
      <div class="col-sm-6">
        <a class="btn btn-primary float-sm-right m-r-20" href="{{url('admins')}}">back</a>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Profile</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" action="{{url('adminEdit')}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="admin_id" value="{{$admin->id}}">
            <div class="card-body row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="e_name">Name</label>
                  <input type="text" class="form-control" name="e_name" id="e_name" placeholder="Enter name" value="{{$admin->name}}">
                </div>
                <div class="form-group">
                  <label for="e_email">Email</label>
                  <input type="email" class="form-control" name="e_email" id="e_email" placeholder="Enter email" value="{{$admin->email}}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group" style="margin-bottom:1%;">
                  <label for="exampleInputFile">Avatar upload</label>
                  <div class="d-flex col-md-6 justify-content-between">
                    <div class="fileinput fileinput-new" data-provides="fileinput" style="display: flex;">
                      <div class="fileinput-new thumbnail" style="width: 100px; height: 100px; margin-bottom:0px;">
                        <img id = "proImgRect" src="{{correctProImgPath($admin->avatar_url)}}" alt="" onerror="noExistProImg(this)" class="img-circle" style="width: 100px; height: 100px;"/>
                      </div>
                      <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                      </div>
                    </div>
                    <div class="file-browse d-flex align-items-end">
                      <span class="btn btn-primary btn-file btn_detail">
                        <span class="fileinput-new"> Choose file </span>
                        <input type="file" name="avatarImg" id="avatarImg" accept="image/*">
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
  $("#avatarImg").on("change", function (evt) {
      var files = evt.target.files;
      var file;
      file = files[0];
      uploadImg(file);
  });

  function uploadImg(uploadFile) {
    var datas = new FormData();
    datas.append('file', uploadFile);
    datas.append('_token', _token);
    datas.append('id', "{{$admin->id}}");
    var uploadUrl = "{{url("ajaxUploadCertImg")}}";
    $.ajax({
        url: uploadUrl,
        data: datas,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        type: 'POST',
        beforeSend: function (data, status) {
        },
        success: function (data, status) {
            console.log(data);
            if (data.status == 1) {
                $("#avatar_Img").attr("src", data.url);
                $(imgObj).before(htmlStr);
            } else {
                errorMsg(data.msg);
            }
        },
        error: function (data, status, e) {
            errorMsg("fail.");
            return;
        }
    });
  }
</script>
@endpush

@endsection