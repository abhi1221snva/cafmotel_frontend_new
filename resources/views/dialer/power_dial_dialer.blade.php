@extends('layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper dialer">
    <!-- Content Header (Page header) -->
    <section class="content-header">
                <h1>
                   <b> Dialer</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> Dialer</li>
                </ol>
        </section>
       
    <!-- Main content -->
    <section class="content">
        <div class="row" >
            <div class="col-xs-12" >
                <div class="box">
                    <div class="box-body">
                        <form class="form-inline form-dialer" method="post">
                            @csrf
                            <div class="row-fluid">
                                <div class="form-group">
                                    <select name="campaign" class="form-control" required="" id="campaign">
                                        <option value="">Select Campaign</option>
                                        @if (!empty($campaign->data))
                                        @foreach($campaign->data as $key => $value)
                                        <option value="{{$value->id}}">{{$value->title}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="start_dialing" name="submit"
                                        class="btn btn-success waves-effect waves-light m-l-10"
                                        value="Search">StartDialing</button>
                                </div>
                                <div class="form-group" style="margin-left: 15%;">
                                    <div class="row text-danger text-center" id="form_error">
                                        @if(!empty($message))
                                        {{ $message }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if (!empty($campaignDetail) )
            <div class="col-xs-12 ">
                <div class="col-xs-7" style="margin-left: -1%;">
                    <div class="col-6 showDiv" style="display: none;">
                        <!-- Widget: user widget style 1 -->
                        <div class="box box-widget widget-user-2" style="margin-bottom: 16%;">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-yellow" style="padding: 7px;">
                                <div class="widget-user-image">
                                    <img class="img-circle" id="lead-img"
                                        src="{{ asset('asset/img/user-128x128.png') }}" alt="Lead" title="">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widgetUserUsername widget-user-username"></h3>
                                <h5 class="widget-user-desc widgetUserDesc"></h5>
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked leadBoxUser"></ul>
                                <ul class="nav nav-stacked ">
                                    <li>
                                        <textarea class="form-control comment" rows="3"
                                            style="width: 95%;margin-left: 3%;" placeholder="Enter comment"></textarea>
                                        <input type="hidden" name="lead_id" id="lead_id" value="" />
                                    </li>
                                    <li>
                                        <div class="m-5 start-dial-btn" style="margin-left: 0%;margin-top: 5%;">
                                            <span class="start-dial-action-btn" id="block-reload-lead"
                                                style="display: none;">
                                                <button type="button" name="reload-lead" id="reload-lead"
                                                    style="margin: 5px;" class="btn btn-primary btn-sm"
                                                    value="retry">Retry</button>
                                            </span>
                                            <span class="start-dial-action-btn">
                                                <button type="button" name="hangup" id="btn-hangup" style="margin: 5px;"
                                                    class="btn btn-danger btn-sm hang-up" value="hangup">HangUp</button>
                                            </span>
                                            <span class="start-dial-action-btn">
                                                <button type="button" name="dialPad" style="margin: 5px;" id="dialPad"
                                                    class="btn btn-success btn-sm" value="dialPad">Dial Pad</button>
                                            </span>
                                            @if($campaignDetail['send_crm'] == 1)
                                            <span class="start-dial-action-btn">
                                                <button type="button" style="margin: 5px;"
                                                    class="btn btn-success btn-sm send-to-crm">Send To Crm</button>
                                            </span>
                                            @endif

                                            @if(intval($campaignDetail['email']) > 0)
                                            <span class="start-dial-action-btn">
                                                <button type="button" style="margin: 5px;" id="btn_send_email"
                                                    data-emailid="" data-leadid="" data-listid=""
                                                    class="btn btn-success btn-sm btn_send_email">Send Email</button>
                                            </span>
                                            @endif

                                            @if($campaignDetail['sms'] == 1)
                                            <span class="start-dial-action-btn">
                                                <button type="button" style="margin: 5px;" data-contact=""
                                                    data-toggle="modal" data-target="#myModal_open"
                                                    class="btn btn-success btn-sm" id="btn_send_sms">Send Sms</button>
                                            </span>
                                            @endif

                                            <span class="start-dial-action-btn">
                                                @if (Session::get('vm_drop')==0)
                                                <button type="button" name="voicemail-drop" style="margin: 5px;"
                                                    value="voicemail-drop"
                                                    class="btn btn-success btn-sm voicemail-drop-empty"
                                                    data-toggle="modal" data-target="#myModal">
                                                    Voicemail Drop
                                                </button>
                                                @else
                                                <button type="button" name="voicemail-drop" style="margin: 5px;"
                                                    value="voicemail-drop"
                                                    class="btn btn-success btn-sm voicemail-drop">
                                                    Voicemail Drop
                                                </button>
                                                @endif
                                            </span>
                                        </div>
                                        <div id="detail-message" class=""></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div style="display:none;">
                            <span id="lead-detail" class="lead-detail-class" data-number='' data-lead=''></span>
                            <input type="hidden" name="lead-detail-class-box" class="lead-detail-class-box"
                                value="lead-detail-class-box">
                        </div>
                        <!-- /.widget-user -->
                    </div>
                </div>

                <div class="col-xs-4">

                    <div class="col-xs-12 disposition" id="disposition-panel" style="display: none;">
                        <div class="box box-widget widget-user-2">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-yellow" style="padding: 4px;">
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username"
                                    style="font-size: large;font-weight: bold;margin-left: 26%;font-size: 12 px;">Select
                                    Disposition</h3>
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked call-disposition">
                                    @if(!empty($dispositionDetail))
                                    @foreach($dispositionDetail as $item=>$value)
                                    @if($value->d_type == 3)
                                    <?php
                                                        $count = count($dispositionDetail) - 1;
                                                        $tmp = $dispositionDetail[$count];
                                                        $dispositionDetail[$count] = $dispositionDetail[$item];
                                                        $dispositionDetail[$item] = $tmp;
                                                        ?>
                                    @endif
                                    @endforeach
                                    @foreach($dispositionDetail as $item=>$value)
                                    @if($value->d_type == 3)
                                    <li class="callback-dis">
                                        <a href="#" style="padding:0px;">
                                            <label for="disposition-radio-{{$value->id}}" class="disposition-label">
                                                {{$value->title}}
                                                <span class="pull-right">
                                                    <input type='radio' class="radio_sms"
                                                        id="disposition-radio-{{$value->id}}" name='disposition'
                                                        value='{{$value->id}}' />
                                                </span>
                                            </label>
                                        </a>
                                        <div id="datetimepicker" class="dpicker">
                                            <span class="add-on input-group-addon bg-primary text-white b-0">
                                                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                            </span>
                                            <input type="text" name="callback_time" class="form-control" value=""
                                                id="callback-time" disabled />
                                        </div>
                                    </li>
                                    @else
                                    <li>
                                        <a href="#" style="padding:0px;">
                                            <label for="disposition-radio-{{$value->id}}" class="disposition-label">
                                                {{$value->title}}
                                                <span class="pull-right">
                                                    <input type='radio' class="radio_sms"
                                                        id="disposition-radio-{{$value->id}}" name='disposition'
                                                        value='{{$value->id}}' />
                                                </span>
                                            </label>
                                        </a>
                                    </li>
                                    @endif

                                    @endforeach
                                    @endif
                                    <li>
                                        <div class="row-fluid save-disposition-block">
                                            <div class="pull-left">
                                                <input type="checkbox" id="pause_calling" /> <label
                                                    for="pause_calling">&nbsp;&nbsp;Pause Calling</label>
                                            </div>
                                            <button type="button" name="submit" id="save-disposition"
                                                class="btn btn-success" value="save" disabled> Save</button>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row text-center" id="disposition_error"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <input type="hidden" name="csrf-token" id="csrf-token" value="{{ csrf_token() }}" />
                        <!-- /.box -->
                    </div>
                </div>
            </div>
            @endif

            <div class="modal fade" id="sendMailModel" role="dialog">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->

                    <form method="post" id="emailSend">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Template</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <span id="responseMessage"></span>
                            <div class="modal-body" style="background: white !important;">
                                <div class="box-body">
                                    <input type="hidden" name="lead_id" id="lead_id" value="" />
                                    <input type="hidden" name="list_id" id="list_id" value="" />

                                    <div class="col-md-6">
                                        <label>To</label>
                                        <div class="input-daterange input-group col-md-12">
                                            <input type="text" class="form-control" name="to" id="toEmailId" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label>From</label>
                                        <div class="input-daterange input-group col-md-12">
                                            <input type="text" class="form-control" name="from" value="" id="fromEmail"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Templates</label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select id="templates" class="form-control" autocomplete="off"
                                                style="width: 100%;">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <span id="setBoxValue" style="display: none;"></span>
                                        <label>Lead Placeholders</label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select id="multiple_labels" class="form-control" autocomplete="off"
                                                style="width: 100%;">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Sender Placeholders</label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select id="multiple_names" class="form-control" autocomplete="off"
                                                style="width: 100%;">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Subject</label>
                                        <div class="input-daterange input-group col-md-12">
                                            <input type="text" class="form-control" name="subject" id="subject">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Templete Preview</label>
                                        <div class="input-daterange input-group col-md-12">
                                            <textarea type="text" class="form-control" name="body" value=""
                                                id="editor1"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="modal-footer">
                                <a id="checkResponce" style="display: none;"> <img style="width:30px;"
                                        src="{{ asset('asset/img/loader.gif') }}" /></a>
                                <button type="button" id="send-email" class="btn btn-info">Send</button>


                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="dialPadModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close dialpad-close-btn" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Dialpad</h4>
                        </div>
                        <div class="modal-body">
                            <div class="phone-dialpad">
                                <div class="dialpad compact">
                                    <div class="number digit-number"></div>
                                    <div class="dials">
                                        <ol>
                                            <li class="digits" data-number="1">
                                                <p><strong>1</strong></p>
                                            </li>
                                            <li class="digits" data-number="2">
                                                <p><strong>2</strong></p>
                                            </li>
                                            <li class="digits" data-number="3">
                                                <p><strong>3</strong></p>
                                            </li>
                                            <li class="digits" data-number="4">
                                                <p><strong>4</strong></p>
                                            </li>
                                            <li class="digits" data-number="5">
                                                <p><strong>5</strong></p>
                                            </li>
                                            <li class="digits" data-number="6">
                                                <p><strong>6</strong></p>
                                            </li>
                                            <li class="digits" data-number="7">
                                                <p><strong>7</strong></p>
                                            </li>
                                            <li class="digits" data-number="8">
                                                <p><strong>8</strong></p>
                                            </li>
                                            <li class="digits" data-number="9">
                                                <p><strong>9</strong></p>
                                            </li>
                                            <li class="digits" data-number="*">
                                                <p><strong>*</strong></p>
                                            </li>
                                            <li class="digits" data-number="0">
                                                <p><strong>0</strong></p>
                                            </li>
                                            <li class="digits" data-number="#">
                                                <p><strong>#</strong></p>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn dialpad-close-btn"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div><!-- /.row -->


        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Warning Header</h4>
                    </div>
                    <div class="modal-body">
                        <p>You have not recorded voicemail drop message for your extension. Please call *88 from your
                            extension to record your personalized voicemail drop message and relogin.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        @include('sms.send_message')
        @push('scripts')
        <script>
        var base_url = "<?php echo URL::to('/'); ?>";
        </script>
        <script type="text/javascript" src="{{ URL::asset ('asset/js/send_sms_component.js') }}"></script>
        @endpush
    </section><!-- /.content -->
</div>

<!-- /.content-wrapper -->
@endsection
@push('styles')

<link rel="stylesheet" type="text/css" media="screen"
    href="{{ asset('asset/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}">
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('asset/css/power_dial.css') }}">
<style>
@if( !empty($campaignDetail) && ($campaignDetail['dial_mode']=='predictive_dial'|| $campaignDetail['dial_mode']=='super_power_dial')) #call-btn {
    display: none;
}

