@extends('layouts.app') @section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
                <h1>
                   <b>Smtp \ SendGrid List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration</li>
                    <li class="active">Smtp \ SendGrid List</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 
          <a id="openDNCForm"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add SMTP</a>
             <a id="openSendGridForm"type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add SendGrid</a>

           </div>
        </section>
    

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-xs-12">
                 <div class="form-group">
                <label>
                  <input type="radio" name="mail" id="smtp" class="flat-red" value="smtp" checked>
                  SMTP
                </label>&nbsp;&nbsp;&nbsp;
                <label>
                  <input type="radio" name="mail" id="sendgrid" value="sendgrid" class="flat-red">
                  SendGrid
                </label>
                
              </div>
                <div class="box">


                    <div class="box-body" id="show_smtp">
                        <table id="smtp_data" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mail Driver</th> 
                                    <th>Host</th>
                                    <th>Port</th>
                                    <th>Username</th>
                                    <!-- <th>Password</th> -->
                                    <th>Encryption</th>
                                    <th>Status</th>

                                    <th>Action</th>



                                </tr>
                            </thead>

                            @if(!empty($smtp_list))
                            <tbody>

                                @foreach($smtp_list as $key => $smtp)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{$smtp->mail_driver}}</td>

                                    <td>{{$smtp->mail_host}}</td>
                                    <td>{{$smtp->mail_port}}</td>
                                    <td>{{$smtp->mail_username}}</td>
                                    <!-- <td>{{$masked =  str_pad(substr($smtp->mail_password, -4), strlen($smtp->mail_password), '*', STR_PAD_LEFT)}}</td> -->
                                    <td>{{$smtp->mail_encryption}}</td>
                                    <td>
                                    @if($smtp->status == '1')
                                    <span class="label label-success">Active</span>
                                    @else ($smtp->status == '0')
                                    <span class="label label-warning">Inactive</span>
                                    @endif

                                    </td>






                                    <td>

                                        
                                        <a style="cursor:pointer;color:blue;" class='editDnc' data-number={{$smtp->id}}  ><i class="fa fa-edit fa-lg"></i></a> |
                                        <a style="cursor:pointer;color:red;" class='openDncDelete' data-status={{$smtp->status}} data-number={{$smtp->id}}><i class="fa fa-trash fa-lg"></i></a> 
                                    </td>

                                </tr>

                                @endforeach
                            </tbody>
                            @endif

                        </table>

                    </div>

                    <div class="box-body" id="hide_send_grid" style="display: none;">
                        <table id="send_grid" class="table table-bordered table-hover">
                             <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mail Driver</th> 
                                   
                                    <th>API Key</th>
                                    <th>Status</th>

                                    <th>Action</th>



                                </tr>
                            </thead>

                            @if(!empty($sendGrid_list))
                            <tbody>

                                @foreach($sendGrid_list as $key => $grid)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{$grid->mail_driver}}</td>

                                    <td>{{$grid->api_key}}</td>
                                    <td>
                                    @if($grid->status == '1')
                                    <span class="label label-success">Active</span>
                                    @else ($grid->status == '0')
                                    <span class="label label-warning">Inactive</span>
                                    @endif

                                    </td>






                                    <td>

                                        
                                        <a style="cursor:pointer;color:blue;" class='editGrid' data-number={{$grid->id}}  ><i class="fa fa-edit fa-lg"></i></a> |
                                        <a style="cursor:pointer;color:red;" class='openGridDelete' data-status={{$grid->status}} data-number={{$grid->id}}><i class="fa fa-trash fa-lg"></i></a> 
                                    </td>

                                </tr>

                                @endforeach
                            </tbody>
                            @endif

                        </table>

                    </div>
                    <!-- /.box-body -->
        
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->


                <div class="modal fade" id="myModalExcel" role="dialog">


            <div class="modal-dialog">
                <div class="modal-content" style="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="upload-excel"></h4>
                    </div>

                    <form method="post" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="dnc" value="" >

                            <label for="inputEmail3" class="col-form-label closed">Excel</label>
                            <input type="file"  class="form-control closed" required name="dnc_file" id="dnc_file" placeholder="Select Excel File">

                           


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>


                <div class="modal fade" id="sendgrid_model" role="dialog">


            <div class="modal-dialog">
                <div class="modal-content" style="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="send-add-edit"></h4>
                        
                    </div>

                    <form method="post" action="">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="smtp_id" value="" id="sendgrid_id">

                             <input type="hidden" onkeypress="//return isNumberKey($(this));" value="SendGrid" class="form-control closed" required name="mail_driver" id="" placeholder="Enter Driver Name">

                            <input type="hidden" onkeypress="//return isNumberKey($(this));" value="0" class="form-control closed" required name="mail_host" id="" placeholder="Enter Host Name">

                           

                            <input type="hidden" onkeypress="//return isNumberKey($(this));" value="0" class="form-control closed" required name="username" id="" placeholder="Enter Username">

                            <input type="hidden" onkeypress="//return isNumberKey($(this));" value="0" class="form-control closed" required name="password" id="" placeholder="Enter Password">

                            <input type="hidden" onkeypress="//return isNumberKey($(this));" value="0" class="form-control closed" required name="encryption" id="" placeholder="Enter Password">

                           
                           
                            <input type="hidden" onkeypress="//return isNumberKey($(this));" value="0" class="form-control closed" required name="mail_port" id="" placeholder="Enter API Key">

                           

                          
                           
                             <label for="inputEmail3" class="col-form-label closed">API Key</label>
                            <input type="text" onkeypress="//return isNumberKey($(this));" class="form-control closed" required name="sendgrid_key" id="sendgrid_key" placeholder="Enter API Key">

                           
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
                        <span style="color:red;" id="errorMsg"></span>
                    </div>

                    <form method="post" action="">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="smtp_id" value="" id="smtp_id">

                            <input type="hidden" onkeypress="//return isNumberKey($(this));" value="smtp" class="form-control closed" required name="mail_driver" id="mail_driver" placeholder="Enter Driver Name">

                            <label for="inputEmail3" class="col-form-label closed">Mail Host</label>
                            <input type="text" onkeypress="//return isNumberKey($(this));" class="form-control closed" required name="mail_host" id="mail_host" placeholder="Enter Host Name">

                           

                            <label for="inputEmail3" class="col-form-label closed">Username</label>
                            <input type="text" onkeypress="//return isNumberKey($(this));" class="form-control closed" required name="username" id="username" placeholder="Enter Username">

                            <label for="inputEmail3" class="col-form-label closed">Password</label>
                             <input type="" onkeypress="//return isNumberKey($(this));" class="form-control closedPassword" required name="password" id="password" placeholder="Enter Password">
                          
                             <div class="input-group" id="hiddenField" style="display: none !important">
                            <input type="password" onkeypress="//return isNumberKey($(this));" class="form-control"  name="password1" id="password_hidden" placeholder="">

                           
                
                <!-- /btn-group -->
                <div class="input-group-btn">
                    <button type="button" class="btn btn-danger" id="viewPassword">View</button>
                </div>
              </div>

                            <label for="inputEmail3" class="col-form-label closed">Encryption</label>
                            <select class="form-control closed" required name="encryption" id="encryption" >
                                <option value="">Select Encryption</option>
                                <option value="SSL">SSL</option>
                                <option value="TLS">TLS</option>

                            </select>
                           
                             <label for="inputEmail3" class="col-form-label closed">Mail Port</label>
                            <input type="text" onkeypress="//return isNumberKey($(this));" class="form-control closed" required name="mail_port" id="mail_port" placeholder="Enter Port No">


                             <input type="hidden" onkeypress="//return isNumberKey($(this));" value="0" class="form-control closed" required name="sendgrid_key" id="sendgrid_key" placeholder="Enter Port No">

                              <!-- <span id="smtpResponce"></span> -->

                           
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
                            <button type="button" name="submit" class="btn btn-info btn-ok checkSetting">Check Setting</button>
                            <a id="smtpResponce" style="display: none;" > <img style="width:30px;" src="{{ asset('asset/img/loader.gif') }}" /></a>
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
                        <input type="hidden" class="form-control smtp_number" name="smtp_number" value="" id="smtp_number">
                        <input type="hidden" class="form-control smtp_status" name="smtp_status" value="" id="smtp_status">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-ok deleteSmtp">Delete</button>

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
        var oTable = $('#smtp_data,#send_grid').dataTable({
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [2, 3]
            }]
        });
    });

    $(".openDncDelete, .openGridDelete").click(function() {
        var delete_id = $(this).data('number');
        var status_id = $(this).data('status');

        $("#delete").modal();
        $(".smtp_number").val(delete_id);
        $(".smtp_status").val(status_id);


    });


       $("#openSendGridForm").click(function() {

        $("#sendgrid_model").modal();
        
       
        $("#send-add-edit").html('Add SMTP Setting');
    });


    

    $("#openDNCForm").click(function() {

        $("#myModal").modal();
       // $("#mail_driver").val('');
        $("#mail_port").val('');
        $("#mail_host").val('');
        $("#mail_encryption").val('');
        $("#username").val('');
        $("#password").val('');

 $("#hiddenField").hide();
        $(".closedPassword").show();

        $(".closed").show();
        $("#add-edit").html('Add SMTP Setting');
    });

    $(document).on("click", ".editDnc", function() {
        $("#myModal").modal();
        $("#add-edit").html('Edit Smtp');
        var edit_smtp_id = $(this).data('number');
        $(".closed").hide();
         $(".checkSetting").show();
           

        $.ajax({
            url: 'editSmtp/' + edit_smtp_id,
            type: 'get',
            success: function(response) {
        $(".closed").show();

        $("#hiddenField").show();
        $(".closedPassword").hide();


                //alert(response);

               // $("#number").val(response[0].id);


                $("#mail_driver").val(response[0].mail_driver);
                $("#mail_host").val(response[0].mail_host);

                 $("#mail_port").val(response[0].mail_port);
                 $("#username").val(response[0].mail_username);
                 $("#password_hidden").val(response[0].mail_password);
                 $("#password").val(response[0].mail_password);

                 $("#encryption").val(response[0].mail_encryption);
                 $("#smtp_id").val(response[0].id);



            }
        });
    });

    $("#viewPassword").click(function(){
        $("#hiddenField").hide();
        $("#password").show();

    });


        $(document).on("click", ".editGrid", function() {
        $("#sendgrid_model").modal();
        $("#send-add-edit").html('Edit SendGrid');
        var edit_grid_id = $(this).data('number');
        
        

        $.ajax({
            url: 'editSmtp/' + edit_grid_id,
            type: 'get',
            success: function(response) {
        


                //alert(response);

               // $("#number").val(response[0].id);


                $("#mail_driver").val(response[0].mail_driver);
               
                 $("#sendgrid_key").val(response[0].api_key);
                 $("#sendgrid_id").val(response[0].id);





            }
        });
    });



        $(document).on("click", ".checkSetting", function() {
        // if(confirm("Are you sure you want to delete this?")){

           

        var mail_driver     = $("#mail_driver").val();
        var mail_host       = $("#mail_host").val();
        var mail_port       = $("#mail_port").val();
        var mail_encryption = $("#encryption").val();
        var mail_username   = $("#username").val();
        var mail_password   = $("#password").val();
       // alert(mail_host);

       
        if(mail_host ==''){
            // alert("ssmail_host");
                 $("#errorMsg").html("Please Enter Host");
                 return false;
        }

        else if(mail_username ==''){
            $("#errorMsg").html("Please Enter Username");
            return false;
        }else 
        if(mail_password == ''){
            $("#errorMsg").html("Please Enter Password");
            return false;
        }


        else 
        if(mail_encryption == ''){
            $("#errorMsg").html("Please Select Encryption");
            return false;
        } 
       
       
 $("#smtpResponce").show();


           var el = this;
        $.ajax({
            url: 'checkSMTPSetting/' + mail_driver +'/'+mail_host+'/'+mail_port+'/'+mail_encryption+'/'+mail_username+'/'+mail_password,
            type: 'get',
            success: function(response) {
                if(response == 1){
                    $(".checkSetting").hide();
                    $("#smtpResponce").show();
                    $("#smtpResponce").html("<span class='btn btn-success btn-ok'>Configuration Setting Success</span>");
                }else 
                if(response == 0){
                     $(".checkSetting").show();
                    $("#smtpResponce").show();
                    $("#smtpResponce").html("<span class='btn btn-danger btn-ok'>Configuration Setting Failed</span>");

             //   window.location.reload(1);
            }

             setTimeout(function(){ 
                        $("#smtpResponce").html('<img style="width:30px;" src="{{ asset('asset/img/loader.gif') }}" />');
                        $("#smtpResponce").hide(); }, 3000);
                }
        });



    });

    $(document).on("click", ".deleteSmtp", function() {
        // if(confirm("Are you sure you want to delete this?")){

        var delete_id = $("#smtp_number").val();
        var status = $("#smtp_status").val();


        var el = this;
        $.ajax({
            url: 'deleteSmtp/' + delete_id +'/'+status,
            type: 'get',
            success: function(response) {
                window.location.reload(1);
            }
        });

        //}
        /*  else
          {
              return false;
          }*/



    });


    $("#encryption").change(function(){
        var encry = $("#encryption").val();
        if(encry == 'SSL'){
            $("#mail_port").val('465');
        }
            else if(encry=="TLS"){
            $("#mail_port").val('587');


            }

    });

    $('input[type=radio][name=mail]').change(function() {
    if (this.value == 'smtp') {
        $("#show_smtp").show();
        $("#hide_send_grid").hide();
        $("#openSendGridForm").hide();
        $("#openDNCForm").show();



    }
    else if (this.value == 'sendgrid') {
         $("#show_smtp").hide();
        $("#hide_send_grid").show();
        $("#openDNCForm").hide();
        $("#openSendGridForm").show();

    }
});






</script>
@endsection