@extends('layouts.app')
<style>
#loader {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 9999;
}
<div class="dialog-background" id="loading" style="display: none;">
    <div class="dialog-loading-wrapper">
        <img src="{{ asset('asset/img/lp.gif') }}">
    </div>
</div>

<style type="text/css">
.dialog-background
{
  background: none repeat scroll 0 0 rgba(244, 244, 244, 0.5);
  height: 100%;
  left: 0;
  margin: 0;
  padding: 0;
  position: absolute;
  top: 0;
  width: 100%;
  z-index: 100;
}

.dialog-loading-wrapper
{
  background: none repeat scroll 0 0 rgba(244, 244, 244, 0.5);
  border: 0 none;
  height: 100px;
  left: 50%;
  margin-left: -50px;
  margin-top: -50px;
  position: fixed;
  top: 50%;
  width: 100px;
  z-index: 9999999;
}

</style>
@section('content')



<?php

error_reporting(0);

if (!empty(request()->input('start_date')))
    {
        $startDate = request()->input('start_date');
    }
    else
    {
        $current_date = date("Y-m-d"); 
        //$str_date = strtotime(date("Y-m-d"));//, strtotime($current_date)) . " - day");
        $startDate = date('Y-m-d');//, $str_date);
    }

    if (!empty(request()->input('end_date')))
    {
        $endDate = request()->input('end_date');
    }
    else
    {
        $endDate = date('Y-m-d');
    }
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <b>Count Report</b>
        </h1>

        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Count</a></li>
            <li class="active">Report</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <form method="post"id='mySearch'>
                        @csrf

                        @if(Session::get('level') > 7)

                        <div class="col-md-4" style="float: right;">
                                <div class="form-group">
                                    
                                    <label>Select Client : <i data-toggle="tooltip" data-placement="right" title="Select client from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>

                                    <div class="input-group">
                                        <select class="form-control" name="client_id">
                                            @if(!empty($clients))
                                                @foreach($clients as $list_client)
                                                @if($list_client->is_deleted == '0')
                                                <option @if($list_client->id == request()->input('client_id')) selected @endif value="{{$list_client->id}}">{{$list_client->company_name}}</option>
                                                @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                        <!-- /.input group -->
                                </div>
                                    <!-- /.form group -->
                            </div>
                            @else
                            <input type="hidden"  name="client_id" value="{{Session::get('parentId')}}" />
                            @endif
                            <div class="col-md-4" style="float: right;">
                                <div class="form-group">
                                    
                                    <label>Date range: <i data-toggle="tooltip" data-placement="right" title="Select date range for call report" class="fa fa-info-circle" aria-hidden="true"></i></label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="cdr-range">
                                        <input type="hidden" id="start_date" name="start_date" value="{{ $startDate }}">
                                        <input type="hidden" id="end_date" name="end_date" value="{{ $endDate }}">
                                    </div>
                                        <!-- /.input group -->
                                </div>
                                    <!-- /.form group -->
                            </div>

                            <div style="clear: both"></div>
                            
                            <div class="modal-footer">

                                <button type="submit" name="submit_download" class="btn btn-danger waves-effect waves-light m-l-10" value="1"><i class="fa fa-file-pdf-o" aria-hidden="true"> </i>Export PDF</button>
                                <button type="submit" name="submit" value="Search" class="btn btn btn-primary waves-effect waves-light"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                                <div id="loader" style="display: none;">
                                    <img src="{{ asset('asset/giphy.gif') }}" alt="Loading..."style="height:100px;width:100px;">
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
<table width="100%" style="background:#fff;border-left:1px solid #e4e4e4;border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4;font-family:Arial,Helvetica,sans-serif" border="0" cellpadding="0" cellspacing="0" align="center">
    <tbody>
        <tr>
            <td style="width:50%">
                <p style="padding:8px;border:1px solid #ddd;padding-top:12px;padding-bottom:12px;text-align:left;background-color:#444444;color:white">
                    <strong>Total Inbound/OutBound Calls</strong>
                </p>

                <div style="clear:both;padding:11px 7px 12px;margin-bottom:8px;border:1px solid #f5f5f5;border-radius:3px;background:#fff">
                    <table style="font-family:Arial,Helvetica,sans-serif;border-collapse:collapse;width:100%">
                        <tbody>
                            <tr>
                                <td style="border:1px solid #ddd;padding:8px">Total Number Of Outbound Calls Made Manually</td>
                                <td style="border:1px solid #ddd;padding:8px">{{$email_templates->total_outbound_Calls_manually}}</td>
                            </tr>

                            <tr>
                                <td style="border:1px solid #ddd;padding:8px">Total Number Of Outbound Calls via Dialer</td>
                                <td style="border:1px solid #ddd;padding:8px">{{$email_templates->total_outbound_Calls_dialer}}</td>
                            </tr>

                            <tr>
                                <td style="border:1px solid #ddd;padding:8px">Total Number Of Outbound Calls via C2C</td>
                                <td style="border:1px solid #ddd;padding:8px">{{$email_templates->total_outbound_Calls_c2c}}</td>
                            </tr>

                            <tr>
                                <td style="border:1px solid #ddd;padding:8px">Total Number Of Outbound Calls</td>
                                <td style="border:1px solid #ddd;padding:8px">{{$email_templates->total_outbound_Calls}}</td>
                            </tr>


                            <tr>
                                <td style="border:1px solid #ddd;padding:8px">Inbound Calls</td>
                                <td style="border:1px solid #ddd;padding:8px">{{$email_templates->total_inbound_Calls}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>

            <td style="width:50%">

                <p style="padding:8px;border:1px solid #ddd;padding-top:12px;padding-bottom:12px;text-align:left;background-color:#444444;color:white">
                    <strong>Total SMS/FAX Report Details</strong>
                </p>
                <div style="clear:both;padding:11px 7px 12px;margin-bottom:44px;border:1px solid #f5f5f5;border-radius:3px;background:#fff">
                    <table style="font-family:Arial,Helvetica,sans-serif;border-collapse:collapse;width:100%">
                        <tbody>
                            <tr>
                                <td style="border:1px solid #ddd;padding:8px">Total Number Of SMS Received</td>
                                <td style="border:1px solid #ddd;padding:8px">{{$email_templates->total_sms_receive}}</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #ddd;padding:8px">Total Number Of SMS Sent</td>
                                <td style="border:1px solid #ddd;padding:8px">{{$email_templates->total_sms_send}}</td>
                            </tr>

                            <tr>
                                <td style="border:1px solid #ddd;padding:8px">FAX Received</td>
                                <td style="border:1px solid #ddd;padding:8px">0</td>
                            </tr>

                            <tr>
                                <td style="border:1px solid #ddd;padding:8px">FAX Sent</td>
                                <td style="border:1px solid #ddd;padding:8px">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<!-- first row -->

        <!-- <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border" style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">
                        <h3 class="box-title" style="font-weight: 600;">Total Inbound/OutBound Calls </h3>
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered"id="first-table">

                            <tr>
                                <td style="border: 1px solid #ddd;padding: 8px;">Outbound Calls Made Manually</td>
                                <td style="border: 1px solid #ddd;padding: 8px;"><span class="badge bg-blue">{{$email_templates->total_outbound_Calls_manually}}</span></td>
                             
                                <td style="border: 1px solid #ddd;padding: 8px;">Inbound Calls</td>
                                <td style="border: 1px solid #ddd;padding: 8px;"><span class="badge bg-blue">{{$email_templates->total_inbound_Calls}}</span></td>
                            </tr>

                            <tr>
                                <td style="border: 1px solid #ddd;padding: 8px;">Outbound Calls via Dialer</td>
                                <td style="border: 1px solid #ddd;padding: 8px;"><span class="badge bg-blue">{{$email_templates->total_outbound_Calls_dialer}}</span></td>
                            </tr>

                            <tr>
                                <td style="border: 1px solid #ddd;padding: 8px;">Total Outbound Calls</td>
                                <td style="border: 1px solid #ddd;padding: 8px;"><span class="badge bg-blue">{{$email_templates->total_outbound_Calls}}</span></td>
                            </tr>

                           
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border" style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">
                        <h3 class="box-title" style="font-weight: 600;">Total SMS/FAX Report Details</h3>
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <td style="border: 1px solid #ddd;padding: 8px;">SMS Received</td>
                                <td style="border: 1px solid #ddd;padding: 8px;"><span class="badge bg-blue">{{$email_templates->total_sms_receive}}</span></td>
                            </tr>

                            <tr>
                                <td style="border: 1px solid #ddd;padding: 8px;">SMS Sent</td>
                                <td style="border: 1px solid #ddd;padding: 8px;"><span class="badge bg-blue">{{$email_templates->total_sms_send}}</span></td>
                            </tr>

                            <tr>
                                <td style="border: 1px solid #ddd;padding: 8px;">FAX Received</td>
                                <td style="border: 1px solid #ddd;padding: 8px;"><span class="badge bg-blue">0</span></td>
                            </tr>

                            <tr>
                                <td style="border: 1px solid #ddd;padding: 8px;">FAX Sent</td>
                                <td style="border: 1px solid #ddd;padding: 8px;"><span class="badge bg-blue">0</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div> -->

        @if(!empty($email_templates->campaign))

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                

                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;">Campaign Wise Outbound Summary </h3>
                    </div>

                    <div class="box-body">
      

                        <!-- second row -->
                        <table style="font-family:Arial,Helvetica,sans-serif;border-collapse:collapse;width:100%">

                        @php
                            $total_campaign = 0;
                            @endphp

                          

                            @foreach($email_templates->campaign as $camp)
                            @php
                            $total_campaign += $camp->calls;

                            $crm = $camp->dispositions;
                                if (in_array("108", $crm))
                                {
                                $campaign_name = 'CRM CALL';
                                }
                                else
                                {
                                $campaign_name = $camp->title;
                                }
                            @endphp



                                <tr style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">
                                    <td style="border: 1px solid #ddd;padding: 8px;font-weight: 800;font-size: 15px;">Campaign Name : {{$campaign_name}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;font-weight: 800;font-size: 15px;"><span class="badge bg-blue">{{$camp->calls}}</span></td>
                                </tr>

                                
                             <tr>
                                    <td style="border: 1px solid #ddd;padding: 8px;"><b>Agent Wise Summary</b></td>
                                   

                                </tr>


                                <tr>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;">Agent Name</th>
                                
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;">Total Calls</th>
                               
                            </tr>


                            @foreach($camp->ext as $camp_user)

                               

                                

                                <tr>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$camp_user->name}} - {{$camp_user->extension}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;"><span class="badge bg-blue">{{$camp_user->extension_total}}</span></td>

                                </tr>

                            @endforeach

                            <tr>
                                    <td style="border: 1px solid #ddd;padding: 8px;"><b>Disposition Wise Summary</b></td>
                                   

                                </tr>


                                @foreach($camp->disposition as $camp_dispo)

                                @php

                                if (!empty($camp_dispo->title))
                                {
                                    $title = $camp_dispo->title;
                                }
                                                        
                               else
                                if($camp_dispo->disposition_id == '101')
                                {
                                    $title = "No Agent Available";
                                }
                            
                                else
                                 if($camp_dispo->disposition_id == '102')
                                {
                                    $title =  "AMD Hangup";
                                }
                            
                                else
                                 if($camp_dispo->disposition_id == '103')
                                {
                                    $title = "Voice Drop";
                                    
                                }

                                else
                                if($camp_dispo->disposition_id== '104')
                                {
                                    $title = "Cancelled By User";
                                }

                                else
                                if($camp_dispo->disposition_id == '105')
                                {
                                    $title = "Channel Unavailable";
                                }
                                else
                                if($camp_dispo->disposition_id == '106')
                                {
                                    $title = "Congestion";
                                }
                                else
                                if($camp_dispo->disposition_id == '107')
                                {
                                    $title = "Line Busy";
                                }

                                else
                                if($camp_dispo->disposition_id == '108')
                                {
                                    $title = "CRM CALL";
                                }
                                else 
                                {
                                    $title =  'No Disposition';
                                }

                                @endphp

                                <tr>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$title}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;"><span class="badge bg-blue">{{$camp_dispo->disposition}}</span></td>

                                </tr>

                                @endforeach
                            @endforeach

                            
                        </table> 
                    </div>
                </div>
            </div>

          
        </div>

        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;">Agent Wise Summary </h3>
                    </div>

                    <div class="box-body">
                    <table style="font-family:Arial,Helvetica,sans-serif;border-collapse:collapse;width:100%">
                            <tr>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Agent Name</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Extension</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Total Calls</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Outbound Calls</th>

                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">C2C Calls</th>

                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Inbound Calls</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Total Call Time</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Average Handle time</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">SMS Sent</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">SMS Received</th>
                            </tr>
                            @php
                            $total_totalcalls = 0;
                            $total_outbound = 0;
                            $total_c2c = 0;

                            $total_inbound = 0;
                            $total_outgoing = 0;
                            $total_incoming = 0;

                            $total_agent_duration = 0;
                            $total_agent_aht = 0;



                            @endphp
                            @foreach($email_templates->agent as $agentcall)
                            
                            @php
                                $total_totalcalls +=$agentcall->totalcalls;
                                $total_outbound +=$agentcall->outbound;
                                $total_c2c +=$agentcall->c2c;

                                $total_inbound +=$agentcall->inbound;
                                $total_outgoing +=$agentcall->outgoing;
                                $total_incoming +=$agentcall->incoming;

                                $seconds = round($agentcall->duration);
                                $output_duration_agent = sprintf('%02d:%02d:%02d', ($seconds/ 3600),($seconds/ 60 % 60), $seconds% 60);

                                $seconds_aht = round($agentcall->aht);
                                $output_duration_aht = sprintf('%02d:%02d:%02d', ($seconds_aht/ 3600),($seconds_aht/ 60 % 60), $seconds_aht% 60);

                                $total_agent_duration +=$agentcall->duration;
                                $total_agent_aht +=$agentcall->aht;


                                $seconds = round($total_agent_duration);
                                $total_duration = sprintf('%02d:%02d:%02d', ($seconds/ 3600),($seconds/ 60 % 60), $seconds% 60);

                                $seconds = round($total_agent_aht);
                                $total_aht = sprintf('%02d:%02d:%02d', ($seconds/ 3600),($seconds/ 60 % 60), $seconds% 60);



                            @endphp
                                <tr>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$agentcall->agentName}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$agentcall->extension}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$agentcall->totalcalls}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$agentcall->outbound}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$agentcall->c2c}}</td>

                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$agentcall->inbound}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$output_duration_agent}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$output_duration_aht}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$agentcall->outgoing}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$agentcall->incoming}}</td>

                                </tr>

                            @endforeach
                                <tr>
                                    <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Total</th>
                                    <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;"></th>
                                    <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{$total_totalcalls}}</th>
                                    <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{$total_outbound}}</th>
                                    <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{$total_c2c}}</th>

                                    <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{$total_inbound}}</th>
                                    <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{$total_duration}}</th>
                                    <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{$total_aht}}</th>
                                    <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{$total_outgoing}}</th>
                                    <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{$total_incoming}}</th>

                                </tr>
                        </table>
                    </div>
                </div>
            </div>

           
        </div>


        @if(!empty($email_templates->did))

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;">Inbound DID Wise Call Report </h3>
                    </div>

                    <div class="box-body">
                        <table style="font-family:Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;">
                            <tr>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">DID Number</th>
                               <!--  <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">CNAM</th> -->
                                <!-- <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Total Calls</th> -->
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Inbound Calls</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Total Call Time</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Average Handle time</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">SMS Sent</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">SMS Received</th>
                            </tr>

                            @php
                            $total_did = 0;
                            $total_duration = 0;
                            $total_aht = 0;
                            $total_outgoing = 0;
                            $total_incoming = 0;




                            @endphp



                            @foreach($email_templates->did as $list)
                            @php
                            $total_did += $list->totalcalls;


                            
                            $total_outgoing += $list->outgoing;
                            $total_incoming += $list->incoming;

                            $duration = round($list->duration);
                            $output_duration = sprintf('%02d:%02d:%02d', ($duration/ 3600),($duration/ 60 % 60), $duration% 60);
                            $aht = round($list->aht);
                            $output_aht = sprintf('%02d:%02d:%02d', ($aht/ 3600),($aht/ 60 % 60), $aht% 60);

                            $total_aht += $list->aht;
                            $total_aht = round($total_aht);
                            $total_output_aht = sprintf('%02d:%02d:%02d', ($total_aht/ 3600),($total_aht/ 60 % 60), $total_aht% 60);

                            $total_duration += $list->duration;
                            $total_duration = round($total_duration);
                            $total_output_duration = sprintf('%02d:%02d:%02d', ($total_duration/ 3600),($total_duration/ 60 % 60), $total_duration% 60);




                            @endphp
                                <tr>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$list->cli}}</td>
                                    <!-- <td style="border: 1px solid #ddd;padding: 8px;">{{$list->cnam}}</td> -->

                                    <!-- <td style="border: 1px solid #ddd;padding: 8px;">{{$list->totalcalls}}</td> -->
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$list->inbound}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{($output_duration)}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{($output_aht)}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$list->outgoing}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$list->incoming}}</td>

                                </tr>
                            @endforeach

                            <tr>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Total</th>
                                <td style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{$total_did}}</td>
                               
                                <td style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{($total_output_duration)}}</td>
                                <td style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{($total_output_aht)}}</td>
                                <td style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{$total_outgoing}}</td>
                                <td style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{$total_incoming}}</td>

                            </tr>
                        </table>
                    </div>
                </div>
            </div>

           
        </div>
        @endif




        @if(!empty($email_templates->city_wise))

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;">State / City / Areacode Wise Summary </h3>
                    </div>

                    <div class="box-body">
                    <table style="font-family:Arial,Helvetica,sans-serif;border-collapse:collapse;width:100%">
                            <tr>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">State</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">City</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">DID</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">CNAM</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Areacode
                                </th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Total Calls</th>
                                
                            </tr>
                            @php
                            $total_city_wise = 0;
                            @endphp
                            @foreach($email_templates->city_wise as $areacodecall)
                            @php
                            $total_city_wise += $areacodecall->total;
                            @endphp
                                <tr>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$areacodecall->state}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$areacodecall->city}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$areacodecall->did}} 
                                        <a class="badge bg-blue cnam" style="text-align:right;"  data-did={{$areacodecall->did}} >Run CNAM Manually</a></td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$areacodecall->cnam}}</td>
                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$areacodecall->area_code}}</td>

                                    <td style="border: 1px solid #ddd;padding: 8px;">{{$areacodecall->total}}</td>
                                    
                                </tr>
                            @endforeach

                            <tr>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Total</th>
                                <td style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;"></td>
                                <td style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;"></td>
                                <td style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;"></td>
                                <td style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;"></td>
                                <td style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">{{$total_city_wise}}</td>

                            </tr>
                        </table>
                        <br>
                        <br>
                        
                    </div>
                </div>
            </div>

           
        </div>
        @endif
    </section>
