@php
error_reporting(0);
@endphp
<table width="100%" style="background:#fff;border-left:1px solid #e4e4e4;border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4;font-family:Arial,Helvetica,sans-serif" border="0" cellpadding="0" cellspacing="0" align="center">

    <tbody>
        <tr>
            <td style="border-top:solid 4px #dddddd;line-height:1">
            </td>
        </tr>
    </tbody>
</table>

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
                                    <td style="border: 1px solid #ddd;padding: 8px;"></td>

                                   

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
                                    <td style="border: 1px solid #ddd;padding: 8px;"></td>

                                   

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
        <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;">Agent Wise Summary </h3>
        </div>
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
                                    </td>
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