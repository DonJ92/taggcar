@extends('layouts.admin_custom')

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Passengers</h1>
      </div>
      <div class="col-sm-6">
        <button class="btn btn-primary float-sm-right m-r-20" data-toggle="modal" data-target="#passengerDel" id="btn_selectDelete">Delete</button>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
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
              <th class="text-center">ID Status</th>
              <th class="text-center">Uploaded ID</th>
              <th class="text-center">Detail</th>
            </tr>
            </thead>
            <tbody>
              <?php
                $i = 0;
                $count = count($passengers);
                $i = $count+1;
              ?>
            @foreach($passengers as $passenger)
            <?php $i = $i-1; ?>
            <tr>
              <td class="text-center">
                <input type="checkbox" class="checkboxes" name="ID" onclick="clicked();" value="{{ $passenger->id }}" />
              </td>
              <td class="text-center">
                <a href="{{ $passenger->avatar_url }}" class="fancybox-button" data-rel="fancybox-button">
                  <img class="img-responsive img-circle" src="{{$passenger->avatar_url == "" ?  url("assets/img/avatar/Avatar.png") :  url("assets/img/avatar/".$passenger->avatar_url) }}" onerror="noExistProImg(this)" alt="" style="width:50px;height:50px;display:inline-block;">
                </a>
              </td>
              <td class="text-center">{{$passenger->name}}</td>
              <td class="text-center">{{$passenger->email}}</td>
              <td class="text-center">{{$passenger->verified_id == 0 ? "Unverified" : ($passenger->verified_id == 1 ? "Requested" : "Verified")}}</td>
              <td class="text-center">
                 @if ($passenger->verified_id > 0)
                   <a href="{{$passenger->id_verification_image == "" ?  url("assets/img/avatar/Avatar.png") :  url("assets/img/avatar/".$passenger->id_verification_image) }}" class="fancybox-button" data-rel="fancybox-button">
                  <img class="img-responsive" src="{{$passenger->id_verification_image == "" ?  url("assets/img/avatar/Avatar.png") :  url("assets/img/avatar/".$passenger->id_verification_image) }}" onerror="noExistProImg(this)" alt="" style="width:50px;height:50px;display:inline-block;">
                </a>
                    @if ($passenger->verified_id == 1)
                    <a href="{{ url("api/verify_id/".$passenger->id)}}" class="fancybox-button" data-rel="fancybox-button">Verify
                    </a>
                    @endif
                  @endif
                </a>
              </td>
              <td class="text-center">
                <div class="btn-group-xs">
                  <a href="{{ url('customer/passengerDetail/') }}/{{$passenger->id}}" class="btn_detail">
                      Detail
                  </a>
                  <input type="hidden" value="{{ $passenger->id }}">
                  <input type="hidden" value="{{ $passenger->name }}">
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
  <div class="modal fade" id="passengerDel">
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
          <button type="button" class="btn btn-outline-light" onclick="deletePassengers()">Delete</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</section>
@push('scripts')
<script>
  $(document).ready(function() {
    document.getElementById('btn_selectDelete').disabled = true;
    $('#btn_selectDelete').css('opacity', 0.5);
  });

  var table = $('#example1');
  table.find('.group-checkable').change(function() {
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

  function deletePassengers() {
    var IDs;
    IDs = $('input[name="ID"]').serialize().replace(/&ID=/g, ',').replace(/ID=/g, '');
    $.get('{{url('customer/passengerDel')}}' + "/" + IDs, function(data) {
      location.href = '';
    })
  }
</script>
<!-- /.content -->
@endpush
@endsection