</div>


        <div class="modal fade" id="cnam_model" role="dialog">

            <!-- Modal content-->

            <div class="modal-dialog">
                <div class="modal-content" style="background-color: teal !important;color:white;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Run CNAM Manually</h4>
                    </div>
                    <div class="modal-body">
                        <p>Please enter your phone number</p>
                        <input type="hidden" class="form-control" name="did_value" value="" id="did_value">
                        <p id="mobile_message"></p>
                        <input type="" onkeypress="return isNumberKey($(this));" maxlength="10" class="form-control" name="mobile" value="" placeholder="phone number" id="mobile">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-info btn-ok search_cnam">Run</button>

                    </div>
                </div>
            </div>

        </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{asset('asset/plugins/daterangepicker/daterangepicker-bs3.css') }}">
    <style>
        .margin-10{
            margin-right: 5%;
        }
        .marginTop-2{
            margin-top: 2%;
        }

        .width_fix{
            width: 195px;
        }
    </style>
@endpush

@push('scripts')




    <script src="{{asset('asset/plugins/dashboard_date/moment.min.js') }}"></script>
    <script src="{{asset('asset/plugins/dashboard_date/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('asset/plugins/dashboard_date/daterangepicker.js') }}"></script>



    <script type="text/javascript">

        $(".cnam").click(function() {
            var did = $(this).data('did');
            $("#cnam_model").modal();
            $("#did_value").val(did);
        });

        $(".search_cnam").click(function() {

                    


            phone_number = $("#mobile").val();
            if(phone_number == '')
            {
                $("#mobile_message").html("Enter number");
                return false;
            }

            $('#cnam_model').removeClass("in");
                    $('#cnam_model').addClass("out");

            $('#loading').show();
                    
            did_value = $("#did_value").val();

            $.ajax({
                type: "get",
                url: "/cli-report-manually-cnam/"+phone_number+"/"+did_value,
                //data: postData,
                dataType: "json",
                success: function (data) {
                    $("#mobile_message").html('');
                    $('#loading').hide();
                    toastr.success("Manully CNAM Call created Successfully");
                }
            });
        });

        $(function () {
            $('#cdr-range').daterangepicker({
                locale: { format: 'YYYY-MM-DD'},
                "startDate": "{{ $startDate }}",
                "endDate": "{{ $endDate }}"
            }, function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                $("#start_date").val(start.format('YYYY-MM-DD'));
                $("#end_date").val(end.format('YYYY-MM-DD'));
            });
        });
    </script>
    <script>
  $(document).ready(function() {
    $('#mySearch').on('submit', function() {
      // Show the loader
      $('#loader').show();
    });

    $('button[name="submit_download"]').on('click', function() {
      var buttonValue = $(this).val();

      if (buttonValue === '1') {
        // Show the loader when the button value is 1
        $('#loader').show();

        // Set a timeout to hide the loader after a delay (e.g., 3 seconds)
        setTimeout(function() {
          $('#loader').hide();
        }, 3000);
      }
    });
  });
</script>
    
     <!-- <script>
  const form = document.querySelector('#mySearch');
  const loader = document.querySelector('#loader');

  form.addEventListener('submit', () => {
    loader.style.display = 'block';
  });

  form.addEventListener('submit', () => {
    loader.style.display = 'none';
  });


  $(function() {
    $('form').submit(function() {
      $('#loader').show();
    }).ajaxComplete(function() {
      $('#loader').hide();
    });
  });
</script>  -->
<script src="{{asset('asset/plugins/iCheck/icheck.min.js') }}"></script>
<link rel="stylesheet" href="{{asset('asset/plugins/iCheck/all.css') }}">
@endpush
