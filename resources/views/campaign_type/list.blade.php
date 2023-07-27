@extends('layouts.app')
@section('title', 'Campaign Type')
@section('content')


<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Content Header (Page header) -->

     <section class="content-header">
                <h1>
                   <b>Campaign Types</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Campaign</li>
                    <li class="active">Campaign Type</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 
            <a id="openLabelForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Type</a>

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
                                    <th>Title</th>
                                    <th>Title Url</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($campaign_type))
                                @foreach($campaign_type as $key => $label_data)
                                <tr>

                                    <td>{{++$key}}</td>

                                    <td>{{$label_data->title}}</td>
                                    <td>{{$label_data->title_url}}</td>

                                   

                                    <td>
                                        @if($label_data->status == '0')
                                        <span class="label label-warning">Inactive</span> 
                                        @else ($label_data->status == '1')
                                        <span class="label label-success">Active</span> @endif

                                    </td>

                                    <td><a style="cursor:pointer;color:blue;" class='editLabel' data-id={{$label_data->id}} ><i class="fa fa-edit fa-lg"></i></a> 
                                    <!--| <a style="cursor:pointer;color:red;" class='openLabelDelete' data-id={{$label_data->id}}><i class="fa fa-trash fa-lg"></i></a>-->
                                   </td>

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
                            <input type="hidden" class="form-control" name="campaign_id" value="" id="id">

                            <label for="inputEmail3" class="col-form-label">Title <i data-toggle="tooltip" data-placement="right" title="Enter Title" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" class="form-control" required name="title" id="title" placeholder="Title">

                             <label for="inputEmail3" class="col-form-label">Title Url <i data-toggle="tooltip" data-placement="right" title="Enter Title Url" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" class="form-control" required name="title_url" id="title_url" placeholder="Title Url">

                             <label for="inputEmail3" class="col-form-label">Status <i data-toggle="tooltip" data-placement="right" title="IP Address Status" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <select class="form-control" name="status" id="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>

                           

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
                        <p>You are about to delete <b><i class="title"></i></b> record.</p>
                        <p>Do you want to proceed?</p>
                        <input type="hidden" class="form-control" name="campaign_id" value="" id="campaign_type_id">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-ok deleteLabel">Delete</button>

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
        $("#campaign_type_id").val(delete_id);

    });

    $("#openLabelForm").click(function() {
        $("#myModal").modal();
        $("#title").val('');
        $("#title_url").val();
        $("#status").val('1');


        $("#id").val('');
        $("#add-edit").html('Add Campaign Type');


    });

    $(document).on("click", ".editLabel", function() {
        $("#myModal").modal();
        $("#add-edit").html('Edit Campaign Type');
        var edit_id = $(this).data('id');
        $.ajax({
            url: 'campaign-type/' + edit_id,
            type: 'get',
            success: function(response) {
                $("#title").val(response.title);
                $("#title_url").val(response.title_url);
                $("#id").val(response.id);
                $("#status").val(response.status);


            }
        });
    });

    $(document).on("click", ".deleteLabel", function()
    {
        var delete_id = $("#campaign_type_id").val();
        var el = this;
        $.ajax({
            url: 'delete-campaign-type/' + delete_id,
            type: 'get',
            success: function(response) {
                window.location.reload(1);
            }
        });
    });
</script>
@endsection