.disposition-label {
    width: 100%;
    height: 100%;
    padding: 10px 15px;
    margin-bottom: 0px;
}

.lead-save-status {
    font-size: 120%;
}

@endif
</style>
<link rel="stylesheet" type="text/css" media="screen"
    href="{{ asset('asset/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}">
@endpush
@push('scripts')
<script src="{{ asset('asset/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset("asset/plugins/ckeditor/ckeditor.js") }}"></script>
<script src="{{ asset("asset/js/power_dial.js") }}"></script>


<script type="text/javascript">
@if(!empty($campaignDetail))

$('#btn_send_email').click(function() {
    var lead_id = $(this).data('leadid');
    var list_id = $(this).data('listid');
    var toEmailId = $(this).data('emailid');

    $("#lead_id").val(lead_id);
    $("#list_id").val(list_id);
    $("#toEmailId").val(toEmailId);
    $("#responseMessage").html('');

    //alert(lead_id);
    $.ajax({
        url: '/openMailModal',
        type: 'post',
        data: {
            lead_id: lead_id,
            smtpType: {{intval($campaignDetail['email'])}},
            campaign_id: {{$campaignDetail['id']}},
            list_id: list_id,
            toEmailId: toEmailId
        },
        success: function(response) {

            templates_line = "<option value=''>Compose New</option>";
            for (var i = 0; i < response['email_templates'].length; i++) {
                var email_template = response['email_templates'][i];
                //alert(email_template);
                templates_line += "<option value='" + response['email_templates'][i]['id'] + "'>";
                templates_line += response['email_templates'][i]['template_name'];
                templates_line += "</option>";
            }

            @if($campaignDetail['email'] == 1)
            $("#fromEmail").val("{{ session("emailId") }}");
            @else
            $("#fromEmail").val(response['smtpSetting']['from_email']);
            @endif

            $('#templates').html(templates_line);
            label_line = "<option value=''>Select to Insert</option>";
            for (var i = 0; i < response['labels'].length; i++) {
                var labels = response['labels'][i];
                label_line += "<option value='" + response['labels'][i]['id'] + "'>";
                //label_line += "<option value='[["+response['labels'][i]['title']+"]]'>";
                label_line += response['labels'][i]['title'];
                label_line += "</option>";
            }

            $('#multiple_labels').html(label_line);
            user_line = "<option value=''>Select to Insert</option>";
            for (var i = 0; i < response['user_column'].length; i++) {
                var user_column = response['user_column'][i];
                //alert(user_column);
                user_line += "<option value='[[" + user_column + "]]'>";
                user_line += user_column;
                user_line += "</option>";
            }
            $('#multiple_names').html(user_line);
            //window.location.reload(1);
        },
        error: function(xhr, status, error) {
            $("#responseMessage").html('<div class="alert alert-danger" id="alert-errors">' + xhr
                .responseText + '</div>');
        }
    });
    $('#sendMailModel').modal('show');
});

