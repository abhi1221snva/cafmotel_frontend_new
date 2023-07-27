@extends('layouts.app')

@push('styles')
    <style type="text/css">
        .col-md-6 {
            margin-top: 15px;
        }
    </style>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->


    <div class="content-wrapper">

          <section class="content-header">
                <h1>
                   <b>Add SMTP</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration</li>
                    <li class="active">Add SMTP</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
               <a href="{{ url('/smtps') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show SMTP </a>
           </div>
        </section>
        <!-- Content Header (Page header) -->
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <span style="color:red;width: 100%;" id="errorMsg"></span>
                        <form method="post" name="smtpform" id="smtpform" action="">
                            @csrf
                            <div class="box-body">
                                <div class="modal-body">
                                    <div class="form-group m-b-10">

                                        <div class="col-md-6">
                                            <label>Driver Name <i data-toggle="tooltip" data-placement="right" title="Type smtp driver name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                {{-- <input type="text" class="form-control" value="{{old('mail_driver')}}" name="mail_driver" id="mail_driver" required> --}}
                                                <select class="form-control" name="mail_driver" id="mail_driver" required>
                                                    <option value="smtp">SMTP</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Host Name <i data-toggle="tooltip" data-placement="right" title="Type smtp host name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" class="form-control" value="{{old('mail_host')}}" name="mail_host" id="mail_host" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Username <i data-toggle="tooltip" data-placement="right" title="Type smtp username" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" class="form-control" name="mail_username" value="{{old('mail_username')}}" id="mail_username" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Password <i data-toggle="tooltip" data-placement="right" title="Type smtp password" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="password" class="form-control" name="mail_password" value="{{old('mail_password')}}" id="mail_password" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Encryption Type <i data-toggle="tooltip" data-placement="right" title="Select Encryption type from the drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control" name="mail_encryption" id="mail_encryption" required>
                                                    <option value="">Select Encryption</option>
                                                    <option @if (old('mail_encryption') == "TLS") {{ 'selected' }} @endif value="TLS">TLS</option>
                                                    <option @if (old('mail_encryption') == "SSL") {{ 'selected' }} @endif value="SSL">SSL</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Port No <i data-toggle="tooltip" data-placement="right" title="Type port number" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" class="form-control" value="{{old('mail_port')}}" name="mail_port" id="mail_port" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>For Sending <i data-toggle="tooltip" data-placement="right" title="Select sending type from the drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <select class="form-control" name="sender_type" id="sender_type" required>
                                                @if( session("level") < 7)
                                                    <option value="user">User Emails</option>
                                                @else
                                                    <option>Select Email Type</option>
                                                    <option @if (old('sender_type') == "system") {{ 'selected' }} @endif value="system">System Emails</option>
                                                    <option @if (old('sender_type') == "campaign") {{ 'selected' }} @endif value="campaign">Campaign Emails</option>
                                                    <option @if (old('sender_type') == "user") {{ 'selected' }} @endif value="user">User Emails</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-md-6" id="from-email-controls">
                                            <label>Sender Email <i data-toggle="tooltip" data-placement="right" title="Type sender email" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <input type="email" class="form-control" value="{{old('from_email')}}" name="from_email" id="from_email">
                                            </div>
                                        </div>

                                        <div class="col-md-6" id="from-name-controls">
                                            <label>Sender Name <i data-toggle="tooltip" data-placement="right" title="Type sender name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <input type="text" class="form-control" value="{{old('from_name')}}" name="from_name" id="from_name">
                                        </div>

                                        <div class="col-md-6" id="user-controls">
                                            <label>User <i data-toggle="tooltip" data-placement="right" title="Select user" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <select class="form-control select2" name="user_id" id="user_id">
                                                @if(session("level") >= 7)
                                                <option value="">Select Extension Email</option>
                                                @endif
                                                @foreach($extensions as $key => $extension)
                                                    <option value={{$extension->id}} @if(old('user_id') == $extension->id || (session("level") < 7 && session("id") == $extension->id) )  selected @endif >{{ $extension->email }} - {{ $extension->first_name }}  {{ $extension->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6" id="campaign-controls">
                                            <label>Campaign <i data-toggle="tooltip" data-placement="right" title="Select campaign name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <select class="form-control select2" name="campaign_id" id="campaign_id">
                                                <option value="">Select Campaign</option>
                                                @foreach($campaigns as $key => $campaign)
                                                    <option value="{{ $campaign->id }}" @if(old('campaign_id') == $campaign->id)  selected @endif >{{ $campaign->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div style="clear: both"></div>
                                <div class="modal-footer">
                                    <button type="button" name="submit" class="btn btn-info btn-ok checkSetting">Check Setting</button>
                                    <a id="smtpResponce" style="display: none;"> <img style="width:30px;" src="{{ asset('asset/img/loader.gif') }}"/></a>
                                    <button type="submit" name="submit" value="add" class="btn btn btn-primary waves-effect waves-light">Save</button>
                                </div>
                            </div><!-- /.box-body -->
                        </form>
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".select2-selection--single").css('height', "32px");

            $("#user-controls").hide();
            $("#campaign-controls").hide();
            $("#from-email-controls").hide();
            $("#from-name-controls").hide();

            @if (old('sender_type') == "user" || session("level") < 7)
                $("#user-controls").show();
                 $("#from-email-controls").show();
                $("#from-name-controls").show();
            @else
                $("#from-email-controls").show();
                $("#from-name-controls").show();
                @if (old('sender_type') == "campaign")
                $("#campaign-controls").show();
                @endif
            @endif

            $("#sender_type").change(function () {
                $("#from-email-controls").hide();
                $("#from-name-controls").hide();
                $("#user-controls").hide();
                $("#campaign-controls").hide();
                let selected = $(this).val();
                if (selected === 'user') {
                    $("#user-controls").show();
                     $("#from-email-controls").show();
                $("#from-name-controls").show();
                } else {
                    $("#from-email-controls").show();
                    $("#from-name-controls").show();
                    if (selected === 'campaign') {
                        $("#campaign-controls").show();
                    }
                }
            });

            $('#smtpform').on('submit', function() {
                let sender_type = $("#sender_type").val();
                let user_id = $("#user_id").val();
                let campaign_id = $("#campaign_id").val();
                if(sender_type === "user" && user_id === "") {
                    $("#alert-errors").html("Select user");
                    $("#alert-errors").show();
                    return false;
                }
                if(sender_type === "campaign" && campaign_id === "") {
                    $("#alert-errors").html("Select campaign");
                    $("#alert-errors").show();
                    return false;
                }
            });

            $("#mail_encryption").change(function () {
                var encry = $("#mail_encryption").val();
                if (encry == 'SSL') {
                    $("#mail_port").val('465');
                } else if (encry == "TLS") {
                    $("#mail_port").val('587');
                }
            });

            $(document).on("click", ".checkSetting", function () {
                var mail_driver = $("#mail_driver").val();
                var mail_host = $("#mail_host").val();
                var mail_port = $("#mail_port").val();
                var mail_encryption = $("#mail_encryption").val();
                var mail_username = $("#mail_username").val();
                var mail_password = $("#mail_password").val();
                var from_email = $("#from_email").val();
                var from_name = $("#from_name").val();
                var sender_type = $("#sender_type").val();
                var user_id = $("#user_id").val();

                if (mail_driver == '') {
                    $("#alert-errors").html("Please enter Driver");
                    $("#alert-errors").show();
                    $("#mail_driver").focus();
                    return false;
                } else if (mail_host == '') {
                    $("#alert-errors").html("Please enter Host");
                    $("#alert-errors").show();
                    $("#mail_host").focus();
                    return false;
                } else if (mail_username == '') {
                    $("#alert-errors").html("Please enter Username");
                    $("#alert-errors").show();
                    $("#mail_username").focus();
                    return false;
                } else if (mail_password == '') {
                    $("#alert-errors").html("Please enter Password");
                    $("#alert-errors").show();
                    $("#mail_password").focus();
                    return false;
                } else if (mail_encryption == '') {
                    $("#alert-errors").html("Please select Encryption");
                    $("#alert-errors").show();
                    $("#mail_encryption").focus();
                    return false;
                } else if (mail_port == '') {
                    $("#alert-errors").html("Please select Port");
                    $("#alert-errors").show();
                    $("#mail_port").focus();
                    return false;
                } else if (sender_type == '') {
                    $("#alert-errors").html("Please select For Sending");
                    $("#alert-errors").show();
                    $("#sender_type").focus();
                    return false;
                } else if (sender_type === 'user' && user_id == '') {
                    $("#alert-errors").html("Please select User");
                    $("#alert-errors").show();
                    $("#user_id").focus();
                    return false;
                }

                $("#smtpResponce").show();
                var el = this;
                $.ajax({
                    url: '/checkSMTPSetting/',
                    data: {mail_driver: mail_driver,
                        mail_host: mail_host,
                        mail_username: mail_username,
                        mail_password: mail_password,
                        mail_encryption: mail_encryption,
                        mail_port: mail_port,
                        from_email: from_email,
                        from_name: from_name,
                        sender_type: sender_type,
                        user_id: user_id
                    },
                    type: 'post',
                    dataType:"json",
                    success: function (response) {
                        if (response.success) {
                            $(".checkSetting").hide();
                            $("#smtpResponce").show();
                            $("#smtpResponce").html("<span class='btn btn-success btn-ok'>Configuration Setting Success</span>");
                        } else {
                            $(".checkSetting").show();
                            $("#smtpResponce").show();
                            $("#smtpResponce").html("<span class='btn btn-danger btn-ok'>Configuration Setting Failed</span>");
                            //window.location.reload(1);
                        }
                        setTimeout(function () {
                            $("#smtpResponce").html('<img style="width:30px;" src="{{ asset('asset/img/loader.gif') }}" />');
                            $("#smtpResponce").hide();
                        }, 3000);
                    }
                });
            });
        });
    </script>
@endpush

