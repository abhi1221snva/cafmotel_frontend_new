
@extends('layouts.app')

@section('content')	
<style>
    /* Adjust the column width as per your requirement */
    th {
        white-space: nowrap;
    }
    td {
        white-space: nowrap;
    }
 
</style>


<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                <div class="box">
                <div class="box-header with-border">
				  <h3 class="box-title">Extension List</h3>
                  @if(Session::get('level') > 5)
                  <form method="post">
                @csrf
                <div class="text-right mt-5 mb-3">
                    <button  name="xml"  type="submit" value="xml" class="btn btn-sm btn-warning"><i class="fa fa-file-text fa-lg"></i> XML</button>
                    <a href="{{ url('/add-extension') }}"  class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Extension</a>
                </div>
            </form>
            @endif
				</div>
				<!-- /.box-header -->
				<div class="box-body">
               
					<div class="table-responsive">
					  <table id="example1" class="table table-bordered table-striped">
						<thead>
                        <tr>
                                    <th>#</th>
                                    <th>Extension</th>
                                    <th>Webphone</th>
                                    @if((Session::get('level') > 9))

                                    <th>Secret</th>
                                    @endif
                                    <th>Name</th>
                                    <th>Voicemail (PIN)</th>
                                    <th>Public IP</th>
                                    <th>Local IP:Port</th>
                                    <th>Call Forward</th>
                                    <th >Twinning</th>
                                    <th>Follow Me</th>
                                    <th class="hidden">Email</th>

                                    <!-- <th>Status</th> -->
                                    <th>Action</th>
                                </tr>
						</thead>
						<tbody>
							
							@php 
                                $i=1
                                @endphp    
                                @foreach($extension_list as $key => $extensions)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$extensions->extension}}</td>
                                        <td>{{$extensions->alt_extension}}</td>
                                        @if((Session::get('level') > 9))


                                        <td>
                                            
                                            <a id="hide_<?php echo $i; ?>" onmouseover="bigImg(<?php echo $i; ?>)" ><?=str_repeat("*", strlen($extensions->secret));?></a>
                                            
                                            <a class="secret"style="display: none;" id="show_<?php echo $i; ?>" onmouseout="normalImg(<?php echo $i; ?>)">{{$extensions->secret}}</a>
                                            <a style="cursor:pointer;color:blue;display: none;" id="showcopy_{{$i}}" class="copyExtension" data-id="{{$extensions->id}}"><i class="fa fa-copy fa-lg"></i></a>
                                            <div id="successMessage" style="display: none;color:green"></div>
                                        </td>
                                        @endif

                                        <td>{{$extensions->first_name}} {{$extensions->last_name}}</td>
                                        <td>
                                            @if ($extensions->voicemail_greeting != '')
                                            <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-danger">NO</span>
                                            @endif
                                            
                                            <span class="badge badge-primary"style="margin-top:10px;">
                                            {{$extensions->vm_pin}}
                                            </span>
                                        </td>
                                        
                                        <td>
                                            @isset($extensions->ipaddr)
                                                {{$extensions->ipaddr}}
                                            @endisset
                                        </td>
                                        <td>
                                            @isset($extensions->fullcontact)
                                                @php
                                                    $udata = explode('@', substr(trim($extensions->fullcontact), 0, 30));
                                                    print_r(end($udata));
                                                @endphp
                                            @endisset
                                        </td>
                                        <td>
                                            @if ($extensions->call_forward == '1')
                                                <span class="badge bg-success">YES</span>
                                            
                                            @elseif ($extensions->call_forward == '2')
                                                <span class="badge bg-danger">NO</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($extensions->twinning == '1')
                                                <span class="badge bg-success">YES</span>
                                            
                                            @elseif ($extensions->twinning == '2')
                                                <span class="badge bg-danger">NO</span>
                                            @endif
                                        </td>

                                         <td>
                                            @if ($extensions->follow_me == '1')
                                                <span class="badge bg-success">YES</span>
                                            
                                            @elseif ($extensions->follow_me == '2')
                                                <span class="badge bg-danger">NO</span>
                                            @endif
                                        </td>

                                        <td class="hidden"id='email'>{{$extensions->email}}</td>
                                        <td>
                                            <a style="cursor:pointer;color:blue;" href="{{url('extension')}}/{{ $extensions->id}}" class='editEG'  title='Edit Extension'><i class="fa fa-edit fa-lg"></i></a>
                                            | <a style="cursor:pointer;color:blue;" class="openChangePassword" data-bs-toggle="modal" data-bs-target="#myModal" data-id="{{$extensions->id}}" title="Change Password"><i class="fa fa-key fa-lg"></i></a>

                                            | <a style="cursor:pointer;color:blue;" class='openChangePermissions' data-bs-toggle="modal" data-bs-target=#changePermissionModal data-id="{{$extensions->id}}" data-url="{{ route('assignableRoles', ['id'=>$extensions->id])}}" title='Unlock Extension'><i class="fa fa-unlock-alt fa-lg"></i></a>

                                            <!-- | <a style="cursor:pointer;color:blue;" class='openAddIp' data-id={{$extensions->id}}><i class="fa fa-thumb-tack"></i></a> -->

                                            <!-- | <a style="cursor:pointer;color:blue;" class='hangupConferences' data-extensionid={{$extensions->extension}} title='Reset Extension'><i class="fa fa fa-recycle"></i></a> -->

                                            | <a style="cursor:pointer;color:blue;" class='refresh_extension'data-bs-toggle="modal" data-bs-target=#refresh_extension data-id="{{$extensions->extension}}"><i class="fa fa-refresh fa-lg" title='Reset Extension'></i></a>

                                            @if((Session::get('level') > 5 && Session::get('id') !=  $extensions->id))
                                            | <a style="cursor:pointer;color:red;" class='openExtensionDelete'data-bs-toggle="modal" data-bs-target=#delete data-id="{{$extensions->id}}"><i class="fa fa-trash fa-lg" title='Delete Extension'></i></a></td>
                                            @else
                                            @if((Session::get('level') > 5))
                                            | <a style="cursor:pointer;color:red;" ><i class="fa fa-trash fa-lg" title='Disable Delete for Logged In User'></i></a></td>
                                            @endif
                                            @endif

                                            <!-- @if((Session::get('level') < 3)) -->

                                            <!-- | <a style="cursor:pointer;color:red;" class='refresh_extension' data-id={{$extensions->extension}}><i class="fa fa-refresh fa-lg" title='Refresh Extension'></i></a> -->
                                            <!-- @endif -->

                                    </tr>
                                    @php
                                    $i++
                                    @endphp

                                @endforeach
		
						</tbody>
					
					  </table>
					</div>
				</div>
				<!-- /.box-body -->
			  </div>
                    <!-- /.box -->
                    
                </div>
                <!-- /.col -->


                <div class="modal fade" id="hangupConferencesModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content" style="">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="add-edit"></h4>
                            </div>
                            <form method="post" name="add-ip-form" action="">
                                @csrf
                                <div class="modal-body">
                                    <!--  -->
                                    <label for="inputEmail3" class="col-form-label">Extension Number</label>
                                    <input type="text" readonly class="form-control" minlength="7" placeholder="" name="hangupConferences_id" id="hangupConferences_id">
                                    <!-- pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" -->
                                    <div id="hangup-conference"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" id="hangupConferences-cancel" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="submit" id="hangupConferences" class="btn btn-info btn-ok">Execute</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="addIpModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content" style="">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="add-edit"></h4>
                            </div>
                            <form method="post" name="add-ip-form" action="">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" class="form-control" name="ext_ip_id" value="" id="ext_ip_id" required>
                                    <input type="hidden" class="form-control" name="ip_name" value="ip" id="" required>
                                    <label for="inputEmail3" class="col-form-label">Add Ip</label>
                                    <input type="text" class="form-control" minlength="7" placeholder="xxx.xxx.xxx.xxx" name="allowed_ip" id="allowed_ip">
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
                                <button type="button" class="close" data-dismiss="modal" id="closeButton"aria-hidden="true">×</button>
                                <h4 class="modal-title" id="add-edit"></h4>
                            </div>
                            <form method="post" name="edit-extention" action="">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" class="form-control" name="ext_id" value="" id="ext_id" required>
                                    <label for="inputEmail3" class="col-form-label">New Password</label>
                                    <input type="text" class="form-control" required name="password" id="password" placeholder="Enter New Password">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default"id="cancelButton" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="changePermissionModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content" style="">
                            <div class="modal-header">
                                <h4 class="modal-title" id="change-permission-title">Change permission</h4>
                                <button type="button" class="close" data-dismiss="modal"id="changeClose" aria-hidden="true">×</button>
                            </div>
                            <form method="post" name="save-user-roles-form" id="save-user-roles-form" action="{{ route('saveUserRoles') }}">
                                <div class="modal-body">
                                    <div id="role-modal-loader" style="display:none; text-align: center;">
                                        <img src="assets/img/loader.gif" alt="loading..." height=100 width=100 />
                                    </div>
                                    <input type="hidden" class="form-control" name="role_ext_id" value="" id="role_ext_id" required>
                                    <div id="change-permission-body"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" id="role-modal-close" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-info btn-ok" id="role-modal-save" style="display: none;" >Save</button>
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
                                <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                                <button type="button" class="close" data-dismiss="modal" id="btnclose"aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <p>You are about to delete <b><i class="title"></i></b> Extension.</p>
                                <p>Do you want to proceed?</p>
                                <input type="hidden" class="form-control" name="label_id" value="" id="extension_id">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"id="btndelete" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger btn-ok deleteExtension">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>

   

                <div class="modal fade" id="refresh_extension" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content" style="background-color: #d33724 !important;color:white;">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"id="refreshClose">×</button>
                            </div>
                            
                            <div class="modal-body">
                                <p>You are about to Reset <b><i class="title"></i></b> Extension.</p>
                                <p>Do you want to proceed?</p>
                                <input type="hidden" class="form-control" name="label_id" value="" id="label_id">

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"id="refreshCancel" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger btn-ok deleteRefreshExtension">Reset</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // $(document).ready(function () {
        //     var oTable = $('#example').dataTable({
        //         "aoColumnDefs": [{
        //             "bSortable": false,
        //             "aTargets": [3, 4, 6]
        //         }]
        //     });
        // });

        //refresh extension

        $(".refresh_extension").click(function() {
            var delete_id = $(this).data('id');
            $("#refresh_extension").modal();
            $("#label_id").val(delete_id);

        });
        $("#refreshClose").click(function() {
      // Code to handle cancel button action (e.g., reset form or close modal)
      $("#refresh_extension").modal('hide');
    });
    $("#refreshCancel").click(function() {
      // Code to handle cancel button action (e.g., reset form or close modal)
      $("#refresh_extension").modal('hide');
    });
        $(document).on("click", ".deleteRefreshExtension", function() {
            var delete_id = $("#label_id").val();
            var el = this;
            $.ajax({
                url: 'deleteExtLiv/'+delete_id,
                type: 'get',
                success: function(response) {
                    window.location.reload(1);
                }
            });
        });
        
 
        //close refresh extension

        function bigImg(x)
        {
            $("#show_"+x).show();
            $("#hide_"+x).hide();
            $("#showcopy_" + x).hide();
        }

        function normalImg(x)
        {
            $("#show_"+x).hide();
            $("#showcopy_" + x).show();
            $("#hide_"+x).show();
        }

        
        $(".openExtensionDelete").click(function () {
            var delete_id = $(this).data('id');
            $("#delete").modal();
            $("#extension_id").val(delete_id);
        });

        $(document).on("click", ".changePassword", function () {
            var id = $("#ext_id").val();
            var password = $("#password").val();


            $.ajax({
                url: 'changePasswordAgent/' + id + '/' + password,
                type: 'get',
                success: function (response) {
                    /*$("#title").val(response[0].title);

                    $("#id").val(response[0].id);*/
                }
            });
        });

        $(document).on("click", ".openChangePassword", function () {
            $("#myModal").modal();
            $("#add-edit").html('change Password');
            var extension_id = $(this).data('id');
            $("#ext_id").val(extension_id);
            /*$.ajax({
                url: 'editExtensionGroup/'+extension_id,
                type: 'get',
                success: function(response){
                    $("#title").val(response[0].title);

                    $("#id").val(response[0].id);
                }
            });*/
            $("#cancelButton").click(function() {
      // Code to handle cancel button action (e.g., reset form or close modal)
      $("#myModal").modal('hide');
    });
    $("#closeButton").click(function() {
      // Code to handle cancel button action (e.g., reset form or close modal)
      $("#myModal").modal('hide');
    });

        });

        /* hangup Conference */
        $(document).on("click", ".hangupConferences", function () {
            $("#hangupConferencesModal").modal();
            $("#add-edit").html('Reset Extension');
            var extension_id = $(this).data('extensionid');
            $("#hangupConferences_id").val(extension_id);
        });

        $(document).on("click", "#hangupConferences", function (e) {
            e.preventDefault();
            postData = {
                "_token": $("#user-role-csrf").val(),
                "extensionId": parseInt($("#hangupConferences_id").val())
            };
            console.log(postData);

            $.ajax({
                type: "POST",
                url: "{{ route('hangupConferences') }}",
                data: postData,
                success: function(data){
                    console.log(data);
                    $("#hangupConferences-cancel").click();
                },
                error: function(error){
                    console.log(error.responseJSON);
                    $("#hangup-conference").html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                    $("#hangup-conference").show();
                }
            });
        });

         /* end hangup Conference */


        $(document).on("click", ".openAddIp", function () {
            $("#addIpModal").modal();
            $("#add-edit").html('Add Ip');
            var extension_id = $(this).data('id');
            //alert(extension_id);
            $("#ext_ip_id").val(extension_id);
            /*$.ajax({
                url: 'editExtensionGroup/'+extension_id,
                type: 'get',
                success: function(response){
                    $("#title").val(response[0].title);

                    $("#id").val(response[0].id);
                }
            });*/
        });

        $(document).on("click", ".deleteExtension", function () {
            var extension_id = $('#extension_id').val();
            $.ajax({
                url: 'deleteExtension/' + extension_id,
                type: 'get',
                success: function (response) {
                    window.location.reload(1);
                }
            });
        });
        $("#btndelete").click(function() {
      // Code to handle cancel button action (e.g., reset form or close modal)
      $("#delete").modal('hide');
    });
    $("#btnclose").click(function() {
      // Code to handle cancel button action (e.g., reset form or close modal)
      $("#delete").modal('hide');
    });
        $(document).on("click", ".openChangePermissions", function (e) {
            e.preventDefault();

            var url = $(this).data('url');
            var extension_id = $(this).data('id');
            $("#role_ext_id").val(extension_id);

            $("#change-permission-body").html("");     // leave it blank before ajax call
            $("#role-modal-loader").show();     // load ajax loader
            $("#role-modal-save").hide();       // hide the save button
            $("#changePermissionModal").modal();

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html'
            }).done(function(content){
                $("#role-modal-loader").hide();     // hide ajax loader
                $("#role-modal-save").show();       // show save button
                $("#change-permission-body").html(content);
                $("#change-permission-body").show();
            }).fail(function(){
                $("#change-permission-body").html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                $("#role-modal-loader").hide();
            });
        });
        $(document).on("click", "#role-modal-save", function (e) {
            e.preventDefault();

            $("#role-modal-save").hide();
            $("#change-permission-body").hide();
            $("#role-modal-loader").show();

            postData = {
                "_token": $("#user-role-csrf").val(),
                "userId": parseInt($("#role_ext_id").val()),
                "role": $("#role-select").val()
            };
            console.log(postData);

            $.ajax({
                type: "POST",
                url: "{{ route('saveUserRoles') }}",
                data: postData,
                success: function(data){
                    console.log(data);
                    $("#role-modal-close").click();
                },
                error: function(error){
                    console.log(error.responseJSON);
                    $("#role-modal-loader").hide();
                    $("#change-permission-body").html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                    $("#change-permission-body").show();
                }
            });
   
  
        });
        $("#changeClose").click(function() {
      // Code to handle cancel button action (e.g., reset form or close modal)
      $("#changePermissionModal").modal('hide');
    });
    $("#role-modal-close").click(function() {
      // Code to handle cancel button action (e.g., reset form or close modal)
      $("#changePermissionModal").modal('hide');
    });
        </script>
   <script>
    $(document).ready(function() {
    $(".copyExtension").click(function(e) {
        e.preventDefault();
        var extensionId = $(this).data("id");
        var secret = $(this).closest("tr").find(".secret").text();
        var email = $(this).closest("tr").find("#email").text(); 
        // Combine email and secret values
        var textToCopy = "Email: " + email + ", Secret: " + secret;
        // Create a temporary input element
        var tempInput = document.createElement("input");
        tempInput.setAttribute("type", "text");
        tempInput.setAttribute("value", textToCopy);
        document.body.appendChild(tempInput);
        tempInput.select();
        tempInput.setSelectionRange(0, 99999);
        document.execCommand("copy");
        document.body.removeChild(tempInput);
         // Show success message
          $("#successMessage").text("Copied!");
        $("#successMessage").show();
        
        // Hide the success message after a certain time (optional)
        setTimeout(function() {
            $("#successMessage").hide();
        }, 3000); // 3000 milliseconds = 3 sec
    });
});
</script>

	
	


	
	
</body>
@endsection
<!-- Mirrored from joblly-admin-template-dashboard.multipurposethemes.com/bs5/main/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 20 Jul 2023 04:57:15 GMT -->
</html>

