@extends('layouts.app') @section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Content Header (Page header) -->

    <section class="content-header">
                <h1>
                   <b>Agent Status</b> ( <i class="fa fa-refresh" aria-hidden="true"></i> refreshing in <span id="timer"></span> sec )
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Campaign</li>
                    <li class="active">Agent Status</li>
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
                                    <th>Name</th>
                                    <th>Extension</th>
                                    <th>Campaign</th>
                                    {{--<th>Channel</th>--}}
                                    <th>Lead Id</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                           
          
                                @if(!empty($label_list))
                                @foreach($label_list as $key => $label_data)
                                <tr>

                                    <td>{{++$key}}</td>
                                    <td>{{$label_data->full_name}}</td>

                                    <td>{{$label_data->extension}}</td>
                                    <td>{{$label_data->title}}</td>
                                    {{--<td>{{$label_data->channel}}</td>--}}
                                    <td>{{$label_data->lead_id}}</td>
                                    <td>
                                        @if($label_data->status == '0')
                                        <span class="label label-success">Ready For Calls</span> 
                                        @elseif($label_data->status == '1')
                                        <span class="label label-success">In Call</span>
                                        @elseif($label_data->status == '2')
                                        <span class="label label-success">Hangup</span>
                                        @else ($label_data->status == '3')
                                        <span class="label label-warning">Pause</span> 
                                        @endif

                                    </td>

                                    <td>
                                    <a style="cursor:pointer;color:red;" class='openLabelDelete' data-id={{$label_data->extension}}><i class="fa fa-refresh fa-lg" title="Reset Extension"></i></a></td>

                                </tr>
                                @endforeach
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

                    <form method="post" action="">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="label_id" value="" id="id">

                            <label for="inputEmail3" class="col-form-label">Name</label>
                            <input type="text" class="form-control" required name="title" id="title" placeholder="Enter Name">

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
                        <p>You are about to Reset <b><i class="title"></i></b> Extension.</p>
                        <p>Do you want to proceed?</p>
                        <input type="hidden" class="form-control" name="label_id" value="" id="label_id">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-ok deleteLabel">Reset</button>

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

    $(".openLabelDelete").click(function() {
        var delete_id = $(this).data('id');
        $("#delete").modal();
        $("#label_id").val(delete_id);

    });

    

    
    $(document).on("click", ".deleteLabel", function() {

        //if(confirm("Are you sure you want to delete this?")){
        var delete_id = $("#label_id").val();
        //alert(delete_id);
        var el = this;
        $.ajax({
            url: 'deleteExtLiv/'+delete_id,
            type: 'get',
            //data:{'delete_id':delete_id},
            success: function(response) {
                window.location.reload(1);
            }
        });

    });
</script>

<script>
    var counter = 6;
    var interval = setInterval(function()
    {
        counter--;
        $("#timer").html(counter);
        if (counter == 0)
        {
            clearInterval(interval);
            window.location.reload();
        }
    },1000);

</script>
@endsection