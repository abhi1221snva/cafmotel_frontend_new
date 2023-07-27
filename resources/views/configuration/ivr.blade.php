@extends('layouts.app') 
@section('title', 'IVR List')

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
                   <b>IVR</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Inbound Configuration</li>
                    <li class="active">IVR</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 <a id="openLabelForm" href='{{url('/edit-ivr')}}/0' class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add IVR</a>
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
                                    <th>Description</th>

                                    <th>File Name</th>

                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>

                                @if(!empty($ivr_list))
                                @foreach($ivr_list as $key => $ivr)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{$ivr->ivr_desc}}</td>
                                    <td><audio controls preload ='none'><source src="{{env('FILE_UPLOAD_URL')}}{{env('IVR_FILE_UPLOAD_FOLDER_NAME')}}/{{$ivr->ann_id}}.wav" type='audio/wav'></audio></td>
                                    <td>

                                        <a style="cursor:pointer;color:blue;" href="{{url('edit-ivr')}}/{{$ivr->id}}" >
                                            <i class="fa fa-edit fa-lg"></i>
                                        </a> 
                                        | <a style="cursor:pointer;color:red" class='openIvrDelete' data-id={{$ivr->id}} data-ivrid= {{$ivr->ivr_id}}>
                                            <i class="fa fa-trash fa-lg"></i>
                                        </a> 
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
                        <input type="hidden" class="form-control" name="label_id" value="" id="auto_id">

                        <input type="hidden" class="form-control" name="label_id" value="" id="ivr_id">
                        <input type="hidden" class="form-control" name="label_id" value="" id="auto_id">
                        <input type="hidden" class="form-control" name="label_id" value="" id="auto_id">

                        <input type="hidden" class="form-control" name="label_id" value="" id="ivr_id">
                        <input type="hidden" class="form-control" name="label_id" value="" id="ivr_id">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-ok deleteIvr">Delete</button>

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
var lang = [] ; 

$(document).ready(function () {
    var oTable = $('#example').dataTable({
        "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [2, 3]
            }]
    });
});

$(".openIvrDelete").click(function () {
    var delete_id = $(this).data('id');
    var ivr_id = $(this).data('ivrid');

    $("#delete").modal();
    $("#auto_id").val(delete_id);
    $("#ivr_id").val(ivr_id);


});

$("#openLabelForm").click(function () {
    $("#myModal").modal();
    $("#name").val('');
    $("#status").val('1');
    $("#id").val('');
    $("#add-edit").html('Add IVR');
});


$(document).on("click", ".deleteIvr", function () {
    //if(confirm("Are you sure you want to delete this?")){
    var delete_id = $("#auto_id").val();
    var ivr_id = $("#ivr_id").val();

    //alert(delete_id);
    var el = this;
    $.ajax({
        url: 'deleteIvr/' + delete_id + '/' + ivr_id,
        type: 'get',
        success: function (response) {
            window.location.reload(1);
        }
    });
});

</script>
@endsection