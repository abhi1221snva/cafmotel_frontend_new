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
                   <b>Custom Field Values</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Lead Management</li>
                    <li class="active">Custom Field Values</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 
            <a id="openLabelForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Custom Field Value</a>

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
                                    <!-- <th>Account No</th> -->
                                    <th>Title</th>
                                    <th>Title Links</th>
                                    <th>Status</th>

                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($custom_fields_values))
                                @foreach($custom_fields_values as $key => $label_data)
                                <tr>

                                    <td>{{++$key}}</td>
                                    <td>{{$label_data->title_match}}</td>

                                    <td>{{$label_data->title_links}}</td>
                                    <td>
                                        @if($label_data->is_deleted == '0')
                                        <span class="label label-success">Active</span> @else ($label_data->is_deleted == '1')
                                        <span class="label label-warning">Inactive</span> @endif

                                    </td>

                                    <td><a style="cursor:pointer;color:blue;" class='editLabel' data-id={{$label_data->id}} ><i class="fa fa-edit fa-lg"></i></a> | <a style="cursor:pointer;color:red;" class='openLabelDelete' data-id={{$label_data->id}}><i class="fa fa-trash fa-lg"></i></a></td>

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
                            <input type="hidden" class="form-control" name="value_id" value="" id="value_id">
                            <input type="hidden" class="form-control" name="title_match" value="" id="title_match">

                            <input type="hidden" class="form-control" name="user_id" value="{{Session::get('id')}}" id="user_id">

                            <label for="inputEmail3" class="col-form-label">Custom Field Labels <i data-toggle="tooltip" data-placement="right" title="Type Label Name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            
                                <select class="form-control" required required name="custom_id" id="custom_id" placeholder="Enter Name">
                                    <option value="">Select Custom Field Labels</option>
                                    @if(!empty($custom_field_labels))
                                @foreach($custom_field_labels as $key => $label_data_values)
                                <option value="{{$label_data_values->id}}">{{$label_data_values->title}}</option>
                                @endforeach
                                @endif
                                </select>
                                
                            <label for="inputEmail3" class="col-form-label">Links <i data-toggle="tooltip" data-placement="right" title="Type Label Name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" required class="form-control" required name="title_links" id="title_links" placeholder="Enter Title Link ">

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
                        <input type="hidden" class="form-control" name="label_id" value="" id="label_id">

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
        $("#label_id").val(delete_id);

    });

    $("#openLabelForm").click(function() {
        $("#myModal").modal();
        $("#name").val('');
        $("#status").val('1');
        $("#id").val('');
        $("#add-edit").html('Add Custom Value');
    });

    $(document).on("click", ".editLabel", function() {
        $("#myModal").modal();
        $("#add-edit").html('Edit Custom Value');
        var edit_id = $(this).data('id');
        $.ajax({
            url: 'custom-field-value/' + edit_id,
            type: 'get',
            success: function(response) {
                $("#value_id").val(response.id);

                $('select[name^="custom_id"] option[value='+response.custom_id+']').attr("selected","selected");
                $("#title_links").val(response.title_links);
                $("#title_match").val(response.title_match);


            }
        });
    });

    $(document).on("click", ".deleteLabel", function() {

        //if(confirm("Are you sure you want to delete this?")){
        var delete_id = $("#label_id").val();
        //alert(delete_id);
        var el = this;
        $.ajax({
           url: 'delete-custom-field-value/' + delete_id,
            type: 'get',
            success: function(response) {
                window.location.reload(1);
            }
        });

    });

    $('#custom_id').change(function() {
  var title_match = $('#custom_id').find(":selected").text();
  $("#title_match").val(title_match);
})
</script>
@endsection