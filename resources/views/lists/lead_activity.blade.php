@extends('layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
@php
    $email="";
    if(!empty($smtp->from_email))
    {
        $from_email = $smtp->from_email;
    }
    else
    {
        $from_email = "";
    }
@endphp
<style>
    .callLeadHide {
  cursor:no-drop;
}
</style>
<div class="content-wrapper dialer">
        <section class="content-header">
                <h1>
                   <b>Lead Activity</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Lead Management</li>
                    <li class="active">Lead Activity</li>
                </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row" >
            @if(Request::get('from') == '')
                <div class="col-xs-12" >
                    <div class="box">
                        <div class="box-body">
                            <form class="form-inline form-dialer" acttion="javascript:void(0);">
                                @csrf
                                <div class="row-fluid">
                                    <div class="form-group">
                                        <input type="" onkeypress="return isNumberKey($(this));" pattern="[0-9]+" id="phone_number" name="phone_number" value="{{(isset($_GET['phone_number']) ? $_GET['phone_number'] : '' )}}" class="form-control" minlength="10" maxlength="12" placeholder="Phone Number" required/>
                                    </div>
                                    <div class="form-group">
                                        <button type="button" id="search_lead" class="btn btn-success waves-effect waves-light m-l-10" value="Search">Search</button>
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
            @endif
        </div><!-- /.row -->
        @if(!empty($updateData))
        <div class="row">
            <!-- Lead data start -->

            <div class="col-xs-5">
                <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                @foreach($leadDataArr as $lead)
                                    @if($lead['title'] == 'First Name')
                                        {{$lead['value']}}
                                    @endif
                                    @if($lead['title'] == 'Last Name')
                                        &nbsp;{{$lead['value']}}
                                    @endif
                                @endforeach


                                &nbsp;&nbsp;&nbsp;<i class='fa fa-phone'></i> {{Request::get('phone_number')}}
                                <br>
                                     @foreach($leadDataArr as $lead)
                                        @if(stristr($lead['title'], 'mail') && $lead['value'] != '')
                                        @php
                                        @$email = $lead['value'];
                                        @endphp
                                            <span style="font-size:15px;"><i class='fa fa-envelope'></i> {{$lead['value']}}</span>
                                        @endif
                                    @endforeach
                            </h3>


                            @if(!empty($leadDataArr))
                                <div class="pull-right">

                                    <a class="btn btn-sm btn-primary" href='{{url('/lead-data')}}/{{$leadId}}/{{Request::get('phone_number')}}'><i class='fa fa-edit'></i> Edit</a>
                                </div>
                            @endif
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <tbody>
                                        @if(!empty($leadDataArr))
                                            @foreach($leadDataArr as $lead)
                                                <tr>
                                                    <td><a href="#">{{$lead['title']}}</a></td>
                                                    <td style="text-align: right;">
                                                        @if(isset($lead['is_dialing']) && $lead['is_dialing'] == '1')
                                                            <i class='fa fa-phone'></i>
                                                            @if($lead['value'] != '')
                                                                {{$lead['value']}}
                                                            @else
                                                                {{Request::get('phone_number')}}
                                                            @endif
                                                        @else
                                                            {{$lead['value']}}
                                                        @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                        <tr style="text-align: center;"><td><b>No Lead Data Found</b></td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>
            </div>
            <!-- Lead data ends -->

            <!-- Update data starts -->
            <div class="col-xs-4" >
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Updates</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body FixedHeightContainer" style="">
                        <ul class="products-list product-list-in-box" style='max-height: 500px;overflow-y:auto;background:#fff;'>
                            @foreach($updateData as $upd)
                                @if(isset($arrUser[$upd->extension]) || (isset($arrUserIdExtMap[$upd->extension]) && isset($arrUser[$arrUserIdExtMap[$upd->extension]])))
                                    <li class="item">
                                        <div class="">
                                            <b>
                                                @if($upd->platform == 'sms')
                                                    {{$arrUser[$arrUserIdExtMap[$upd->extension]]['first_name']}} {{$arrUser[$arrUserIdExtMap[$upd->extension]]['last_name']}} ({{$arrUser[$arrUserIdExtMap[$upd->extension]]['extension']}})
                                                @elseif($upd->platform == 'comment')

                                                {{$arrUser[$upd->extension]['first_name']}} {{$arrUser[$upd->extension]['last_name']}} ({{$arrUser[$upd->extension]['extension']}})

                                                @else
                                                    {{$arrUser[$upd->extension]['first_name']}} {{$arrUser[$upd->extension]['last_name']}} ({{$arrUser[$upd->extension]['extension']}})
                                                @endif

                                            </b>
                                            @if($upd->platform == 'comment')
                                            Comments : {{$upd->comment}}
                                            @endif
                                            @if($upd->platform == 'cdr')
                                                @if($upd->route == 'IN')
                                                    received a call from
                                                @else
                                                    made a
                                                    @if($upd->type == 'manual')
                                                       manual
                                                    @endif
                                                    call to
                                                @endif
                                                {{$upd->number}}  
                                                @if($upd->type == 'manual')
                                                @if(empty($upd->disposition_id))
                                                <div id="stop_{{$upd->id}}">
                                                <input type="hidden" class="cdr_id" value="{{$upd->id}}" />
                                                <select class="check_dispo" onchange="clicktochange('{{$upd->id}}')" id="check_dispo_{{$upd->id}}" name="check_dispo">
                                                    <option value="">select disposition</option>
                                                    @foreach($disposition_list as $dispo)
                                                    <option value="{{$dispo->id}}_{{$dispo->title}}">{{$dispo->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                                @endif
                                                @endif

                                                @foreach($disposition_list as $dispo)
                                                    @if($dispo->id  == $upd->disposition_id)
                                                        and as mark the call <b>{{$dispo->title}}</b>
                                                    @endif
                                                @endforeach

                                               <span  style="display: none;"id="hide_{{$upd->id}}"> and as mark the call <b id="dis_{{$upd->id}}"></b></span>



                                            @elseif($upd->platform == 'sms')
                                                sent a text to {{$upd->number}} <a onclick="open_msg_fax_detail_popup('{{$upd->message}}');" title="View Details" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                                            @elseif($upd->platform == 'fax')
                                                sent a fax to {{$upd->dialednumber}} <a onclick="open_msg_fax_detail_popup('{{$upd->faxurl}}');" title="View Details" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                                            @endif
                                            <br>
                                            <a href="javascript:void(0)" class="product-title">
                                                <span style="font-size:12px;" class="label label-warning pull-right">{{$upd->start_time}}</span>
                                            </a>
                                            <span class="product-description">
                                                @if($upd->platform == 'cdr')
                                                    {{$upd->duration_in_time_format}}
                                                    @if(Session::get('role') !== null && in_array(Session::get('role'), ['admin', 'manager', 'super_admin']))
                                                        <a title="Play Recording" target="_blank" href="{{$upd->call_recording}}"><i class="fa fa-play"></i></a>
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Updates ends -->

            <!-- CrM Utilities -->
            <div class="col-xs-3">
                <!-- Updates ends -->
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">CRM Utilities</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" style="">
                        <ul class="products-list product-list-in-box">
                            <li class="item">
                                <div class="">
                                    <a id="btn_send_email" data-emailid="{{$email}}" data-leadid="{{$leadId}}" data-listid="39" href="javascript:void(0)" class="product-title"><i class="fa fa-envelope"></i> Send Email</a>
                                    <span class="product-description"></span>
                                </div>
                            </li>
                            <li class="item">
                                <div class="">

                                     <input type="hidden" name="lead-detail-class-box" class="lead-detail-class-box" value="{{$leadId}}">
                                    <a style="cursor: pointer;" data-contact="{{Request::get('phone_number')}}" data-toggle="modal" data-target="#myModal_open" id="btn_send_sms" class="product-title"><i class="fa fa-commenting-o"></i> Send Text</a>

                                    <span class="product-description"></span>
                                </div>
                            </li>
                            <li class="item">
                                <div class="">


                                    <input type="hidden" name="extension" class="extension" class="lead-detail-class-box" value="{{Session::get('extension')}}">
                                    <input type="hidden" name="alt_extension" class="alt_extension" value="{{Session::get('private_identity')}}">
                                    <input type="hidden" name="extension" class="phone_number" class="lead-detail-class-box" value="{{Request::get('phone_number')}}">
                                    <a disabled href="javascript:void(0)"  class="product-title callLead call"><i class="fa fa-phone"></i> Call Lead</a>

                                </div>
                            </li>
                            <li class="item">
                                <div class="">
                                    <a target="_blank" href="{{url('/send-fax')}}" class="product-title"><i class="fa fa-fax"></i> Send Fax</a>
                                    <span class="product-description"></span>
                                </div>
                            </li>

                            <li class="item">
                                <div class="">
                                    <a target="_blank" style="cursor: pointer;" class="product-title send-to-crm"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send To CRM</a>

                                    <span class="product-description"></span>
                                </div>
                            </li>
                        </ul>
                    </div>


                </div>

                <!-- Updates ends -->

                <!-- Live Call -->
                <div class="box box-primary box-solid" id="view_Call_details" @if(empty($live_call)) style="display: none;" @endif>
                    <div class="box-header with-border">
                        <h3 class="box-title">Live Call</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body table-responsive" style="" id="result_call" )>
                        @foreach($live_call as $call)

                        @php
                        $duration_time = $call->start_time;
                        @endphp

                        <table style="width:100%" class="table"><tr><th>Extension:</th><td><?php echo $call->extension; ?></td></tr><tr><th>Number:</th><td><?php echo $call->number; ?></td></tr><tr><th>Start Time:</th><td><?php echo $call->start_time; ?></td></tr><tr><th>Duration:</th><td id="countup"><span class="timeel hours">00</span>:<span class="timeel minutes">00</span>:<span class="timeel seconds">00</span></td></tr></table>
                        @endforeach

                    </div>
                </div>

                <!-- End Live Call -->
            </div>
            <!-- CrM Utilities Ends-->



        </div>
        @endif


</div>
</section>
</div>

<!---Msg / fax detail popup--->
<div class="modal fade" id="msg_fax_detail_popup" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="msg_fax_detail_title">Details</h4>
            </div>
            <div class="modal-body">
                <p id="msg_fax_detail_text"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Email Model Open -->
<div class="modal fade" id="sendMailModel" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->

                        <form method="post" id="emailSend">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Template</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <span id="responseMessage"></span>
                                <div class="modal-body" style="background: white !important;">
                                    <div class="box-body">
                                        <input type="hidden" name="lead_id" id="lead_id" value=""/>
                                        <input type="hidden" name="list_id" id="list_id" value=""/>

                                        <div class="col-md-6">
                                            <label>To</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" value="{{$email}}" class="form-control" name="to" id="toEmailId" @if(!empty($email)) disabled @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>From</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" class="form-control" name="from" value="{{$from_email}}" id="fromEmail" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Templates</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select id="templates" class="form-control" autocomplete="off" style="width: 100%;">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <span id="setBoxValue" style="display: none;"></span>
                                            <label>Lead Placeholders</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select id="multiple_labels" class="form-control" autocomplete="off" style="width: 100%;" @if(empty($email)) disabled @endif>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Sender Placeholders</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select id="multiple_names" class="form-control" autocomplete="off" style="width: 100%;" @if(empty($email)) disabled @endif>
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
                                                <textarea type="text" class="form-control" name="body" value="" id="editor1"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="modal-footer">
                                    <a id="checkResponce" style="display: none;"> <img style="width:30px;" src="{{ asset('asset/img/loader.gif') }}"/></a>
                                    <button type="button" id="send-email" class="btn btn-info">Send</button>


                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- send to crm -->

                <div class="modal fade" id="sendtocrmselection" role="dialog">
                    <!-- Modal content-->
                    <div class="modal-dialog">
                        <div class="modal-content" style="background-color: #3cc7c0 !important;color:white;">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myModalLabel">Click Url to Send to CRM</h4>
                            </div>
                            <div class="modal-body">
                                <p>Please click to url which u want to send to crm<b><i class="title"></i></b></p>
                               <div id="showUrls" style="color:white;"></div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <!-- <button type="button" class="btn btn-danger btn-ok deleteExtension">Delete</button> -->
                            </div>
                        </div>
                    </div>
                </div>



                <!-- end send to crm -->
<!-- close -->

@include('sms.send_message')
            @push('scripts')
                <script>
                    var base_url = "<?php echo URL::to('/'); ?>";
                </script>
                <script type="text/javascript" src="{{ URL::asset ('asset/js/send_sms_component.js') }}"></script>
            @endpush
@endsection

@push('scripts')

    @if(Request::get('phone_number') != '')
<script>
@if(!empty($duration_time))
countUpFromTime('{{$duration_time}}', 'countup');
    $(".call").addClass("callLeadHide");
    @else
    $(".call").removeClass("callLeadHide");

@endif
</script>
@endif
<script>
    @if(Request::get('phone_number') != '' && empty($arrUser))
        toastr.error("No lead data found.");
    @endif

    $(function () {
        $("#search_lead").click(function () {
            if($("#phone_number").val().length < 10 || $("#phone_number").val().length > 12) {
                toastr.error('Phone number must be more than 10 and less than 13 digits');
                return;
            } else {
                var redirectUrl = "{{url('/lead-activity')}}";
                window.location.href = redirectUrl + "?phone_number=" + $("#phone_number").val();
            }

        });

        $("#phone_number").on('keyup', function(){
            if($(this).val().length > 12) {
                toastr.error('Phone number must be more than 10 and less than 13 digits');
                return;
            }
        });
    });

    function open_msg_fax_detail_popup(text) {
        $("#msg_fax_detail_text").text(text);
        $("#msg_fax_detail_popup").modal();
    }

</script>

    <script src="{{ asset("asset/plugins/ckeditor/ckeditor.js") }}"></script>

    <script language="javascript">
        $(function () {
            CKEDITOR.config.autoParagraph = false;
            CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
            CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_P;
            CKEDITOR.replace('editor1', {
                enterMode: CKEDITOR.ENTER_BR,
                filebrowserUploadUrl: "{{route('start-dialing.upload', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form',
                allowedContent: true
                
            });

            CKEDITOR.instances['editor1'].on('contentDom', function () {
                this.document.on('click', function (event) {
                    console.log('abh');
                    $('#setBoxValue').html('');
                });
            });

            $("#multiple_labels").on('change', function () {
                //console.log($(this).val());
                var label_name = $(this).val();
                var lead_id = $("#lead_id").val();
                var list_id = $("#list_id").val();


                $.ajax({
                    url: 'getLabelValue/' + label_name + '/' + list_id + '/' + lead_id,
                    type: 'get',
                    success: function (response) {

                        //console.log($(this).val());
                        var hidden_box = $('#setBoxValue').html();
                        if (hidden_box == 'subject_box')
                        {
                            var cursorPos = $('#subject').prop('selectionStart');
                            var v = $('#subject').val();
                            console.log(v);

                            var textBefore = v.substring(0, cursorPos);
                            var textAfter = v.substring(cursorPos, v.length);
                            $('#subject').val(textBefore + response + textAfter);
                        }
                        else
                        {
                            for (var i in CKEDITOR.instances) {

                                console.log(response);
                                CKEDITOR.instances[i].insertHtml(response);
                            }
                        }
                    }
                });
            });

            $("#multiple_names").on('change', function () {
                console.log($(this).val());
                var sender_id = $(this).val();
                $.ajax({
                    url: 'getSenderValue/' + sender_id,
                    type: 'get',
                    success: function (response) {
                        var hidden_box = $('#setBoxValue').html();
                        if (hidden_box == 'subject_box')
                        {
                            var cursorPos = $('#subject').prop('selectionStart');
                            var v = $('#subject').val();
                            console.log(v);

                            var textBefore = v.substring(0, cursorPos);
                            var textAfter = v.substring(cursorPos, v.length);
                            $('#subject').val(textBefore + response + textAfter);
                        }
                        else
                        {
                            for (var i in CKEDITOR.instances) {
                                CKEDITOR.instances[i].insertHtml(response);
                            }
                        }
                    }
                });
            });

            $("#templates").on('change', function () {
                console.log("Template change called");
                var output = '';
                CKEDITOR.instances['editor1'].setData(output);

                var template_id = this.value;
                var lead_id = $("#lead_id").val();
                var list_id = $("#list_id").val();

                $.ajax({
                    url: 'getTemplate/' + template_id + '/' + list_id + '/' + lead_id,
                    type: 'get',
                    success: function(response){
                        $("#subject").val(response['subject']);
                        for (var i in CKEDITOR.instances) {
                            CKEDITOR.instances[i].insertHtml(response['template_html']);
                            var editor = CKEDITOR.instances[i];
                            editor.on('contentDom', function () {
                                var editable = editor.editable();
                                editable.attachListener(editable, 'click', function () {
                                    console.log("click event");
                                    $('#setBoxValue').html('');
                                });
                            });
                        }
                    }
                });
            });

            $('#subject').on('click', function () {
                $('#setBoxValue').html('subject_box');
            });
        });


    /*send email code */
    $('#btn_send_email').click(function () {
            var lead_id = $(this).attr('data-leadid');
            var list_id = $(this).attr('data-listid');
            var toEmailId = $("#toEmailId").val();

            $("#lead_id").val(lead_id);
            $("#list_id").val(list_id);
            $("#responseMessage").html('');

            //Rest the template selection
            $('#templates option[value=""]').attr('selected','selected');
            $('#subject').val('');
            $('#multiple_labels').val('');
            $('#multiple_names').val('');
            CKEDITOR.instances['editor1'].setData('');

            console.log("btn_send_email: " + toEmailId);
            $.ajax({
                url: '/openMailModal',
                type: 'post',
                data: {
                    lead_id: lead_id,
                    smtpType: '1',
                    campaign_id: {{$campaignId}},
                    list_id: list_id,
                    toEmailId: toEmailId
                },
                success: function (response) {

                    templates_line = "<option value=''>Compose New</option>";
                    for (var i = 0; i < response['email_templates'].length; i++) {
                        var email_template = response['email_templates'][i];
                        //alert(email_template);
                        templates_line += "<option value='" + response['email_templates'][i]['id'] + "'>";
                        templates_line += response['email_templates'][i]['template_name'];
                        templates_line += "</option>";
                    }



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
                error: function (xhr, status, error) {
                    $("#responseMessage").html('<div class="alert alert-danger" id="alert-errors">'+xhr.responseText+'</div>');
                }
            });
            $('#sendMailModel').modal('show');
        });


  

    function clicktochange(val)
    {
        check_dispo = $("#check_dispo_"+val).val();
        disposition_id = check_dispo.split("_");
        //alert(myArray[0]);
        //alert(myArray[1]);

        cdr_id = val;

         $.ajax({
                url: '/change-disposition',
                type: 'post',
                data: {
                    disposition_id: disposition_id[0],
                    cdr_id: cdr_id
                },
                success: function (response)
                {   
                    $("#stop_"+cdr_id).hide();
                    $("#dis_"+cdr_id).text(disposition_id[1]);
                    $("#hide_"+cdr_id).show();
                },
                error: function (xhr, status, error) {
                    //$("#responseMessage").html('<div class="alert alert-danger" id="alert-errors">'+xhr.responseText+'</div>');
                }
            });


    }



    $('#send-email').bind('click', function (event) {

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
                    smtpType: '1',
                    campaign_id: {{$campaignId}}
                },

                success: function (response) {
                    console.log(response);
                    $("#responseMessage").show();
                    $("#checkResponce").hide();
                    if (response.success) {
                        $("#responseMessage").html("<div class='alert alert-success alert-block'>Mail Send Successfully</div>");
                    } else {
                        $("#responseMessage").html("<div class='alert alert-danger alert-block'>" + response.message + "</div>");
                    }
                    setTimeout(function () {
                        $("#responseMessage").hide();
                    }, 3000);
                }
            });
        });
    /* close*/

    /* send to crm  code */

    $('.send-to-crm').click(function ()
    {
        var num = $('#lead-detail').attr('data-number');
        var lead_id_1 = $('.lead-detail-class-box').attr('value');

        lead_id_1 = {{$leadId}};
        campaign_id = {{$campaignId}};
        num = {{Request::get('phone_number')}};

        $.ajax({
            type: 'POST',
            url: '/sendToCrm',
            data:
            {
                campaign: campaign_id,
                lead: lead_id_1,
                number: num,
                "_token": "{{ csrf_token() }}"
            },

            success: function (data)
            {
                var res = $.parseJSON(data);
                if (res.status == "false")
                {
                    alert(res.message);
                }
                count = Object.keys(res.url).length;
                if (res.status == "success")
                {
                    if(count == 1)
                    {
                        window.open(res.url, '_blank');
                    }
                    else
                    {
                        $("#sendtocrmselection").modal();
                        var html='';
                        for(i=0;i<count;i++)
                        {
                            html+='<p><a target="_blank" style="color:white;background: yellowgreen;" href="'+res.url[i]+'">'+res.main_url[i]+'</a></p>';
                        }

                        $("#showUrls").html(html);
                    }
                }

                else
                {
                    $("#detail-message").addClass('text-success').removeClass('text-danger').html(res.message);
                }
            }
        });
    });

</script>
@endpush
