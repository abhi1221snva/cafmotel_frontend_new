@extends('layouts.app') @section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
                <h1>
                   <b>Conference Overview </b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Conferencing</li>
                    <li class="active">Conference Overview </li>
                </ol>
        </section>
    

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body">
                        <table id="example" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <!-- <th>Account No</th> -->
                                    <th>Conference Id</th>
                                    <th>Total Participants</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Recording</th>
                                    <th>Status</th>
                                   <!--  <th>Prompt File</th> -->

                                   <!--  <th>Action</th> -->
                                </tr>
                            </thead>

                            @if(!empty($recording_conference))
                            <tbody>

                                @foreach($recording_conference as $key => $recording)
                                <tr>
                                    <td>{{++$key}}</td>

                                    <td>{{$recording->conference_id}}</td>
                                    <td>{{$recording->total_participants}}</td>
                                    <td>{{$recording->start_time}}</td>
                                    <td>{{$recording->end_time}}</td>
                                    <td><audio controls preload ='none'><source src="{{$recording->call_recording}}" type='audio/wav'></audio></td>
                                    <td>
                                        @if($recording->status == '0')
                                        OnGoing
                                        @elseif($recording->status == '1')
                                        Completed
                                        @endif

                                        </td>





                                    {{-- <td>
                                        <a style="cursor:pointer;color:blue;" class='editConference' data-auto_id={{$conference->id}}  ><i class="fa fa-edit fa-lg"></i></a> |
                                        <a style="cursor:pointer;color:red;" class='openConferenceDelete' data-id={{$conference->id}}><i class="fa fa-trash fa-lg"></i></a>
                                    </td> --}}

                                </tr>

                                @endforeach
                            </tbody>
                            @endif

                        </table>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->



        <div class="modal fade" id="myModal" role="dialog">


            <div class="modal-dialog">
                <div class="modal-content" style="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="add-edit"></h4>
                    </div>

                    <form method="post" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="auto_id" value="" id="id">


                            <label for="inputEmail3" class="col-form-label ">Title</label>
                            <input type="text" class="form-control closed" required name="title" id="title" placeholder="Enter title">


                            <label for="inputEmail3" class="col-form-label ">Conference Id</label>
                             <div class="input-group">
                            <input type="text" readonly="" onkeypress="//return isNumberKey($(this));" class="form-control" required name="conference_id" id="conference_id" placeholder="">

                           
                
                <!-- /btn-group -->
                <div class="input-group-btn">
                  <button type="button" class="btn btn-danger" onclick="document.getElementById('conference_id').value = getConferenceId(1000,9999)">Generate</button>





                </div>
              </div>

               <label for="inputEmail3" class="col-form-label ">Host Pin</label>
                             <div class="input-group">
                            <input type="text" readonly="" onkeypress="//return isNumberKey($(this));" class="form-control" required name="host_pin" id="host_pin" placeholder="">

                           
                
                <!-- /btn-group -->
                <div class="input-group-btn">
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('host_pin').value = getHostId(1000,9999)">Generate</button>
                </div>
              </div>


                            

                            <label for="inputEmail3" class="col-form-label ">Participant Pin</label>
                             <div class="input-group">

                            <input type="text" readonly="" onkeypress="//return isNumberKey($(this));" class="form-control" required name="part_pin" id="part_pin" placeholder="">

                            <div class="input-group-btn">
                  <button type="button" class="btn btn-danger" onclick="document.getElementById('part_pin').value = getPartId(1000,9999)">Generate</button>
                </div>
              </div>


                            <label for="inputEmail3" class="col-form-label ">Maximum Participant</label>
                            <input type="text" onkeypress="//return isNumberKey($(this));" class="form-control" required name="max_part" id="max_part" placeholder="Enter Part Pin">

                            <label for="inputEmail3" class="col-form-label ">Lock</label>
                            <select class="form-control" name="locked" id="locked">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                

                            </select>

                            <label for="inputEmail3" class="col-form-label ">Mute</label>
                            <select class="form-control" name="mute" id="mute">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                

                            </select>


                            <label for="inputEmail3" class="col-form-label ">Prompt File</label>
                            <input type="file"  class="form-control" required name="prompt_file" id="prompt" placeholder="Enter Part Pin">

                            

                           

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="modal fade" id="delete" role="dialog">

            <!-- Modal content-->

            <div class="modal-dialog">
                <div class="modal-content" style="background-color: #d33724 !important;color:white;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete <b><i class="title"></i></b> dnc record.</p>
                        <p>Do you want to proceed?</p>
                        <input type="hidden" class="form-control" name="auto_id" value="" id="auto_id">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-ok deleteConferencing">Delete</button>

                    </div>
                </div>
            </div>

        </div>
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
        var oTable = $('#example').dataTable({
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [2, 3]
            }]
        });
    });

    $(".openConferenceDelete").click(function() {
        var auto_id = $(this).data('id');
        $("#delete").modal();
        $("#auto_id").val(auto_id);

    });


   

    $("#openIpSettingForm").click(function() {

        $("#myModal").modal();
        $("#name").val('');
        $("#status").val('1');
        $("#id").val('');
        $(".closed").show();
        $("#add-edit").html('Add Conference Id');
    });

    $(document).on("click", ".editConference", function() {
        $("#myModal").modal();
        $("#add-edit").html('Edit Conference Id');
        var auto_id = $(this).data('auto_id');
       //alert(auto_id);
        

        $.ajax({
            url: 'editConferencing/' + auto_id,
            type: 'get',
            success: function(response) {

                
                $("#id").val(response[0].id);
                $("#title").val(response[0].title);
                $("#conference_id").val(response[0].conference_id);
                $("#host_pin").val(response[0].host_pin);
                $("#part_pin").val(response[0].part_pin);
                $("#max_part").val(response[0].max_part);
                $("#locked").val(response[0].locked);
                $("#mute").val(response[0].mute);
                $("#prompt").val(response[0].prompt_file);





                
            }
        });
    });

    $(document).on("click", ".deleteConferencing", function() {
        // if(confirm("Are you sure you want to delete this?")){

        var auto_id = $("#auto_id").val();
       

        var el = this;
        $.ajax({
            url: 'deleteConferencing/' + auto_id,
            type: 'get',
            success: function(response) {
                window.location.reload(1);
            }
        });

        //}
        /*  else
          {
              return false;
          }*/
    });
</script>

<script>
function getConferenceId(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}

function getHostId(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}

function getPartId(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}
</script>

<script src="{{ asset('asset/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('asset/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('asset/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>

@endsection