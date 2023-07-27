@extends('layouts.app')
@section('title', 'VoIP Configurations')
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
                   <b>VoIP Configurations</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration</li>
                    <li class="active">VoIP Configurations</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 
            <a href="{{ url('/voip-configuration') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Voip Configuration</a>

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
                                    <th>Name</th>
                                    <th>Host</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($voip_configurations))
                                @foreach($voip_configurations as $key => $voip)
                                @php
                                $data = explode('_',$voip->name);
                                @endphp
                                
                                <tr>

                                    <td>{{++$key}}</td>
                                    <td>{{str_replace('-',' ',$data[0])}}</td>
                                    <td>{{$voip->host}}</td>
                                    <td>{{str_replace('-',' ',$voip->username)}}</td>
                                    <td>{{$voip->secret}}</td>
                                    <td><a href="{{ url('/voip-configuration/') }}/{{$voip->id}}" class='editLabel' ><i class="fa fa-edit fa-lg"></i></a> | <a style="cursor:pointer;color:red;" class='openLabelDelete' data-id={{$voip->id}}><i class="fa fa-trash fa-lg"></i></a></td>

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

    

    $(document).on("click", ".deleteLabel", function()
    {
        var delete_id = $("#label_id").val();
        var el = this;
        $.ajax({
            url: 'delete-voip-configuration/' + delete_id,
            type: 'get',
            success: function(response) {
                toastr.success('Voip Configuration has been deleted successfully');
                window.location.reload(1);
            }
        });
    });
</script>
@endsection