$('#send-email').bind('click', function(event) {
    event.preventDefault();
    from = $("#fromEmail").val();
    if (from == '' || from == undefined) {
        $('#from').css('border', '1px solid red');
        $("#from").focus();
        return false;
    }
    to = $("#toEmailId").val();
    if (to == '' || to == undefined) {
        $('#to').css('border', '1px solid red');
        $("#to").focus();
        return false;
    }
    subject = $("#subject").val();
    if (subject == '' || subject == undefined) {
        $('#subject').css('border', '1px solid red');
        $("#subject").focus();
        return false;
    }
    message = CKEDITOR.instances['editor1'].getData();

    if (message == '' || message == undefined) {
        $('#message').css('border', '1px solid red');
        $("#message").focus();
        return false;
    }

    $("#checkResponce").show();

    $.ajax({
        type: 'POST',
        url: 'send-email/generic',
        dataType: "json",
        data: {
            from: from,
            to: to,
            subject: subject,
            message: message,
            smtpType: {{intval($campaignDetail['email'])}},
            campaign_id: {{$campaignDetail['id']}}
        },
        success: function(response) {
            console.log(response);
            $("#responseMessage").show();
            $("#checkResponce").hide();
            if (response.success) {
                $("#responseMessage").html(
                    "<div class='alert alert-success alert-block'>Mail Send Successfully</div>");
            } else {
                $("#responseMessage").html("<div class='alert alert-danger alert-block'>" + response
                    .message + "</div>");
            }
            setTimeout(function() {
                $("#responseMessage").hide();
            }, 3000);
        }
    });
});

