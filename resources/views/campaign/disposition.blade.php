@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

      <section class="content-header">
                <h1>
                    <b>Disposition</b>
                </h1>
                <ol class="breadcrumb">
                     <li><a href="{{('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                     <li class="active">Campaign</li>
                    <li class="active">Disposition</li>
                </ol>
        </section>

    <section class="content-header">

        <div class="text-right mt-5 mb-3"> 
              <a id="openDispositionForm"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Disposition</a>
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
                                <th>Type</th>
                                <th>Enable SMS</th>
                               <th>Status</th>
                                <th>Action</th>


                                
                            </tr>
                            </thead>
                            <tbody>
                        @foreach($disposition_list as $key => $disposition)
                            <tr>

                                
                                <td>{{ $key+1 }}</td>
                               
                                <td>{{$disposition->title}}</td>
                                <td>
                                @if($disposition->d_type == '1')
                                    Status
                                @elseif ($disposition->d_type == '2')
                                    Do Not Call
                                @else ($disposition->d_type == '3')
                                    Callback
                                @endif
                                </td>
                                <td>
                                @if($disposition->enable_sms == '0')
                                    <span class="badge bg-red">NO</span>
                                    @elseif($disposition->enable_sms == '1')
                                    <span class="badge bg-green">YES</span>
                                    @elseif($disposition->enable_sms == NULL)
                                    
                                    @endif
                                </td>    
                                <td>
                                    @if($disposition->status == '1')
                                    <span class="label label-success">Active</span>
                                    @else ($disposition->status == '0')
                                    <span class="label label-warning">Inactive</span>
                                    @endif

                                    </td>
                                
                                <td><a style="cursor:pointer;color:blue;" class='editDisposition' data-id={{$disposition->id}} ><i class="fa fa-edit fa-lg"></i></a> | <a style="cursor:pointer;color:red;"  class='openDeleteDisposition' data-id={{$disposition->id}}><i class="fa fa-trash fa-lg"></i></a></td>


                                    



                                
                            </tr>
                        @endforeach   
                        </table>

                        
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

              
            </div><!-- /.col -->







 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="add-edit"></h4>
        </div>
                <div class="modal-body">
                    <form method="post" action="">
                            @csrf
                    <div class="box-body">

                         <input type="hidden" class="form-control" name="id" value="" id ="id" required>


                            <input type="hidden" class="form-control" name="username" value="" id ="first-name" required>

                         <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">Name <i data-toggle="tooltip" data-placement="right" title="Type disposition name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" class="form-control" required  name="title" id="title" placeholder="Enter Name">
                        </div>

                        <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">Type <i data-toggle="tooltip" data-placement="right" title="Select type of disposition" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <select name="d_type" class="form-control" id="d_type" >
                            <option value="1">Status</option>
                            <option value="2">Do Not Call</option>
                            <option value="3">Callback</option>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">Enable SMS Pop Up <i data-toggle="tooltip" data-placement="right" title="Select Enable Sms" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <select name="enable_sms" class="form-control" id="enable_sms" >
                            <option value=""Selected>--Select--</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                            </select>
                        </div>
                        
                    </div>


                



                    <button type="submit" name ="submit"  class="btn btn btn-primary waves-effect waves-light">Save</button>
                  

                    </div><!-- /.box-body -->
                </form>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
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
                            <p>You are about to delete <b><i class="title"></i></b> record.</p>
                            <p>Do you want to proceed?</p>
                            <input type="hidden" class="form-control" name="disposition_id" value="" id="disposition_id">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger btn-ok deleteDisposition">Delete</button>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>

     $(".openDeleteDisposition").click(function() {
        var delete_id = $(this).data('id');
        $("#delete").modal();
        $("#disposition_id").val(delete_id);

    });


    $("#openDispositionForm").click(function(){
        $("#myModal").modal();
        $("#name").val('');
        $("#status").val('1');
        $("#enable_sms").val('');
        $("#id").val('');
        $("#add-edit").html('Add dispositon');
    });

    $(document).on("click", ".editDisposition" , function() {
    $("#myModal").modal();
    $("#add-edit").html('Edit Disposition');
    var edit_id = $(this).data('id');
    $.ajax({
        url: 'editDisposition/'+edit_id,
        type: 'get',
        success: function(response){
            console.log(response);
            $("#title").val(response[0].title);
             $("#id").val(response[0].id);
             $('#d_type').val(response[0].d_type);
             $('#enable_sms').val(response[0].enable_sms);
        }
    });
});


    $(document).on("click", ".deleteDisposition" , function()
    {
        var delete_id = $("#disposition_id").val();
        var el = this;
        $.ajax
        ({
            url: 'deleteDisposition/'+delete_id,
            type: 'get',
            success: function(response)
            {
                window.location.reload(1);
            }
        });
    });

</script>


 <script>
     $(document).ready(function() {
    var oTable = $('#example').dataTable( {
    "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 2,3 ] }
    ]
}); 
} );
</script>
@endsection