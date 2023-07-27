<table width="100%" style="max-width:800px;background:#fff;border-left:1px solid #e4e4e4;border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4;font-family:Arial,Helvetica,sans-serif" border="0" cellpadding="0" cellspacing="0" align="center">
    <tbody>
    <tr>
        <td style="border-top:solid 4px #dddddd;line-height:1">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom:1px solid #e4e4e4;border-top:none">
                <tbody>
                <tr>
                    <td align="left" valign="top">
                        <div style="padding: 7px 12px 8px 8px;">
                            <img src="{{$result_arr->logo}}" valign="middle" style="height: 54px;">
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:21px 10px 2px 10px;background:#dddddd">
            <p style="margin:0px;font-size:16px;padding-bottom:12px;line-height:21px">
                <strong>Total Inbound/OutBound Calls & SMS Report Details</strong>
            </p>
            <div style="clear:both;padding:11px 7px 12px;margin-bottom:8px;border:1px solid #f5f5f5;border-radius:3px;background:#fff">
                <table style="font-family:Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;">
                    <tr>
                        <td style="border: 1px solid #ddd;padding: 8px;">Total Number Of Outbound Calls</td>
                        <td style="border: 1px solid #ddd;padding: 8px;">{{$result_arr->total_outbound_Calls}}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd;padding: 8px;">Total Number Of Outbound Calls Made Manually</td>
                        <td style="border: 1px solid #ddd;padding: 8px;">{{$result_arr->total_outbound_Calls_manually}}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd;padding: 8px;">Total Number Of Outbound Calls</td>
                        <td style="border: 1px solid #ddd;padding: 8px;">{{$result_arr->total_outbound_Calls_dialer}}</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding:21px 10px 2px 10px;background:#dddddd">
            <p style="margin:0px;font-size:16px;padding-bottom:12px;line-height:21px">
                <strong>Campaign Wise Outbound Summary</strong>
            </p>
            <div style="clear:both;padding:11px 7px 12px;margin-bottom:8px;border:1px solid #f5f5f5;border-radius:3px;background:#fff">
                @if(!empty($result_arr->campaign))
                    <table style="font-family:Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;">
                        @foreach($result_arr->campaign as $camp)
                            <tr>
                                <td style="border: 1px solid #ddd;padding: 8px;">{{$camp->title}}</td>
                                <td style="border: 1px solid #ddd;padding: 8px;">{{$camp->calls}}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding:21px 10px 2px 10px;background:#dddddd">
            <p style="margin:0px;font-size:16px;padding-bottom:12px;line-height:21px">
                <strong>Inbound Calls </strong>
            </p>
            <div style="clear:both;padding:11px 7px 12px;margin-bottom:8px;border:1px solid #f5f5f5;border-radius:3px;background:#fff">
                <table style="font-family:Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;">
                    <tr>
                        <td style="border: 1px solid #ddd;padding: 8px;">Total Number Of Inbound Calls</td>
                        <td style="border: 1px solid #ddd;padding: 8px;">{{$result_arr->total_inbound_Calls}}</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding:21px 10px 2px 10px;background:#dddddd"><p style="margin:0px;font-size:16px;padding-bottom:12px;line-height:21px"><strong>SMS Details </strong></p>
            <div style="clear:both;padding:11px 7px 12px;margin-bottom:8px;border:1px solid #f5f5f5;border-radius:3px;background:#fff">
                <table style="font-family:Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;">
                    <tr>
                        <td style="border: 1px solid #ddd;padding: 8px;">Total Number Of SMS Received</td>
                        <td style="border: 1px solid #ddd;padding: 8px;">{{$result_arr->total_inbound_Calls}}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd;padding: 8px;">Total Number Of SMS Sent</td>
                        <td style="border: 1px solid #ddd;padding: 8px;">{{$result_arr->total_sms_send}}</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding:21px 10px 2px 10px;background:#dddddd"><p style="margin:0px;font-size:16px;padding-bottom:12px;line-height:21px"><strong>FAX Details </strong></p>
            <div style="clear:both;padding:11px 7px 12px;margin-bottom:8px;border:1px solid #f5f5f5;border-radius:3px;background:#fff">
                <table style="font-family:Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;">
                    <tr>
                        <td style="border: 1px solid #ddd;padding: 8px;">Total Number Of FAX Received</td>
                        <td style="border: 1px solid #ddd;padding: 8px;">0</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd;padding: 8px;">Total Number Of FAX Sent</td>
                        <td style="border: 1px solid #ddd;padding: 8px;">0</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding:21px 10px 2px 10px;background:#dddddd"><p style="margin:0px;font-size:16px;padding-bottom:12px;line-height:21px"><strong>Agent Wise Summary</strong></p>
            <div style="clear:both;padding:11px 7px 12px;margin-bottom:8px;border:1px solid #f5f5f5;border-radius:3px;background:#fff">
                <div style="margin:0 0 7px;font-size:14px">
                    <table style="font-family:Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;">
                        <tr>
                            <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Agent Name</th>
                            <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Oubound Calls</th>
                            <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Inbound Calls</th>
                        </tr>
                        @foreach($result_arr->agent as $agentcall)
                            <tr>
                                <td style="border: 1px solid #ddd;padding: 8px;">{{$agentcall->agentName}}</td>
                                <td style="border: 1px solid #ddd;padding: 8px;">{{$agentcall->outbound}}</td>
                                <td style="border: 1px solid #ddd;padding: 8px;">{{$agentcall->inbound}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td style="border-bottom:4px solid #dddddd;border-top:1px solid #dedede;padding:0 16px">
            <p style="color:#999999;margin:0;font-size:11px;padding:6px 0;font-family:Arial,Helvetica,sans-serif">
                © Copyright <?php echo date('Y'); ?>  {{$result_arr->company_name}}.
            </p>
        </td>
    </tr>
    </tbody>
</table>