$('.dpicker').datetimepicker({
    format: 'MM/dd/yyyy hh:mm:ss',
    language: 'en'
});
var campaign_id = "{{$campaignDetail['id']}}";
var dial_mode = "{{$campaignDetail['dial_mode']}}";
var group_id = "{{$campaignDetail['group_id']}}";
var api = "{{$campaignDetail['api']}}";
var user_id = "{{$campaignDetail['user_id']}}";
var user_token = "{{$campaignDetail['user_token']}}";
var extension = "{{$campaignDetail['extension']}}";
var getLeadAttempt = 0;
$('#campaign').val(campaign_id);
$(".showDiv").show();
$("#start_dialing").prop('disabled', true);
getLead();
$('#lead-detail').on('click', '#call-btn', function() {
    event.preventDefault();
    event.stopPropagation();
    var num = $(this).data('number');
    $.ajax({
        type: 'POST',
        url: '/call-number',
        data: {
            number: num,
            campaign: campaign_id,
            lead: '1',
            "_token": "{{ csrf_token() }}",
            user_id: user_id,
            user_token: user_token,
            extension: extension
        },
        success: function(data) {
            var res = $.parseJSON(data);
            if (res.status == 'success') {
                $("#detail-message").addClass('text-success').removeClass('text-danger').html(res
                    .message);
            } else {
                $("#detail-message").addClass('text-danger').removeClass('text-success').html(res
                    .message);
            }
        }
    });
});
$('.dialer .hang-up').on('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    $('.dialer .hang-up').prop('disabled', true);
    $.ajax({
        type: 'POST',
        url: '/hang-up',
        data: {
            "_token": "{{ csrf_token() }}",
            user_id: user_id,
            user_token: user_token,
        },
        success: function(data) {
            var res = $.parseJSON(data);
            if (res.status == 'true') {
                $("#detail-message").addClass('text-success').removeClass('text-danger').html(res.message);
                $(".start-dial-btn :input").attr("disabled", true);
            } else {
                $("#detail-message").addClass('text-danger').removeClass('text-success').html(res.message);
            }
        }
    });
});
$('.digits').click(function() {
    var digit = $(this).data('number');
    $('.digit-number').append(digit);
    $.ajax({
        type: 'POST',
        url: '/dtmf',
        data: {
            digit: digit,
            user_id: user_id,
            user_token: user_token,
            extension: extension,
            "_token": "{{ csrf_token() }}"
        },
        success: function(data) {
            var res = $.parseJSON(data);
            if (res.status != "true") {
                $("#detail-message").addClass('text-danger').removeClass('text-success').html(res
                    .message);
            }
        }
    });
});
$('.voicemail-drop').click(function() {
    $.ajax({
        type: 'POST',
        url: '/voicemail-drop',
        data: {
            user_id: user_id,
            user_token: user_token,
            extension: extension,
            "_token": "{{ csrf_token() }}"
        },
        success: function(data) {
            var res = $.parseJSON(data);
            if (res.status == "true") {
                $("#detail-message").addClass('text-success').removeClass('text-danger').html(res
                    .message);
                $(".hang-up").trigger("click");
            }
        }
    });
});
$('.send-to-crm').click(function() {
    var num = $('#lead-detail').data('number');
    var lead_id_1 = $('.lead-detail-class-box').attr('value');
    //$('#lead-detail').html(lead_id);
    $.ajax({
        type: 'POST',
        url: '/sendToCrm',
        data: {
            id: user_id,
            user_id: user_id,
            user_token: user_token,
            campaign: campaign_id,
            lead: lead_id_1,
            number: num,
            "_token": "{{ csrf_token() }}"
        },
        success: function(data) {
            var res = $.parseJSON(data);
            if (res.status == "success") {
                window.open(res.url, '_blank');
            } else {
                $("#detail-message").addClass('text-success').removeClass('text-danger').html(res
                    .message);
            }
        }
    });
});

