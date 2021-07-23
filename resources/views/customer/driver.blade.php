@extends('layouts.admin_custom')

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 d-flex">
        <h1>Drivers</h1>
        <div class="btn-group">
          <button class="btn btn-primary m-l-20 active focus btn_active" onclick="activate_user();">Active</button>
          <button class="btn btn-primary btn_inactive" onclick="deactivate_user();">Inactive</button>
        </div>
      </div>
      <div class="col-sm-6">
        <button class="btn btn-primary float-sm-right m-r-20" data-toggle="modal" data-target="#driverDel" id="btn_selectDelete">Delete</button>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content" id="active_body">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th class="table-checkbox text-center">
                <input type="checkbox" class="group-checkable" data-set="#example1 .checkboxes" id="allCheck"/>
              </th>
              <th class="text-center">Avatar</th>
              <th class="text-center">Name</th>
              <th class="text-center">Email</th>
              <th class="text-center">Actived</th>
              <th class="text-center">Detail</th>
            </tr>
            </thead>
            <tbody>
              <?php
                $i = 0;
                $count = count($Ydrivers);
                $i = $count+1;
              ?>
            @foreach($Ydrivers as $Ydriver)
            <?php $i = $i-1; ?>
            <tr>
              <td class="text-center">
                <input type="checkbox" class="checkboxes" name="ID" onclick="clicked();" value="{{ $Ydriver->id }}" />
              </td>
              <td class="text-center">
                <a href="{{ $Ydriver->avatar_url }}" class="fancybox-button" data-rel="fancybox-button">
                  <img class="img-responsive img-circle" src="{{ correctProImgPath($Ydriver->avatar_url) }}" onerror="noExistProImg(this)" alt="" style="width:50px;display:inline-block;">
                </a>
              </td>
              <td class="text-center">{{$Ydriver->name}}</td>
              <td class="text-center">{{$Ydriver->email}}</td>
              <td class="text-center">
                <!-- <div class="icheck-primary">
                        <input type="checkbox" value="" id="check1">
                        <label for="check1"></label>
                      </div> -->
                <div class="icheck-primary">
                  <?php
                  if($Ydriver->is_allow == "Y"){
                  ?>
                  <input type="checkbox" id="checkbox{{ $Ydriver->id }}" checked>
                  <?php
                  }
                  else{
                  ?>
                  <input type="checkbox" id="checkbox{{ $Ydriver->id }}">
                  <?php
                  }
                  ?>
                  <label for="checkbox{{ $Ydriver->id }}">
                    <span></span>
                    <input type="hidden" value="{{ $Ydriver->id }}">
                    <span class="check"></span>
                    <span class="box"></span>
                  </label>
                </div>
              </td>
              <td class="text-center">
                <div class="btn-group-xs">
                <a href="{{ url('customer/driverDetail/') }}/{{$Ydriver->id}}" class="btn_detail">
                      Detail
                  </a>
                  <input type="hidden" value="{{ $Ydriver->id }}">
                  <input type="hidden" value="{{ $Ydriver->name }}">
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
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<section class="content" id="inactive_body">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table id="example2" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th class="table-checkbox text-center">
                <input type="checkbox" class="group-checkable" data-set="#example2 .checkboxes" id="allCheck"/>
              </th>
              <th class="text-center">Avatar</th>
              <th class="text-center">Name</th>
              <th class="text-center">Email</th>
              <th class="text-center">Actived</th>
              <th class="text-center">Detail</th>
            </tr>
            </thead>
            <tbody>
              <?php
                $i = 0;
                $count = count($Ndrivers);
                $i = $count+1;
              ?>
            @foreach($Ndrivers as $Ndriver)
            <?php $i = $i-1; ?>
            <tr>
              <td class="text-center">
                <input type="checkbox" class="checkboxes" name="ID" onclick="clicked();" value="{{ $Ndriver->id }}" />
              </td>
              <td class="text-center">
                <a href="{{ $Ndriver->avatar_url }}" class="fancybox-button" data-rel="fancybox-button">
                  <img class="img-responsive img-circle" src="{{ correctProImgPath($Ndriver->avatar_url) }}" onerror="noExistProImg(this)" alt="" style="width:50px;display:inline-block;">
                </a>
              </td>
              <td class="text-center">{{$Ndriver->name}}</td>
              <td class="text-center">{{$Ndriver->email}}</td>
              <td class="text-center">
                <div class="icheck-primary">
                  <?php
                  if($Ndriver->is_allow == "Y"){
                  ?>
                  <input type="checkbox" id="checkbox{{ $Ndriver->id }}" checked>
                  <?php
                  }
                  else{
                  ?>
                  <input type="checkbox" id="checkbox{{ $Ndriver->id }}">
                  <?php
                  }
                  ?>
                  <label for="checkbox{{ $Ndriver->id }}">
                    <span></span>
                    <input type="hidden" value="{{ $Ndriver->id }}">
                    <span class="check"></span>
                    <span class="box"></span>
                  </label>
                </div>
              </td>
              <td class="text-center">
                <div class="btn-group-xs">
                  <a href="{{ url('customer/driverDetail/') }}/{{$Ndriver->id}}" class="btn_detail">
                      Detail
                  </a>
                  <input type="hidden" value="{{ $Ndriver->id }}">
                  <input type="hidden" value="{{ $Ndriver->name }}">
                </div>
              </td>
            </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="driverDel">
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
        <button type="button" class="btn btn-outline-light" onclick="deleteDrivers()">Delete</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.content -->

@push('scripts')
<script>
  $(document).ready(function() {
    document.getElementById('btn_selectDelete').disabled = true;
    $('#btn_selectDelete').css('opacity', 0.5);
    $('#inactive_body').hide();

  });
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
    if(ids_len == len) {
        $("#allCheck").addClass("checked");
    }
  }

  function deleteDrivers() {
    var IDs;
    IDs = $('input[name="ID"]').serialize().replace(/&ID=/g, ',').replace(/ID=/g, '');
    $.get('{{url('customer/passengerDel')}}' + "/" + IDs, function(data) {
      location.href = '';
    })
  }

  function activate_user() {
    $('#active_body').show();
    $('#inactive_body').hide();
    $('.btn_active').addClass('active');
    $('.btn_active').addClass('focus');
    $('.btn_inactive').removeClass('active');
    $('.btn_inactive').removeClass('focus');
  }

  function deactivate_user() {
    $('#active_body').hide();
    $('#inactive_body').show();
    $('.btn_active').removeClass('active');
    $('.btn_active').removeClass('focus');
    $('.btn_inactive').addClass('active');
    $('.btn_inactive').addClass('focus');
  }
</script>
@endpush

@endsection