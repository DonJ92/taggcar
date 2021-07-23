@extends('layouts.admin_custom')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Place</h1>
      </div>
      <div class="col-sm-6">
        <button class="btn btn-primary float-sm-right m-r-20" data-toggle="modal" data-target="#placeDel" id="btn_selectDelete">Delete</button>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<section class="content">
	<div class="container-fluid">
		<div class="row">
      <!-- left column -->
      <div class="col-md-4">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Create</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <div>
            <div class="card-body">
              <div class="form-group">
                <label for="name">Place Name</label>
                <input type="text" class="form-control" id="name" placeholder="">
              </div>
              <div class="form-group">
                <label>City Name</label>
                <select class="select2" multiple="multiple" id="city_id" data-placeholder="Select a State"
                        style="width: 100%;">
                  @foreach($cities as $city)
                  <option value="{{$city->id}}">{{$city->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="name">Latitude</label>
                <input type="text" class="form-control" id="lat" placeholder="">
              </div>
              <div class="form-group">
                <label for="name">Longitude</label>
                <input type="text" class="form-control" id="lng" placeholder="">
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <a class="btn btn-primary" href="javascript:createPlace();">Submit</a>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- right column -->
      <div class="col-md-8">
	      <div class="card">
	        <div class="card-body">
	          <table id="example1" class="table table-bordered table-striped">
	            <thead>
	            <tr>
	              <th class="table-checkbox text-center">
	                <input type="checkbox" class="group-checkable" data-set="#example1 .checkboxes" id="allCheck"/>
	              </th>
	              <th class="text-center">Place Name</th>
	              <th class="text-center">City Name</th>
                <th class="text-center">Latitude</th>
	              <th class="text-center">Longitude</th>
	              <th class="text-center">Detail</th>
	            </tr>
	            </thead>
	            <tbody>
	              <?php
	                $i = 0;
	                $count = count($places);
	                $i = $count+1;
	              ?>
	            @foreach($places as $place)
	            <?php $i = $i-1; ?>
	            <tr>
	              <td class="text-center">
	                <input type="checkbox" class="checkboxes" name="ID" onclick="clicked();" value="{{ $place->id }}" />
	              </td>
	              <td class="text-center">{{$place->pname}}</td>
	              <td class="text-center">{{$place->name}}</td>
                <td class="text-center">{{$place->lat}}</td>
	              <td class="text-center">{{$place->lng}}</td>
	              <td class="text-center">
	                <div class="btn-group-xs">
	                  <a href="#" data-toggle="modal" data-target="#placeDetail" class="btn_detail btn_placeDetail">
	                      Detail
	                  </a>
	                  <input type="hidden" value="{{ $place->id }}">
                    <input type="hidden" value="{{ $place->pname }}">
                    <input type="hidden" value="{{ $place->city_id }}">
                    <input type="hidden" value="{{ $place->lat }}">
                    <input type="hidden" value="{{ $place->lng }}">
	                </div>
	              </td>
	            </tr>
	            @endforeach
	            </tbody>
	          </table>
	        </div>
	        <!-- /.card-body -->
	      </div>
	      <!-- /.card -->
	    </div>
  	</div>
  </div>
</section>
<div class="modal fade" id="placeDetail">
  <div class="modal-dialog">
    <div class="modal-content bg-primary">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <form method="post" action="{{url('city/placeUpdate')}}">
        {{ csrf_field() }}
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" id="e_id" name="e_id">
            <label for="e_name">Place Name</label>
            <input type="text" class="form-control" id="e_name" name="e_name" placeholder="">
          </div>
          <div class="form-group">
            <label>City Name</label>
            <select class="select2" multiple="multiple" id="e_city_id" name="e_city_id" data-placeholder="Select a State"
                    style="width: 100%;">
              @foreach($cities as $city)
              <option value="{{$city->id}}">{{$city->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="e_lat">Latitude</label>
            <input type="text" class="form-control" id="e_lat" name="e_lat" placeholder="">
          </div>
          <div class="form-group">
            <label for="e_lng">Longitude</label>
            <input type="text" class="form-control" id="e_lng" name="e_lng" placeholder="">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-outline-light">Save</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="placeDel">
  <div class="modal-dialog">
    <div class="modal-content bg-primary">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete?&hellip;</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-outline-light" onclick="deletePlaces()">Delete</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@push('scripts')
<script>

  $(document).ready(function() {
    document.getElementById('btn_selectDelete').disabled = true;
    $('#btn_selectDelete').css('opacity', 0.5);
  });

	function createPlace(){
    var name = $('#name').val();
    var city_id = $('#city_id').val().toString();
    var lat = $('#lat').val();
    var lng = $('#lng').val();
    var param = new Object();
    param._token = _token;
    param.name = name;
    param.city_id = city_id;
    param.lat = lat;
    param.lng = lng;

    var url = "{{url("city/placeCreate")}}";
    $.post(url, param, function(res){
        if(res.status == "1"){
            //alert(data.msg);
            $(document).ready( function() {
              if (table1) {
                table1.destroy();
                table1 = null;
              }
              $('#example1 tbody').html(res.table);
              table1 = $("#example1").DataTable();
            });
        }else{
            alert(res.msg);
        }
    }, "json");
  }

  var table = $('#example1');
  table.find('group-checkable').change(function() {
    var set = jQuery(this).attr('data-set');
    var checked = jQuery(this).is(':checked');
    if (checked) {
        document.getElementById("btn_selectDelete").disabled = false;
        $('#btn_selectDelete').css('opacity', 1);
    } else {
        document.getElementById("btn_selectDelete").disabled = true;
        $('#btn_selectDelete').css('opacity', 0.5);
    }
    jQuery(set).each(function() {
        if(checked) {
            $(this).prop('checked', true);
        } else {
            $(this).prop('checked', false);
        }
    });
    jQuery.uniform.update(set);
  });

  function clicked() {
    var ids = [];
    var ids = $("input[name='ID']").serialize().replace(/&ID=/g, ',').replace(/ID=/g, '').split(',');
    var ids_len = ids.length;
    if(ids[0] == '') ids_len = 0;
    var IDs = $("input[name='ID']").serialize();
    if (IDs == ""){
      document.getElementById("btn_selectDelete").disabled = true;
      $('#btn_selectDelete').css('opacity', 0.5);
    } else {
      document.getElementById("btn_selectDelete").disabled = false;
      $('#btn_selectDelete').css('opacity', 1);
    }
    $("#allCheck").removeClass("checked");
    var len = $('section:last').text();
    console.log(len);
    if(ids_len == len) {
        $("#allCheck").addClass("checked");
    }
  }

  function deletePlaces() {
    var IDs;
    IDs = $('input[name="ID"]').serialize().replace(/&ID=/g, ',').replace(/ID=/g, '');
    $.get('{{url('city/placeDel')}}' + "/" + IDs, function(data) {
      location.href = '';
    });
  }
</script>

@endpush
@endsection