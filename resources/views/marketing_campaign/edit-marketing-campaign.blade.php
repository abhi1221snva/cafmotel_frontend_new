

@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
                <h1>
                   <b>Edit Marketing Campaign</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Marketing Campaigns</li>
                    
                    <li class="active">Edit Marketing Campaign</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                <a href="{{ url('/campaign') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Marketing Campaign</a>
           </div>
        </section>
    

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body">
                         <form method="post" action="">
                             @csrf
                <div class="modal-body">
                    <div class="row col-md-12">
                        <div class="form-group col-md-6">

                             <input type="hidden" class="form-control" name="marketing_id" value="{{$marketing_campaign_list[0]->id}}" id="marketing_campaign_name" required="">
                            <label>Name <i data-toggle="tooltip" data-placement="right" title="Marketing campaign name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <input type="text" class="form-control" name="title" value="{{$marketing_campaign_list[0]->title}}" id="marketing_campaign_name" required="">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Description <i data-toggle="tooltip" data-placement="right" title="Marketing campaign description" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <textarea type="textarea" class="form-control" name="description" value="" id="marketing_campaign_description">{{$marketing_campaign_list[0]->description}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Mail Gateway Type <i data-toggle="tooltip" data-placement="right" title="Mail gateway type" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">

                                SMTP  <input type="radio" id="mail_gateway_type1" class="radio_sms" name="mail_gateway_type" @if($marketing_campaign_list[0]->mail_gateway_type == 'SMTP') checked @endif value="SMTP">&nbsp;&nbsp;
                                SendGrid <input type="radio" id="mail_gateway_type2" @if($marketing_campaign_list[0]->mail_gateway_type == 'SendGrid') checked @endif class="radio_sms" name="mail_gateway_type"  value="SendGrid">


                            </div>
                        </div>


                        <div class="form-group col-md-6" style="display: none;"  id="sendgrid_data">
                            <label>Mail Gateway <i data-toggle="tooltip" data-placement="right" title="Mail gateway" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">

                                <select name="mail_gateway" class="form-control" id="status">


                                    @if(!empty($sendGrid_list))
                                     @foreach($sendGrid_list as $key => $grid)
                                    <option @if($marketing_campaign_list[0]->mail_gateway == $grid->id) selected @endif value="{{$grid->id}}">{{$grid->mail_driver}}</option>


                                     @endforeach
                                     @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6" id="smtp_data">
                            <label>Mail Gateway <i data-toggle="tooltip" data-placement="right" title="Mail gateway" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="mail_gateway" class="form-control" id="status">

                                    @if(!empty($smtp_list))
                                     @foreach($smtp_list as $key => $smtp)
                                   <option @if($marketing_campaign_list[0]->mail_gateway == $smtp->id) selected @endif value="{{$smtp->id}}">{{$smtp->mail_host}}</option>

                                     @endforeach
                                     @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Mail Templete <i data-toggle="tooltip" data-placement="right" title="Mail template" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">

                                 <select class="select2" required="" name="mail_templete" autocomplete="off" data-placeholder="Select Template" style="width: 100%;">
                                <option value="{{$marketing_campaign_list[0]->mail_template}}">Email Templete 1</option>

                                <option value="1">Email Templete 1</option>
                                <option value="2">Email Templete 2</option>




                            </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Text Templete <i data-toggle="tooltip" data-placement="right" title="Text template" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">

                                 <select class="select2" required="" name="sms_templete" autocomplete="off" data-placeholder="Select Template" style="width: 100%;">
                                <option value="">Select Text Templete</option>

                                @foreach($templete_list as $key => $templete)

                                 <option @if($marketing_campaign_list[0]->sms_template == $templete->templete_id) selected @endif value="{{$templete->templete_id}}">{{$templete->templete_name}}</option>
                                @endforeach;
                            </select>
                            </div>
                        </div>
                    </div>


                     <div class="row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Text Gateway Type <i data-toggle="tooltip" data-placement="right" title="Text gateway type" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">

                                DidForSale <input type="radio" id="sms_gateway_type1" @if($marketing_campaign_list[0]->sms_gateway_type == 'DidForSale') checked @endif  class="radio_sms" name="sms_gateway_type" checked value="DidForSale">&nbsp;&nbsp;
                                Twilio <input type="radio" id="sms_gateway_type2" @if($marketing_campaign_list[0]->sms_gateway_type == 'Twilio') checked @endif class="radio_sms" name="sms_gateway_type"  value="Twilio">


                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Text Gateway <i data-toggle="tooltip" data-placement="right" title="Text gayeway" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="sms_gateway" class="form-control" id="sms_gateway">

                                    <option @if($marketing_campaign_list[0]->sms_gateway == 1) selected @endif value="1">Didforsale</option>
                                    <option @if($marketing_campaign_list[0]->sms_gateway == 2) selected @endif value="2">Twilio</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Campaign Run Time <i data-toggle="tooltip" data-placement="right" title="Campaign Run Time" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div>
                                <div class="input-group">
                                    <input type="time" class="form-control" value="{{$marketing_campaign_list[0]->campaign_run_times}}" name="campaign_run_times" id="campaign_run_times">

                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                        <label>Send Report <i data-toggle="tooltip" data-placement="right" title="Send report" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                            <select name="send_report" class="form-control" id="campaign_send_report">
                                <option @if($marketing_campaign_list[0]->send_report == 1) selected @endif value="1">Yes</option>
                                <option @if($marketing_campaign_list[0]->send_report == 0) selected @endif value="0">No</option>
                            </select>
                        </div>
                    </div>


                     <div class="col-md-6">
                            <label>Group <i data-toggle="tooltip" data-placement="right" title="Group" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                            <select class="select2" required="" name="group_id" autocomplete="off" data-placeholder="Select Group" style="width: 100%;">
                                <option value="">Select Group</option>

                                @foreach($group as $group_ext)
                                <option @if($marketing_campaign_list[0]->group_id == $group_ext->id) selected @endif value="{{$group_ext->id}}">{{$group_ext->title}}</option>


                                @endforeach;
                            </select>
                            </div>
                        </div>




                </div>


                <div class="row lead_status">
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="form-control" name="username" value="" id ="first-name" required>
                    <button type="submit" name="submit" value="Save" class="btn btn btn-primary waves-effect waves-light">Save</button>

                </div>
            </form>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->


            </div><!-- /.col -->
        </div><!-- /.row -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>



$(document).ready(function(){


        $('.radio_sms').on('change',function(){
            alert();

        });

});

$(document).ready(function(){
  $('.radio_sms').iCheck({
      radioClass   : 'iradio_minimal-blue'
    });



});
</script>

<script src="{{asset('asset/plugins/iCheck/icheck.min.js') }}"></script>
<link rel="stylesheet" href="{{asset('asset/plugins/iCheck/all.css') }}">
@endsection
