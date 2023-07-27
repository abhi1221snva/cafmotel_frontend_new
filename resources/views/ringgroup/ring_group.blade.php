@extends('layouts.app')
@section('title', 'Ring Group Lists')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">
        <!-- Content Header (Page header) -->

         <section class="content-header">
                <h1>
                   <b>Ring Group List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Extension</li>
                    <li class="active">Ring Group List</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
               <a id="openDNCForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Ring Group</a>
           </div>
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
                                    
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Extension</th>
                                    <th>Emails</th>

                                    <th>Action</th>

                                </tr>
                            </thead>

                            @if(!empty($ring_group))
                            <tbody>

                                @foreach($ring_group as $key => $ring)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{$ring->title}}</td>

                                    <td>{{$ring->description}}</td>
                                    {{--<td>
                                    @php
                                    $main = explode('&',$ring->extensions);
                                    @endphp
                                    @foreach ($main as $key=> $tag)
                                    <span>@if($key !=0)&@endif{{ $tag }}</span>
                                    @endforeach
                                    </td> --}}

                                    <td>
                                        @php
                                        $country_array = explode(',' , $ring->extension_name);
                                        foreach( $country_array as $key => $country )
                                        {
                                            echo "<span class='badge bg-purple'>{$country}</span>";
                                            echo ( ( $key < ( count( $country_array ) -1 ) ) ? ' ':'' );
                                        }
                                        @endphp
                                        </td>

                                    <td>{{$ring->emails}}</td>



                                    <td>

                                        

                                        <a style="cursor:pointer;color:blue;" title="Edit" class='editRingGroup' data-ringtype={{$ring->ring_type}} data-number={{$ring->id}} data-ext={{ str_replace("SIP/","",$ring->extensions)}} data-emails={{$ring->emails}} ><i class="fa fa-edit fa-lg"></i></a> |
                                        <a style="cursor:pointer;color:red;" title="Delete" class='openRingDelete' data-number={{$ring->id}}><i class="fa fa-trash fa-lg"></i></a>
                                    </td>

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


                       <div class="modal fade" id="myModal" role="dialog">


            <div class="modal-dialog">
                <div class="modal-content" style="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="add-edit"></h4>
                    </div>

                    <form method="post" action="">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="ring_id" value="" id="id">

                            <label for="inputEmail3" class="col-form-label closed">Title <i data-toggle="tooltip" data-placement="right" title="Type ring group name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text"  class="form-control closed" required name="title" id="title" placeholder="Enter Name">

                            <label for="inputEmail3" class="col-form-label closed">Description <i data-toggle="tooltip" data-placement="right" title="Type description for the ring group" class="fa fa-info-circle" aria-hidden="true"></i></label>

                             <textarea class="form-control" required name="description" id="description" placeholder="Enter Description"></textarea>


                           

                <div class="form-group">
                            <label for="inputPassword3" id="" class="col-form-label">Extension <i data-toggle="tooltip" data-placement="right" title="select multiple extension from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                 
                  <select class="select2" multiple="multiple" required name="extensions[]" id="extensions" autocomplete="off" data-placeholder="Select Extension" style="width: 100%;">
                    <option value="">Select Extension</option>
                    @foreach($extension_list as $key => $ext)
                    @if((request()->session()->get('level') > 9))
                    @if(($ext->user_level <= 9) || ($ext->extension == request()->session()->get('extension')))
                    <option value="{{$ext->extension}}">{{$ext->first_name}} {{$ext->last_name}} - {{$ext->extension}}</option>
                    @endif
                     @elseif(($ext->user_level < 9) || ($ext->extension == request()->session()->get('extension')))
                    <option value="{{$ext->extension}}">{{$ext->first_name}} {{$ext->last_name}} - {{$ext->extension}}</option>
                    @endif
                    @endforeach;
                  </select>
                </div>

                 <div class="form-group">
                            <label for="inputPassword3" id="" class="col-form-label">Email <i data-toggle="tooltip" data-placement="right" title="Type the email id which will be used to receive voicemails" class="fa fa-info-circle" aria-hidden="true"></i></label>
                 
                  <?php /* ?>
                  <select class="select2" multiple="multiple" name="emails[]" id="emails" autocomplete="off" data-placeholder="Select emails" style="width: 100%;">
                  <option value="">select Emails</option>
                  @foreach($extension_list as $key => $ext)
                  <option value="{{$ext->email}}">{{$ext->email}}</option>
                  @endforeach;
                  </select>
                  <?php */ ?>

                  <input type="" class="form-control" name="emails[]" value="" id="emails">
                 </div>

                 <div class="form-group" id="redirect_to_div">
                            <label>Ring Mode<i data-toggle="tooltip" data-placement="right" title="Select Ring Mode" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="ring_type" class="form-control" id="ring_type">
                                    <option value="1">Ring All</option>
                                    <option value="2">Sequence</option>
                                    <option value="3">Round Robin</option>
                                </select>
                            </div>
                        </div>
                <!-- /.form-group -->
           
                <!-- /.form-group -->


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
                        <p>You are about to delete <b><i class="title"></i></b> ring group detail.</p>
                        <p>Do you want to proceed?</p>
                        <input type="hidden" class="form-control" name="ring_id" value="" id="ring_id">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-ok deleteRing">Delete</button>

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

    $(".openRingDelete").click(function() {
        var delete_id = $(this).data('number');
        $("#delete").modal();
        $("#ring_id").val(delete_id);

    });


     $("#openExcelForm").click(function() {

        $("#myModalExcel").modal();
        $("#name").val('');
        $("#status").val('1');
        $("#id").val('');
        $(".closed").show();
        $("#upload-excel").html('Upload Excel');
    });

    $("#openDNCForm").click(function() {

        $("#myModal").modal();
        $("#name").val('');
        $("#status").val('1');
        $("#id").val('');
        $(".closed").show();
        $("#add-edit").html('Add Ring Group');
    });

    $(document).on("click", ".editRingGroup", function() {
        $("#myModal").modal();
        $("#add-edit").html('Edit Ring Group');

        var edit_number = $(this).data('number');
        var sip_list = $(this).data('ext');
        var sip_list = sip_list.replace("SIP/", "");
        var myarr = sip_list.split("&");
        $('#extensions').val(myarr).trigger('change');


        var email_list = $(this).data('emails');
        var ring_type = $(this).data('ringtype');

        var myarr_emails = email_list.split(",");
        $('#emails').val(myarr_emails).trigger('change');
        $.ajax({
            url: 'editRingGroup/' + edit_number,
            type: 'get',
            success: function(response) {
                $("#description").val(response[0].description);
                $("#title").val(response[0].title);
                $("#emails").val(response[0].emails);
                $("#ring_type").val(response[0].ring_type);
                $("#id").val(response[0].id);
            }
        });
    });

    $(document).on("click", ".deleteRing", function() {
        var delete_id = $("#ring_id").val();
        var el = this;
        $.ajax({
            url: 'deleteRingGroup/' + delete_id,
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
@endsection