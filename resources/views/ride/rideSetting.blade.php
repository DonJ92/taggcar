@extends('layouts.admin_custom')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Rides</h1>
      </div>
      <div class="col-sm-6">
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<section class="content">
	<div class="container-fluid">
		<div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Setting</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <div>
            <div class="card-body">
              <div class="form-group">
                <label for="per_price">Price per 1km</label>
                <input type="text" class="form-control" id="per_price" placeholder="Price" value="{{$price->per_price}}">
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <a class="btn btn-primary" href="javascript:createPrice();">OK</a>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- right column -->
  	</div>
  </div>
</section>

@push('scripts')
<script>

	function createPrice(){
    var per_price = $('#per_price').val();
    var param = new Object();
    param._token = _token;
    param.per_price = per_price;

    var url = "{{url("ride/setting")}}";
    $.post(url, param, function(data){
        if(data.status == "1"){
            alert(data.msg);
            location.href= location.href;
        }else{
            alert(data.msg);
        }
    }, "json");
  }

</script>

@endpush
@endsection