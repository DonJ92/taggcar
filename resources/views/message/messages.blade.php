@extends('layouts.admin_custom')

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Messages</h1>
      </div>
      <div class="col-sm-6">
        <button class="btn btn-primary float-sm-right m-r-20" data-toggle="modal" data-target="#messageDel" id="btn_selectDelete">Delete</button>
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
              <th class="text-center">Sender</th>
              <th class="text-center">Content</th>
              <th class="text-center">Receiver</th>
              <th class="text-center">Date</th>
              <th class="text-center">Detail</th>
            </tr>
            </thead>
            <tbody>
              <?php
                $i = 0;
                $count = count($messages);
                $i = $count+1;
              ?>
            @foreach($messages as $message)
            <?php $i = $i-1; ?>
            <tr>
              <td class="text-center">
                <input type="checkbox" class="checkboxes" name="ID" onclick="clicked();" value="{{ $message->id }}" />
              </td>
              <td class="text-center">{{$message->sender}}</td>
              <td class="text-center">{{$message->content}}</td>
              <td class="text-center">{{$message->receiver}}</td>
              <td class="text-center">{{$message->msg_date}}</td>
              <td class="text-center">
                <div class="btn-group-xs">
                  <a href="{{ url('messageDetail/') }}/{{$message->id}}" class="btn_detail">
                      Detail
                  </a>
                  <input type="hidden" value="{{ $message->id }}">
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
<!-- /.content -->
<div class="modal fade" id="messageDel">
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
        <button type="button" class="btn btn-outline-light" onclick="deleteMessages()">Delete</button>
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

  function deleteMessages() {
    var IDs;
    IDs = $('input[name="ID"]').serialize().replace(/&ID=/g, ',').replace(/ID=/g, '');
    $.get('{{url('messageDel')}}' + "/" + IDs, function(data) {
      location.href = '';
    })
  }
  
</script>
@endpush

@endsection