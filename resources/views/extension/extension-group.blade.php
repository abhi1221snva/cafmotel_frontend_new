@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <section class="content-header">
                <h1>
                    <b>Extension Group</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Extension</li>
                    <li class="active">Extension Group</li>
                </ol>
        </section>
        <!-- Content Header (Page header) -->
        <section class="content-header">
           
                <div class="text-right mt-5 mb-3">
                     <a id="openAEGForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus fa-lg"></i> Add Extension Group</a>
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
                                    <th>Extensions</th>

                                    <th>Status</th>

                                    <th>Action</th>


                                </tr>
                                </thead>
                                <tbody>
                                @foreach($group as $key => $ext_group)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td id="ext-title-{{$ext_group->id}}">{{$ext_group->title}}</td>
                                        <td>
                                            <?php foreach($map as $mapped)
                                            {
                                                if($mapped->group_id == $ext_group->id)
                                                {
                                                    if($mapped->is_deleted == 0){
                                                    

                                                echo "<span class='badge bg-purple'>".$mapped->first_name." ".$mapped->last_name."-".$mapped->extension."</span>";
                                            }
                                        }
                                                
                                            }?>
                                        </td>

                                        <td>
                                            @if($ext_group->status == '1')
                                                <span class="label label-success">Active</span>
                                            @else ($ext_group->status == '0')
                                                <span class="label label-warning">Inactive</span>
                                            @endif

                                        </td>

                                        <td><a style="cursor:pointer;color:blue;" title="Edit" class='editEG' data-id={{$ext_group->id}} ><i class="fa fa-edit fa-lg"></i></a> | <a style="cursor:pointer;color:red;" title="Delete" class='openGroupDelete' data-id={{$ext_group->id}}><i class="fa fa-trash fa-lg"></i></a></td>

                                    </tr>
                                @endforeach
                                </tbody>


                            </table>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->


                </div><!-- /.col -->


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
                                    <input type="hidden" class="form-control" name="id" value="" id="edit-group-id" required>
                                    <label for="inputEmail3" class="col-form-label">Name <i data-toggle="tooltip" data-placement="right" title="Type extension group name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <input type="text" class="form-control" required name="title" id="title" placeholder="Enter Name" value="" />
                                    <?php  //echo "<pre>";print_r($extension_list);die;?>
                            <div class="form-group">
                            <label for="inputPassword3" id="" class="col-form-label">Extension <i data-toggle="tooltip" data-placement="right" title="select multiple extension from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                 
                  <select class="select2" multiple="multiple"  name="extensions[]" id="extensions" autocomplete="off" data-placeholder="Select Extension" style="width: 100%;">
                    
                  </select>
                </div>
                                </div>


                                <div class="modal-footer">
                                    <a href="/extension-group" type="button" class="btn btn btn-danger waves-effect waves-light" data-dismiss="modal"><i class="fa fa-reply fa-lg"></i> Cancel</a> 

                                     <a onclick="window.location.reload();" type="button" class="btn btn btn-warning waves-effect waves-light" data-dismiss="modal"><i class="fa fa-refresh fa-lg"></i> Reset</a> 
                                    <button type="submit" name="submit" class="btn btn btn-primary waves-effect waves-light"><i class="fa fa-check-square-o fa-lg"></i> Submit</button>
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
                                <p>You are about to delete <b><i class="title" id="delete-title"></i></b> Group.</p>
                                <p>Do you want to proceed?</p>
                                <input type="hidden" class="form-control" value="" id="group_id">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger btn-ok deleteEG">Delete</button>
                            </div>
                        </div>
                    </div>


                </div>

            </div><!-- /.row -->
        </section><!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <script>

        $(document).ready(function () {
            var oTable = $('#example').dataTable({
                "aoColumnDefs": [
                    {"bSortable": false, "aTargets": [2, 3]}
                ]
            });
        });

        $(".openGroupDelete").click(function () {
            var delete_id = $(this).data('id');
            $("#group_id").val(delete_id);
            $("#delete-title").html($("#ext-title-"+delete_id).html());
            $("#delete").modal();
        });
       
        $(document).on("click", "#openAEGForm", function () {
    $("#add-edit").html('Add Extension Group');
    $("#edit-group-id").val('');
    $("#title").val('');
    loadExtensionOptions([], null);
    $("#myModal").modal();
});

