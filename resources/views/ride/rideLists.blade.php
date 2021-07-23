@extends('layouts.admin_custom')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Rides</h1>
      </div>
      <div class="col-sm-6">
        <button class="btn btn-primary float-sm-right m-r-20" data-toggle="modal" data-target="#rideDel" id="btn_selectDelete">Delete</button>
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
                <label for="line_from">From</label>
                <input type="text" class="form-control" id="line_from" placeholder="From">
              </div>
              <div class="form-group">
                <label for="line_to">To</label>
                <input type="text" class="form-control" id="line_to" placeholder="To">
              </div>
              <div class="form-group">
                <label for="to_place">To Place</label>
                <input type="text" class="form-control" id="to_place" placeholder="To place">
              </div>
              <div class="form-group">
                <label for="line_price">Price</label>
                <input type="text" class="form-control" id="line_price" placeholder="Price">
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <a class="btn btn-primary" href="javascript:createLine();">Submit</a>
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
	              <th class="text-center">From</th>
	              <th class="text-center">To</th>
                <th class="text-center">To Place</th>
	              <th class="text-center">Price</th>
	              <th class="text-center">Detail</th>
	            </tr>
	            </thead>
	            <tbody>
	              <?php
	                $i = 0;
	                $count = count($lines);
	                $i = $count+1;
	              ?>
	            @foreach($lines as $line)
	            <?php $i = $i-1; ?>
	            <tr>
	              <td class="text-center">
	                <input type="checkbox" class="checkboxes" name="ID" onclick="clicked();" value="{{ $line->id }}" />
	              </td>
	              <td class="text-center">{{$line->line_from}}</td>
	              <td class="text-center">{{$line->line_to}}</td>
                <td class="text-center">{{$line->to_place}}</td>
	              <td class="text-center">{{$line->line_price}}</td>
	              <td class="text-center">
	                <div class="btn-group-xs">
	                  <a href="#" data-toggle="modal" data-target="#rideDetail" class="btn_detail btn_rideDetail">
	                      Detail
	                  </a>
	                  <input type="hidden" value="{{ $line->id }}">
                    <input type="hidden" value="{{ $line->line_from }}">
                    <input type="hidden" value="{{ $line->line_to }}">
                    <input type="hidden" value="{{ $line->to_place }}">
                    <input type="hidden" value="{{ $line->line_price }}">
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
<div class="modal fade" id="rideDetail">
  <div class="modal-dialog">
    <div class="modal-content bg-primary">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <form method="post" action="{{url('ride/rideUpdate')}}">
        {{ csrf_field() }}
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" id="e_id" name="e_id">
            <label for="e_line_from">From</label>
            <input type="text" class="form-control" id="e_line_from" name="e_line_from" placeholder="From">
          </div>
          <div class="form-group">
            <label for="e_line_to">To</label>
            <input type="text" class="form-control" id="e_line_to" name="e_line_to" placeholder="To">
          </div>
          <div class="form-group">
            <label for="e_to_place">To Place</label>
            <input type="text" class="form-control" id="e_to_place" name="e_to_place" placeholder="To Place">
          </div>
          <div class="form-group">
            <label for="e_line_price">Price</label>
            <input type="text" class="form-control" id="e_line_price" name="e_line_price" placeholder="Price">
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
<div class="modal fade" id="rideDel">
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
        <button type="button" class="btn btn-outline-light" onclick="deleteRides()">Delete</button>
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

	function createLine(){
    var line_from = $('#line_from').val();
    var line_to = $('#line_to').val();
    var to_place = $('#to_place').val();
    var line_price = $('#line_price').val();
    var param = new Object();
    param._token = _token;
    param.line_from = line_from;
    param.line_to = line_to;
    param.to_place = to_place;
    param.line_price = line_price;

    var url = "{{url("ride/rideCreate")}}";
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

  function deleteRides() {
    var IDs;
    IDs = $('input[name="ID"]').serialize().replace(/&ID=/g, ',').replace(/ID=/g, '');
    $.get('{{url('ride/rideDel')}}' + "/" + IDs, function(data) {
      location.href = '';
    });
  }
</script>

@endpush
@endsection