function getLead() {
    $(".widgetUserDesc").html("");
    $(".widgetUserUsername").html("");
    $(".widget-user-image").hide();
    $("#block-reload-lead").hide();
    $("#reload-lead").attr("disabled", true);
    getLeadAttempt++;
    $.ajax({
        type: 'POST',
        url: '/get-lead',
        data: {
            "_token": "{{ csrf_token() }}"
        },
        dataType: "json",
        success: function(res) {
            if (res.success) {
                getLeadAttempt = 0;
                var callBtn =
                    '&nbsp;<button type="button" name="call" id="call-btn" class="btn btn-success" value="' +
                    res.number + '" data-number="' + res.number + '">Call</button>';
                $("#detail-message").html('');
                $(".start-dial-btn :input").attr("disabled", false);
                $(".lead-detail-class").attr('data-number', res.number);
                $(".lead-detail-class").attr('data-lead', res.lead_id);
                $(".lead-detail-class-box").attr('value', res.lead_id);
                $("#lead-img").attr('title', "Lead id " + res.lead_id);
                $("#lead-detail").html(res.lead_id);
                $("#lead_id").val(res.lead_id);
                $('#btn_send_email').attr('data-leadid', res.lead_id);
                $('#btn_send_email').attr('data-listid', res.list_id);
                mobile_txt = '';
                $val_1 = '';
                $val_2 = '';
                leadBoxData = '';
                $.each(res.data, function(columnName, columnData) {
                    /* console.log(lead_id + '--------' + columnName + '=========' + columnData); */
                    if (columnData.is_dialing) {
                        mobile_val = columnData.value;
                        mobile_txt =
                            '<span class="pull-left ml-1" style="width: 19%;"><i class="fa fa-phone mr-6"></i>' +
                            columnData.value + '</span>';
                        $('#btn_send_sms').attr('data-contact', columnData.value);

                    } else if (/email/i.test(columnData.label)) {
                        $('#btn_send_email').attr('data-emailid', columnData.value);

                        email_txt = '<i class="fa fa-envelope mr-l"></i>';
                        $(".widgetUserDesc").html(email_txt + columnData.value + mobile_txt);
                    } else if (/first name/i.test(columnData.label)) {
                        $(".widgetUserUsername").html(columnData.value);
                        $val_1 = columnData.value;
                    } else if (/last name/i.test(columnData.label)) {
                        $val_2 = columnData.value;
                    }

                    if (columnData.is_visible) {
                        var columnHtml = columnData.value;
                        if (columnData.is_editable) {
                            columnHtml = "<span id='" + columnName +
                                "-save-status' class='lead-save-status'></span> <input type='text' name='" +
                                columnName + "' id='" + columnName +
                                "' onchange='updateLeadData(this, " + res.lead_id + ")' value='" +
                                columnData.value + "' />";
                        }
                        leadBoxData += '<li><a href="javascript:return false;"> <b>' + columnData
                            .label + '</b> <span class="pull-right">' + columnHtml +
                            '</span></a></li>';
                    }

                    if (columnData.alternate_phone == 1) {
                        var columnHtmlData = columnData.value;
                        leadBoxData += '<li><a> <b>'+columnData.label + '</b> <span class="margin-right-70"><button type="button" class="btn btn-info alternate_phone_btn" data-nxt_call='+columnHtml+' data-lead_id='+res.lead_id+' data-list_id='+res.list_id+'><i class="fa fa-phone" aria-hidden="true"></i>'+columnHtml+'</button></span></a></li>';
                    }

                });

                $(".widgetUserUsername").html($val_1 + ' ' + $val_2);
                $(".leadBoxUser").html(leadBoxData);
                $(".widget-user-image").show();
            } else {
                $('#lead-detail').html('');
                if (getLeadAttempt < 3) {
                    $("#detail-message").html("Please wait, populating lead information");
                    setTimeout(function() {
                        getLead();
                    }, 3000);
                } else {
                    $.when($.ajax({
                        type: "GET",
                        url: "/hopper-count/" + campaign_id,
                        dataType: "json"
                    })).then(function(data, textStatus, jqXHR) {
                        console.log(data);
                        if (data.success) {
                            if (data.count === 0) {
                                $(".leadBoxUser").html(
                                    '<div class="alert alert-warning"><h3><i class="icon fa fa-warning"></i> No leads in hopper</h3></div>'
                                );
                            }
                        }
                        $("#detail-message").html(
                            "Click on Retry button after leads are loaded and call is connected."
                        );
                        $("#block-reload-lead").show();
                        $("#reload-lead").attr("disabled", false);
                    });
                }
            }
        }
    });
}