$(document).on("click", ".editEG", function () {
    var group_id = $(this).data('id');
    $("#add-edit").html('Edit Extension Group');
    $("#edit-group-id").val(group_id);
    $("#title").val($("#ext-title-" + group_id).html());
    loadExtensionOptions([], group_id);
    $("#myModal").modal();
});

function loadExtensionOptions(selectedExtensions, selectedGroupID) {
    $.ajax({
        url: 'mapExtensionGroup/',
        type: 'get',
        success: function (response) {
            var options = '';

            if (response.success && response.data && response.data.length > 0) {
                var uniqueExtensions = [];
                response.data.forEach(function (extension) {
                    if (extension.is_deleted === 0 && !uniqueExtensions.includes(extension.extension)) {
                        uniqueExtensions.push(extension.extension);
                        options += '<option value="' + extension.extension + '">' + extension.first_name + ' ' + extension.last_name + '-' + extension.extension + '</option>';
                    }
                });
            }

            $("#extensions").html(options);

            if (selectedGroupID) {
                loadSelectedExtensions(selectedGroupID, selectedExtensions);
            } else {
                $('#extensions').val(selectedExtensions).trigger('change');
            }
        }
    });
}

function loadSelectedExtensions(group_id, selectedExtensions) {
    $.ajax({
        url: 'mapExtensionGroup/',
        type: 'get',
        success: function (response) {
            var extensions = [];

            if (response.success && response.data && response.data.length > 0) {
                response.data.forEach(function (extension) {
                    if (extension.group_id === group_id && extension.is_deleted === 0) {
                        extensions.push(extension.extension);
                    }
                });
            }

            if (selectedExtensions) {
                selectedExtensions.forEach(function (extension) {
                    if (!extensions.includes(extension)) {
                        extensions.push(extension);
                    }
                });
            }

            $('#extensions').val(extensions).trigger('change');
        }
    });
}



        // $(document).on("click", ".editEG", function () {
        //     $("#add-edit").html('Edit Extension Group');
        //     var group_id = $(this).data('id');
        //     $("#edit-group-id").val(group_id);
        //     $("#title").val($("#ext-title-"+group_id).html());

        //     $.ajax({
        //     url: 'mapExtensionGroup/',
        //     type: 'get',
        //     success: function(response)
        //     {
        //         var option = "";
        //         var myarr = []; 
        //         var res_length = Object.keys(response.data).length
        //         for (var i = 0; i < res_length; i++)
        //         {
        //             var cart = {};
        //             var obj = response.data[i];
        //             if(obj.group_id == group_id)
        //             {
        //                 if(obj.group_id == group_id)
        //                 {
        //                     if((obj.is_deleted == 0)){
        //                 cart = obj.extension;
        //                 option += '<option value="'+obj.extension+'">'+obj.first_name+' '+obj.last_name+'-'+obj.extension+'</option>';
        //                 myarr.push(cart);
        //             }
        //         }
        //             }
        //         }

        //         $("#extensions").html(option);
        //         $('#extensions').val(myarr).trigger('change');

               
        //     }
        // });
        //     $("#myModal").modal();
        // });

        $(document).on("click", ".deleteEG", function () {
            var group_id = $('#group_id').val();
            $.ajax({
                url: 'deleteExtensionGroup/' + group_id,
                type: 'get',
                success: function (response) {
                    console.log(response);
                    window.location.reload(1);
                }
            });
        });
    </script>
@endsection
