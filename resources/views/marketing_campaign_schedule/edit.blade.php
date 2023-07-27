@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <section class="content-header">
                <h1>
                   <b>Edit Marketing Schedule</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Marketing Campaigns</li>
                    
                    <li class="active">Edit Marketing Schedule</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                <a href="{{ url('/marketing-campaigns') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Marketing Campaign</a>
           </div>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <form method="post" name="userform" id="userform" action="">
                            @csrf
                            <div class="box-body">
                                <div class="modal-body">
                                    <div class="form-group m-b-10">

                                        <input type="hidden" name="created_by" value="{{Session::get('id')}}"/>
                                        <input type="hidden" name="schedular_id" value="{{$marketing_campaign_schedule['id']}}"/>
                                        <input type="hidden" value="{{$marketing_campaign_schedule['send']}}" name="send" id="send"/>


                                        <div class="col-md-4">
                                            <label>Marketing Campaign</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control" name="campaign_id" id="campaign_id">
                                                    @if(!empty($marketing_campaigns))
                                                        @foreach($marketing_campaigns as $skey => $clists)
                                                            <option
                                                                @if($marketing_campaign_schedule['campaign_id'] == $clists->id) selected=selected
                                                                @endif value="{{$clists->id}}">{{$clists->title}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Lists</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control" name="list_id" id="list_id">
                                                    <option value="">Select List</option>
                                                    @if(!empty($list))
                                                        @foreach($list as $skey => $clists)
                                                            <option
                                                                @if($marketing_campaign_schedule['list_id'] == $clists->list_id) selected=selected
                                                                @endif value="{{$clists->list_id}}">{{$clists->list}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        @if($marketing_campaign_schedule['send'] == 1)
                                            <div class="col-md-4">
                                                <label>Email Template</label>
                                                <div class="input-daterange input-group col-md-12">
                                                    <select class="form-control" name="email_template_id" id="email_template_id">
                                                        @if(!empty($email_templates))
                                                            @foreach($email_templates as $skey => $clists)
                                                                <option
                                                                    @if($marketing_campaign_schedule['email_template_id'] == $clists->id) selected=selected
                                                                    @endif value="{{$clists->id}}">{{$clists->template_name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label>SMTP for sending email [Host - From Email - From Name]</label>
                                                <div class="input-daterange input-group col-md-12">
                                                    <select class="form-control" name="email_setting_id"
                                                            id="email_setting_id">
                                                        @if(!empty($smtp_setting))
                                                            @foreach($smtp_setting as $skey => $clists)
                                                                <option
                                                                    @if($marketing_campaign_schedule['email_setting_id'] == $clists->id) selected=selected
                                                                    @endif value="{{$clists->id}}">{{$clists->mail_host}}
                                                                    :{{$clists->mail_port}} - {{$clists->from_email}}
                                                                    - {{$clists->from_name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        @elseif($marketing_campaign_schedule['send'] == 2)
                                            <div class="col-md-4">
                                                <label>Country Code</label>
                                                <div class="input-group col-md-12">
                                                    <input type="number" class="form-control" name="sms_country_code"
                                                           id="sms_country_code"
                                                           value="{{$marketing_campaign_schedule['sms_country_code']}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Text Template</label>
                                                <div class="input-daterange input-group col-md-12">
                                                    <select class="form-control" name="sms_template_id"
                                                            id="sms_template_id">
                                                        @if(!empty($sms_templates))
                                                            @foreach($sms_templates as $skey => $clists)
                                                                <option
                                                                    @if($marketing_campaign_schedule['sms_template_id'] == $clists->templete_id) selected=selected
                                                                    @endif value="{{$clists->templete_id}}">{{$clists->templete_name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label>Text DID</label>
                                                <div class="input-daterange input-group col-md-12">
                                                    <select class="form-control" name="sms_setting_id"
                                                            id="sms_setting_id">
                                                        @if(!empty($did))
                                                            @foreach($did as $skey => $clists)
                                                                <option
                                                                    @if($marketing_campaign_schedule['sms_setting_id'] == $clists->id) selected=selected
                                                                    @endif value="{{$clists->id}}">{{$clists->cli}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        @endif


                                        <div class="col-md-4">
                                            <label>Run Time</label>
                                            <input type="hidden" id="run_time" name="run_time" value="{{$marketing_campaign_schedule['run_time']}}">
                                            <div class="input-daterange input-group col-md-12">
                                                <div class="col-md-6">
                                                    <input type="date" class="form-control" required="" value="" id="run_date_local">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="time" class="form-control" required="" value="" id="run_time_local">
                                                </div>
                                                <span style="color:red;" id="error_run_time"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="clear: both"></div>
                                <div class="modal-footer">
                                    <button type="submit" name="submit" value="add"
                                            class="btn btn btn-primary waves-effect waves-light">Update
                                    </button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            let utcTime = "{{$marketing_campaign_schedule['run_time']}}";
            var localTime = moment.utc(utcTime).local();
            console.log(localTime);
            $("#run_date_local").val(localTime.format('YYYY-MM-DD'));
            $("#run_time_local").val(localTime.format('HH:mm:ss'));
        });

        $("#userform").submit(function(e){
            $("#error_run_time").html("");
            var run_date = $("#run_date_local").val();
            var run_time = $("#run_time_local").val();
            var localTime = new Date(run_date + ' ' + run_time + ':00');
            var plus10Mins = moment(new Date()).add(10, 'm').toDate();
            if (localTime < plus10Mins) {
                $("#error_run_time").html("Run time should be at least 10 minutes in future i.e., more than " + moment(plus10Mins).local().format('DD/MM/YYYY hh:mm A'));
                e.preventDefault();
            }

            var utc_run_time = moment(localTime).utc().format('YYYY-MM-DD HH:mm:ss');
            $("#run_time").val(utc_run_time);
        });
    </script>
@endpush


