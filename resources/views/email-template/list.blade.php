@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">


         <section class="content-header">
                <h1>
                   <b>Email Templates</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration</li>

                    <li class="active">Email Templates</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
               <a href="{{ url('/email-template') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Email Templete</a>
           </div>
        </section>
    
        <!-- Content Header (Page header) -->
       

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
                                    <th>Template Name</th>
                                    <th>Template Html</th>
                                    <th>Status</th>
                                    
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(!empty($email_templates))
                                @foreach($email_templates as $key => $template)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$template->template_name}}</td>

                                
                                     
                                    <td>{{strip_tags(substr($template->template_html,0,100))}}....</td>
                                    <td>
                                    @if($template->status == '1')
                                    <span class="label label-success">Active</span>
                                    @else ($template->status == '0')
                                    <span class="label label-warning">Inactive</span>
                                    @endif
                                    
                                   
                                    <td><a style="cursor:pointer;color:blue;;" href="{{url('email-template')}}/{{ $template->id}}" class='editEG'><i class="fa fa-edit fa-lg"></i></a> 
                                        |
                                        <a @if($template->status == 1) style="cursor:pointer;color:green;" @else style="cursor:pointer;color:red;" @endif   class='openEmailTemplete' data-status={{$template->status}} data-id={{$template->id}}> @if($template->status == 1) <i class="fa fa-check fa-lg"></i> @else <i class="fa fa-check fa-lg"></i> @endif </a>
                                        |
                                        
                                        <a style="cursor:pointer;color:red;" href="javascript:deleteEmailTemplate({{ $template->id }})" id='delete-{{ $template->id }}'><i class="fa fa-trash fa-lg"></i></a>
                                       </td>

                                </tr>

                                @endforeach
                                @endif
                            </tbody>

                        </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <div class="modal fade" id="delete" role="dialog">

                <!-- Modal content-->

                <div class="modal-dialog">
                    <div class="modal-content" style="background-color: #d33724 !important;color:white;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Confirm Update</h4>
                        </div>
                        <div class="modal-body">
                            <p>You are about to change <b><i class="title"></i></b> Email Templete status.</p>
                            <p>Do you want to proceed?</p>
                            <input type="hidden" class="form-control" name="temp_id" value="" id="temp_id">
                            <input type="hidden" class="form-control" name="status" value="" id="status">


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger btn-ok deleteEmailTemp">Update</button>
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

@endsection
@push('scripts')

<script language="javascript">

    $(".openEmailTemplete").click(function() {
        var delete_id = $(this).data('id');
        var status = $(this).data('status');

        $("#delete").modal();
        $("#temp_id").val(delete_id);
        $("#status").val(status);


    });

    $(document).ready(function() {
        var oTable = $('#example').dataTable( {
            "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 2,3 ] }
            ]
        }); 
    });

    function deleteEmailTemplate(id) {

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
            url: "/email-template-delete/" + id,
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

    $(document).on("click", ".deleteEmailTemp", function() {
        //if(confirm("Are you sure you want to delete this record?")){
        var temp_id = $('#temp_id').val();
        var status = $('#status').val();

        /* alert(temp_id);
         alert(status);*/

        //var account_no = $(this).data('account_no');
        //alert(account_no);
        var el = this;
        $.ajax({
            url: 'deleteEmailTemplete/' + temp_id +'/'+status,
            type: 'get',
            success: function(response) {
                window.location.reload(1);
                // alert(response);
            }
        });
        //window.location.reload(1);

    });

</script>

@endpush