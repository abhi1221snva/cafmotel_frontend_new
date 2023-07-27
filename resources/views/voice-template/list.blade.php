@extends('layouts.app') @section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height:500px !important">
    <!-- Content Header (Page header) -->

       <section class="content-header">
                <h1>
                   <b>Voice Templete List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Voice</li>
                    
                    <li class="active">Voice Templete List</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
               <a href="{{ url('/add-voice-templete') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Voice Templete</a>
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
                                    <th>Templete Name</th>
                                    <th>Message</th>
                                    
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($templete_list as $key => $templete)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$templete->templete_name}}</td>
                                    <td>{{$templete->templete_desc}}</td>
                                    <td>
                                    @if($templete->is_deleted == '1')
                                    <span class="label label-success">Active</span>
                                    @else ($templete->is_deleted == '0')
                                    <span class="label label-warning">Inactive</span>
                                    @endif

                                    </td>
                                   
                                    <td><a style="cursor:pointer;color:blue;" href="{{url('editVoiceTemplete')}}/{{ $templete->templete_id}}" class='editEG'><i class="fa fa-edit fa-lg"></i></a> |
                                        <a @if($templete->is_deleted == 1) style="cursor:pointer;color:green;" @else style="cursor:pointer;color:red;" @endif   class='openVoiceTemplete' data-status={{$templete->is_deleted}} data-id={{$templete->templete_id}}> @if($templete->is_deleted == 1) <i class="fa fa-check fa-lg"></i> @else <i class="fa fa-check fa-lg"></i> @endif </a>
                                        |
                                        <a style="cursor:pointer;color:red;" href="javascript:deleteVoiceTemplate({{ $templete->templete_id }})" id='delete-{{ $templete->templete_id }}'><i class="fa fa-trash fa-lg"></i></a>
                                       </td>

                                </tr>

                                @endforeach

                        </table>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </div>
            <!-- /.col -->

            <div class="modal fade" id="addIpModal" role="dialog">

    <div class="modal-dialog">
        <div class="modal-content" style="">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="add-edit"></h4>
            </div>

            <form method="post" action="">
                @csrf
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="ext_ip_id" value="" id="ext_ip_id" required>
                    <input type="hidden" class="form-control" name="ip_name" value="ip" id="" required>

                    <label for="inputEmail3" class="col-form-label">Add Ip</label>
                    

                    <input type="text" class="form-control" minlength="7" placeholder="xxx.xxx.xxx.xxx" name="allowed_ip" id="allowed_ip"  >

                    <!-- pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" -->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
                </div>
            </form>
        </div>
    </div>

</div>


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
                    <input type="hidden" class="form-control" name="ext_id" value="" id ="ext_id" required>
                   
                            <label for="inputEmail3" class="col-form-label">New Password</label>
                            <input type="text" class="form-control" required  name="password" id="password" placeholder="Enter New Password">
                       



                          
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                     <button type="submit" name ="submit"  class="btn btn-info btn-ok">Save</button>
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
                            <h4 class="modal-title" id="myModalLabel">Confirm Update</h4>
                        </div>
                        <div class="modal-body">
                            <p>You are about to change <b><i class="title"></i></b> Voice Templete status.</p>
                            <p>Do you want to proceed?</p>
                            <input type="hidden" class="form-control" name="temp_id" value="" id="temp_id">
                            <input type="hidden" class="form-control" name="status" value="" id="status">


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger btn-ok deleteVoiceTemp">Update</button>
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
                "aTargets": [3,4]
            }]
        });
    });

    $(".openVoiceTemplete").click(function() {
        var delete_id = $(this).data('id');
        var status = $(this).data('status');

        $("#delete").modal();
        $("#temp_id").val(delete_id);
        $("#status").val(status);


    });


    function deleteVoiceTemplate(id) {

        if(!confirm('Are you sure to delete?')){
            e.preventDefault();
            return false;
        }
        
        $("#delete-" + id).hide();
        postData = {
            "_token": "{{ csrf_token() }}"
        };

        $.ajax({
            type: "POST",
            url: "/voice-template-delete/" + id,
            data: postData,
            dataType: "json",

            success: function (data) {
                console.log(data);
                if (data.success) {
                    $("#alert-success").html(data.message).show();
                    setTimeout(function(){
                        window.location.reload(1);
                    }, 2000);
                }
                else {
                    $("#alert-errors").html(data.message).show();
                }
            },

            error: function (xhr, status, error) {
                $("#alert-errors").html(error).show();
            }
        });
    }


        $(document).on("click", ".deleteVoiceTemp", function() {
        //if(confirm("Are you sure you want to delete this record?")){
        var temp_id = $('#temp_id').val();
        var status = $('#status').val();

        /* alert(temp_id);
         alert(status);*/

        //var account_no = $(this).data('account_no');
        //alert(account_no);
        var el = this;
        $.ajax({
            url: 'deleteVoiceTemplate/' + temp_id +'/'+status,
            type: 'get',
            success: function(response) {
                window.location.reload(1);
                // alert(response);
            }
        });
        //window.location.reload(1);

    });


  

 

</script>
@endsection