function updateLeadData(input, leadId) {
    var values = {};
    values[input.name] = input.value;
    postData = {
        "_token": $("#csrf-token").val(),
        "values": values
    };

    $.ajax({
        type: "POST",
        url: "/lead/" + leadId,
        data: postData,
        dataType: "json",
        success: function(data) {
            if (data.success) {
                $("#" + input.name + "-save-status").addClass('text-success').removeClass('text-danger')
                    .html("<i class='icon fa fa-check'></i> ");
            } else {
                $("#" + input.name + "-save-status").addClass('text-danger').removeClass('text-success')
                    .html("<i class='icon fa fa-close'></i> ");
            }
        },
        error: function(xhr, status, error) {
            //expired csrf or session
            if (xhr.status === 419) {
                //request new csrf from server and try again
                if (upadteCsrfToken()) {
                    updateLeadData(input, leadId);
                    return;
                }
            }
            $("#" + input.name + "-save-status").addClass('text-danger').removeClass('text-success').html(
                "<i class='icon fa fa-close'></i> ");
        }
    });
}

function upadteCsrfToken() {
    $.ajax({
        async: false,
        type: "GET",
        url: "/get-csrf-token",
        dataType: "json",
        success: function(data) {
            $("#csrf-token").val(data.token);
            return true;
        },
        error: function(xhr, status, error) {
            return false;
        }
    });
}

