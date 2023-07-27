

@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

     <section class="content-header">
                <h1>
                   <b>Add Marketing Campaign</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Marketing Campaigns</li>
                    
                    <li class="active">Add Marketing Campaign</li>
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
                            <label>Name</label>
                            <div class="input-daterange input-group col-md-12">
                                <input type="text" class="form-control" name="title" value="" id="marketing_campaign_name" required="">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Description</label>
                            <div class="input-daterange input-group col-md-12">
                                <textarea type="textarea" class="form-control" name="description" value="" id="marketing_campaign_description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Mail Gateway Type</label>
                            <div class="input-daterange input-group col-md-12">

                                SMTP  <input type="radio" id="mail_gateway_type1" class="radio_sms" name="mail_gateway_type" checked value="SMTP">&nbsp;&nbsp;
                                SendGrid <input type="radio" id="mail_gateway_type2" class="radio_sms" name="mail_gateway_type"  value="SendGrid">


                            </div>
                        </div>


                        <div class="form-group col-md-6" style="display: none;"  id="sendgrid_data">
                            <label>Mail Gateway</label>
                            <div class="input-daterange input-group col-md-12">

                                <select name="mail_gateway" class="form-control" id="status">

                                    <option value="">Select SendGrid</option>
                                    @if(!empty($sendGrid_list))
                                     @foreach($sendGrid_list as $key => $grid)
                                    <option value="{{$grid->id}}">{{$grid->mail_driver}}</option>


                                     @endforeach
                                     @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6" id="smtp_data">
                            <label>Mail Gateway</label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="mail_gateway" class="form-control" id="status">
                                    <option value="">Select SMTP</option>
                                    @if(!empty($smtp_list))
                                     @foreach($smtp_list as $key => $smtp)
                                    <option value="{{$smtp->id}}">{{$smtp->mail_host}}</option>


                                     @endforeach
                                     @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Mail Templete</label>
                            <div class="input-daterange input-group col-md-12">
                                <textarea type="textarea" class="form-control" name="mail_templete" value="" id="mail_templete"></textarea>

                                 <select class="select2" required="" name="mail_templete" autocomplete="off" data-placeholder="Select SMS" style="width: 100%;">
                                <option value="">Select Email Templete</option>
                                <option value="1">Email Templete 1</option>
                                <option value="2">Email Templete 2</option>




                            </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Text Templete</label>
                            <div class="input-daterange input-group col-md-12">


                                <select class="select2" required="" name="sms_templete" autocomplete="off" data-placeholder="Select Template" style="width: 100%;">
                                <option value="">Select Text Templete</option>

                                @foreach($templete_list as $key => $templete)
                                    <option value="{{$templete->templete_id}}">{{$templete->templete_name}}</option>
                                @endforeach;
                            </select>
                                                           </div>
                        </div>
                    </div>


                     <div class="row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Text Gateway Type</label>
                            <div class="input-daterange input-group col-md-12">

                                DidForSale <input type="radio" id="sms_gateway_type1"  class="radio_sms" name="sms_gateway_type" checked value="DidForSale">&nbsp;&nbsp;
                                Twilio <input type="radio" id="sms_gateway_type2" class="radio_sms" name="sms_gateway_type"  value="Twilio">


                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Text Gateway</label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="sms_gateway" class="form-control" id="sms_gateway">
                                    <option value="">Select Text Gateway</option>
                                    <option value="1">Didforsale</option>
                                    <option value="2">Twilio</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Campaign Run Time</label>
                            <div>
                                <div class="input-group">
                                    <input type="time" class="form-control" value="09:30" name="campaign_run_times" id="campaign_run_times">

                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                        <label>Send Report</label>
                        <div class="input-daterange input-group col-md-12">
                            <select name="send_report" class="form-control" id="campaign_send_report">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>


                     <div class="col-md-6">
                            <label>Group</label>
                            <div class="input-daterange input-group col-md-12">
                            <select class="select2" required="" name="group_id" autocomplete="off" data-placeholder="Select Group" style="width: 100%;">
                                <option value="">Select Group</option>

                                @foreach($group as $group_ext)
                                    <option value="{{$group_ext->id}}">{{$group_ext->title}}</option>
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