$('#save-disposition').click(function(event) {
    $(this).attr("disabled", true);
    event.preventDefault();
    event.stopPropagation();
    var disposition = $(".call-disposition input:radio:checked").val();
    var callBack = $('#callback-time').val();
    $('#lead-detail').html('');
    if (disposition === undefined || disposition === null) {
        $("#disposition_error").removeClass('text-success').addClass('text-danger').html(
            "Please select disposition");
    } else {
        $("#disposition_error").html("");
        if (disposition == 4 && (callBack === undefined || callBack === null)) {
            $("#disposition_error").removeClass('text-success').addClass('text-danger').html(
                "Please add call back time");
        }
    }

    var comment = $('.comment').val();
    var pause_calling = ($('#pause_calling').is(":checked")) ? 1 : 0;
    var lead_id = $("#lead_id").val();
    var campaignDetail = {{ $campaignDetail['api']}};
    $.ajax({
        method: "POST",
        url: "/save-disposition",
        dataType: "json",
        data: {
            "_token": "{{ csrf_token() }}",
            user_id: user_id,
            user_token: user_token,
            disposition: disposition,
            campaign: campaign_id,
            lead: lead_id,
            api: campaignDetail,
            comment: comment,
            pause_calling: pause_calling,
            call_back_time: callBack
        }
    }).done(function(response) {
        if (response.status) {
            $("#disposition-panel").toggle();
            if (pause_calling == 1) {
                $('#lead-detail').html('');
                $("#detail-message").addClass('text-success').removeClass('text-danger').html(response
                    .message + ". Calling Paused");
                $('.start-dial-btn').addClass("hide");
                $('.showDiv').hide();
                $('#start_dialing').attr('disabled', false);
            } else {
                $(".leadBoxUser").html(
                    '<div class="alert alert-info"><h3><i class="icon fa fa-info"></i> Looking for next lead. Please wait...</h3></div>'
                );
                $("#detail-message").addClass('text-success').removeClass('text-danger').html(response
                    .message);
                getLead();
            }
            $('.comment').val('');
            $(this).attr("disabled", false);
        } else {
            $("#disposition_error").addClass('text-danger').removeClass('text-success').html(
                "Unable to save disposition");
        }
    }).fail(function() {
        $("#disposition_error").addClass('text-danger').removeClass('text-success').html(
            "Unable to save disposition");
    });
});
$("input[type=radio][name=disposition]").change(function() {
    $("#save-disposition").attr("disabled", false);
});
@endif
</script>
@endpush
