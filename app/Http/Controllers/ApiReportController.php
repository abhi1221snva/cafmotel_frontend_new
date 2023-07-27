<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use App\Http\Controllers\InheritApiController;
use PDF;
use Session;
use Illuminate\Support\MessageBag;
use DateTimeZone;
use DateTime;

class ApiReportController extends Controller      {

    function getReportByLeadId(Request $request) {
        $urlpage = $request->page;
        if (!empty($urlpage)) {
            $lower_limit = $urlpage * 10;
            $url = env('API_URL') . 'report-lead-id';
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'lead_id' => $request->lead_id,
                'lower_limit' => $lower_limit,
                'upper_limit' => 10,
            );

            //echo "<pre>";print_r($body);die;

            try {
                $cdr_report = Helper::PostApi($url, $body);
                if ($cdr_report->success == 'true') {
                    $record_count = $cdr_report->record_count;
                    $report = $cdr_report->data;
                    $lead_id = $request->lead_id;

                    //echo "<pre>";print_r($record_count);die;
                    //return back()->withSuccess($result->message);
                    return view('cdr_report.report_by_lead_id', compact('report', 'record_count', 'lower_limit', 'lead_id'));
                }

                if ($cdr_report->success == 'false') {
                    return back()->withSuccess($cdr_report->message);
                }
            } catch (BadResponseException $e) {
                return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
            }
        } else {


            $lower_limit = 0;
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'lead_id' => $request->lead_id,
                'lower_limit' => $lower_limit,
                'upper_limit' => 10,
            );

            // echo "<pre>";print_r($body);die;//

            $url = env('API_URL') . 'report-lead-id';
            /*  $cdr_report = Helper::PostApi($url,$body);
              echo "<pre>";print_r($cdr_report);die;
             */
            try {

                $cdr_report = Helper::PostApi($url, $body);

                //echo "<pre>";print_r($cdr_report);die;


                if ($cdr_report->success == 'true') {

                    $record_count = $cdr_report->record_count;

                    $report = $cdr_report->data;

                    $lead_id = $request->lead_id;

                    //return back()->withSuccess($result->message);
                    return view('cdr_report.report_by_lead_id', compact('report', 'record_count', 'lower_limit', 'lead_id'));
                }

                if ($cdr_report->success == 'false') {
//                    return redirect('/');

                    return back()->withSuccess($cdr_report->message);
                }
            } catch (BadResponseException $e) {
                return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                $message = "Page Not Found";

                return redirect('/');

                // return back()->withSuccess($message);
            }
        }
    }

    function getReportByNumber(Request $request) {

        $urlpage = $request->page;
        if (!empty($urlpage)) {
            $lower_limit = $urlpage * 10;
            $url = env('API_URL') . 'report';
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'number' => $request->number,
                'lower_limit' => $lower_limit,
                'upper_limit' => 10,
            );

            //echo "<pre>";print_r($body);die;

            try {
                $cdr_report = Helper::PostApi($url, $body);
                if ($cdr_report->success == 'true') {
                    $record_count = $cdr_report->record_count;
                    $report = $cdr_report->data;
                    //echo "<pre>";print_r($record_count);die;
                    //return back()->withSuccess($result->message);
                    return view('cdr_report.report_by_number', compact('report', 'record_count', 'lower_limit'));
                }

                if ($cdr_report->success == 'false') {
                    return back()->withSuccess($cdr_report->message);
                    //return back()->withSuccess($result->message);
                }
            } catch (BadResponseException $e) {
                return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
            }
        } else {
            $lower_limit = 0;
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'number' => $request->number,
                'lower_limit' => $lower_limit,
                'upper_limit' => 10,
            );

            // echo "<pre>";print_r($body);die;

            $url = env('API_URL') . 'report';
            /*  $cdr_report = Helper::PostApi($url,$body);
              echo "<pre>";print_r($cdr_report);die;
             */
            try {

                $cdr_report = Helper::PostApi($url, $body);

                //echo "<pre>";print_r($cdr_report);die;


                if ($cdr_report->success == 'true') {

                    $record_count = $cdr_report->record_count;

                    $report = $cdr_report->data;

                    //return back()->withSuccess($result->message);
                    return view('cdr_report.report_by_number', compact('report', 'record_count', 'lower_limit'));
                }

                if ($cdr_report->success == 'false') {
                    return back()->withSuccess($cdr_report->message);
                }
            } catch (BadResponseException $e) {
                return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                $message = "Page Not Found";

                return redirect('/');

                // return back()->withSuccess($message);
            }
        }
    }

    function getReport(Request $request) {
        ini_set('max_execution_time', 3000);
        $inherit_list = new InheritApiController;

        $disposition_list = $inherit_list->getDisposition();
        if (!is_array($disposition_list)) {
            $disposition_list = array();
        }

        $campaign_list = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "campaigns";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $campaign_list = $response->data;
            } else {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("cdr_report.report", compact("errors", $errors));
        }

        /*$campaign_list = $inherit_list->getCampaign();
        if (!is_array($campaign_list))
        {
            $campaign_list = array();
        }*/

        $did_list = $inherit_list->getDidList();
        if (!is_array($did_list)) {
            $did_list = array(); // checking empty record
        }

        //areacode list
        $url = env('API_URL') . "area-code-list";
        $area_code = Helper::GetApi($url);
        $area_codes = $area_code->data;

        //timezone list
        $url = env('API_URL') . "get-timezone-list";
        $timezone_list = Helper::GetApi($url);
        $timezone_lists = $timezone_list->data;
        
        $extension_list = $this->getExtensionList();
        //var_dump($extension_list);

        if ($request->isMethod('get')) {
            $number = str_replace(array('(',')', '_', '-',' '), array(''), $request->number); 

            $urlpage = $request->page;
            if (!empty($urlpage)) {
                $page=0;
        $upper_limit=10;
        $urlpage = $request->page;
        if (!empty($urlpage) && $urlpage > 1)
        {
            $urlpage = $urlpage - 1;
            $lower_limit = $urlpage * 10;
        }
        else
        {
            $lower_limit = 0;
        }

        if ($request->isMethod('post')) {
           $lower_limit = 0;
           $page=1;
        }

                $url = env('API_URL') . 'report';
                $body = array(
                    'level' => Session::get('level'),
                    'number' => $number,
                    'type' => $request->type,
                    'extension' => $request->extension,
                    'did_numbers'=>$request->did_numbers,
                    'area_code'=>$request->area_code,
                    'timezone_value'=>$request->timezone_value,
                    'campaign' => $request->campaign,
                    'disposition' => $request->disposition,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'lower_limit' => $lower_limit,
                    'upper_limit' => 10,
                );
                try {
                    //echo "s";die;
                    $cdr_report = Helper::PostApi($url, $body);
                   // echo "<pre>";print_r($cdr_report);die;
                    if ($cdr_report->success == 'true') {
                        $record_count = $cdr_report->record_count;
                        $report = $cdr_report->data;
                        return view('cdr_report.report', compact('disposition_list', 'extension_list', 'report', 'campaign_list', 'record_count', 'lower_limit','did_list','area_codes','timezone_lists','page'));
                    }
                    if ($cdr_report->success == 'false') {
                        return redirect('/');
                    }
                } catch (BadResponseException $e) {
                    return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
                } catch (RequestException $ex) {
                    return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
                }
            } else {
                if (empty(Session::get('tokenId'))) {
                    return redirect('/');
                }
                return view('cdr_report.report', compact('campaign_list', 'extension_list', 'disposition_list','did_list','area_codes','timezone_lists'));
            }
        } elseif ($request->isMethod('post')) {
            if ($request->submit_download == '1') {
                $inherit_list = new InheritApiController;
                $headerUserDetails = $inherit_list->headerUserDetails();
                $lower_limit = 0;
            $number = str_replace(array('(',')', '_', '-',' '), array(''), $request->number); 

                $url = env('API_URL') . 'report';
                $body = array(
                    'id' => Session::get('id'),
                    'token' => Session::get('tokenId'),
                    'level' => Session::get('level'),
                    'did_numbers'=>$request->did_numbers,
                    'area_code'=>$request->area_code,
                    'timezone_value'=>$request->timezone_value,
                    'number' => $number,
                    'type' => $request->type,
                    'extension' => $request->extension,
                    'campaign' => $request->campaign,
                    'route' => $request->route,
                    'disposition' => $request->disposition,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'lower_limit' => $lower_limit,
                );
                try {
                    $number = $number;
                    $type = $request->type;
                    $extension = $request->extension;
                    $campaign = $request->campaign;
                    $route = $request->route;
                    $start_date = $request->start_date;
                    $end_date = $request->end_date;
                    $disposition = $request->disposition;
                    $cdr_report = Helper::PostApi($url, $body);


                    if ($cdr_report->success == 'true') {
                        $record_count = $cdr_report->record_count;
                        $report = $cdr_report->data;
                        $logo = $headerUserDetails->data->logo;
                        $company_name = $headerUserDetails->data->company_name;
                        $mobile = $headerUserDetails->data->mobile;
                        $email = $headerUserDetails->data->email;
                        $pdf = PDF::loadView('cdr_report.report_pdf', compact('report', 'record_count', 'campaign_list', 'disposition_list', 'lower_limit', 'extension_list', 'number', 'type', 'extension', 'campaign', 'route', 'start_date', 'end_date', 'disposition', 'logo', 'company_name', 'mobile', 'email'))->setPaper('a4', 'landscape');
                        return $pdf->download('cdr_report.pdf');
                    }
                    if ($cdr_report->success == 'false') {
                        return redirect('/');
                    }
                } catch (BadResponseException $e) {
                    return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
                } catch (RequestException $ex) {
                    $message = "Page Not Found";
                    return redirect('/');
                }
            } else if ($request->submit_download == '2') {

                $inherit_list = new InheritApiController;
                $headerUserDetails = $inherit_list->headerUserDetails();
                $timezone = $headerUserDetails->data->timezone;
                $lower_limit = 0;
            $number = str_replace(array('(',')', '_', '-',' '), array(''), $request->number); 

                $url = env('API_URL') . 'report';
                $body = array(
                    'id' => Session::get('id'),
                    'token' => Session::get('tokenId'),
                    'level' => Session::get('level'),
                    'did_numbers'=>$request->did_numbers,
                    'area_code'=>$request->area_code,
                    'timezone_value'=>$request->timezone_value,
                    'number' => $number,
                    'type' => $request->type,
                    'extension' => $request->extension,
                    'campaign' => $request->campaign,
                    'route' => $request->route,
                    'disposition' => $request->disposition,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'lower_limit' => $lower_limit,
                );

                try {
                    $user_array = array('route', "type");
                    $users = Helper::PostApi($url, $body);
                    foreach ($users->data as $user) {
                        $user_array[] = array(
                            'route' => $user->route,
                            'type' => $user->type,
                        );
                    }

                    $filename_excel = 'CDR_REPORT_'.date('Y-m-d').".csv";

                    header("Content-type: text/csv");
                    header("Content-Disposition: attachment; filename=".$filename_excel);
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    $reviews = $user_array;
                    $columns = array('Extension', 'Campaign', 'CLI', 'Route', 'Type', 'Number', 'Disposition', 'Duration' , 'State / City' ,'Start Time', 'End Time', 'Recording');
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);
                    if (!empty($users->data)) {

                        $excel_data = array();
                        foreach ($users->data as $key => $val) {

                         $state_city= '-';
                           if(!empty($val->area_code))
                           {
                            foreach($area_codes as $key => $area)
                        {
                            if($area->areacode == $val->area_code)
                            {
                                 $state_city = $area->state_name.' / '.$area->city_name;
                            } 
                        }
                           }
                           else
                           {
                            $state_city= '-';
                           }

                        

                        //extension

                        foreach($extension_list as $key => $extension)
                        {
                            if($extension->extension == $val->extension)
                            {
                                $extension_name = $extension->first_name.' '.$extension->last_name.'-'.$val->extension;
                            }
                            else
                            if($extension->alt_extension == $val->extension)
                            {
                                $extension_name = $extension->first_name.' '.$extension->last_name.'-'.$val->extension;
                            }
                            else
                            if($val->extension == NULL)
                            {
                                $extension_name = '-';
                            }
                            else
                            {
                                $num = $val->extension;
                                $numlength = strlen((string)$num);
                                if($numlength > 9)
                                {
                                    $extension_name = $val->extension;
                                }
                            }
                        }

                        $excel_data[$key]['extension_name'] = $extension_name;

                        //campaign

                        if (!empty($val->campaign_id))
                        {
                            foreach ($campaign_list as $key => $campaign)
                            {
                                if ($campaign->id == $val->campaign_id)
                                {
                                    if (!empty($campaign->title))
                                    {
                                        $campaign_name = $campaign->title;
                                    }                                
                                }
                            }
                        }
                        else
                        {
                            $campaign_name = '-';
                        }

                        //cli

                        if(!empty($val->cli))
                        {
                            $cli = $val->cli;
                        }
                        else
                        {
                            $cli ='-';
                        }

                        //route

                        if(!empty($val->route))
                        {
                            $route = $val->route;
                        }
                        else
                        {
                            $route ='-';
                        }

                        //type

                        if(!empty($val->type))
                        {
                            if($val->type == 'manual')
                                $type = 'Manual';
                            else
                            if($val->type == 'dialer')
                                $type = 'Dialer';
                            else
                            if($val->type == 'predictive_dial')
                                $type = 'Predictive';
                            else
                                $type='-';
                        }
                        else
                        {
                            $type ='-';
                        }

                        //number

                        if(!empty($val->number))
                        {
                            $number = $val->number;
                        }
                        else
                        {
                            $number ='-';
                        }

                        //disposition

                        $disposition_name ='-';//$val->disposition_id;

                        if (!empty($val->disposition_id)) 
                        {
                            foreach ($disposition_list as $key => $dispo) 
                            {
                                if ($dispo->id == $val->disposition_id) 
                                {
                                    $disposition_name = $dispo->title;
                                }
                                else
                                if($val->disposition_id == '101')
                                {
                                    $disposition_name = "No Agent Available";
                                    break;
                                }
                                else
                                if($val->disposition_id == '102')
                                {
                                    $disposition_name = "AMD Hangup";
                                    break;
                                }
                                else
                                if($val->disposition_id == '103')
                                {
                                    $disposition_name = "Voice Drop";
                                    break;
                                }
                                else
                                if($val->disposition_id == '104')
                                {
                                    $disposition_name = "Cancelled By User";
                                    break;
                                }
                                else
                                if($val->disposition_id == '105')
                                {
                                    $disposition_name = "Channel Unavailable";
                                    break;
                                }
                                else
                                if($val->disposition_id == '106')
                                {
                                    $disposition_name = "Congestion";
                                    break;
                                }
                                else
                                if($val->disposition_id == '107')
                                {
                                    $disposition_name = "Line Busy";
                                    break;
                                }

                                else
                                if($val->disposition_id == '108')
                                {
                                    $disposition_name = "CRM CALL";
                                    break;
                                }
                            }
                        }
                        else
                        {
                            $disposition_name = '-';
                        }


                       

                        //recording
                        if(!empty($val->call_recording))
                        {
                            $recording = $val->call_recording;
                        }
                        else
                        {
                            $recording = '-';
                        }


                        //time

                        if(!empty($timezone))
                        {
                            if(!empty($val->start_time))
                            {
                                $utc_start_time = $val->start_time;
                                $dt_start_time = new DateTime($utc_start_time);
                                $tz = new DateTimeZone($timezone); // or whatever zone you're after
                                $dt_start_time->setTimezone($tz);
                                $start_time = $dt_start_time->format('Y-m-d H:i:s');

                            }
                            else
                            {
                                $start_time='-';
                            }

                            if(!empty($val->end_time))
                            {
                                $utc_end_time = $val->end_time;
                                $dt_end_time = new DateTime($utc_end_time);
                                $tz = new DateTimeZone($timezone); // or whatever zone you're after
                                $dt_end_time->setTimezone($tz);    
                                $end_time = $dt_end_time->format('Y-m-d H:i:s');
                            }

                            else
                            {
                                $end_time='-';
                            }
                        }

                        else
                        {
                            if(!empty($val->start_time))
                            {
                                $start_time = $val->start_time;
                            }
                            else
                            {
                                $start_time='-';
                            }

                            if(!empty($val->end_time))
                            {
                                $end_time = $val->end_time;
                            }
                            else
                            {
                                $end_time='-';
                            }
                        }

                        fputcsv($file, array($extension_name,$campaign_name,$cli,$route, $type, $number, $disposition_name, $val->duration, $state_city, $start_time, $end_time,$recording));


                      
                        }
                    }

                    

                    exit();

                    if ($cdr_report->success == 'true') {
                        $record_count = $cdr_report->record_count;
                        $report = $cdr_report->data;
                        return view('cdr_report.report', compact('report', 'record_count', 'campaign_list', 'disposition_list', 'lower_limit', 'extension_list'));
                    }
                    if ($cdr_report->success == 'false') {
                        return redirect('/');
                    }
                } catch (BadResponseException $e) {
                    return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
                } catch (RequestException $ex) {
                    $message = "Page Not Found";
                    return redirect('/');
                }
            } else {

                $page=0;
        $upper_limit=10;
        $urlpage = $request->page;
        if (!empty($urlpage) && $urlpage > 1)
        {
            $urlpage = $urlpage - 1;
            $lower_limit = $urlpage * 10;
        }
        else
        {
            $lower_limit = 0;
        }

        if ($request->isMethod('post')) {
           $lower_limit = 0;
           $page=1;
        }

            $number = str_replace(array('(',')', '_', '-',' '), array(''), $request->number); 
                
                $url = env('API_URL') . 'report';
                $body = array(
                    'id' => Session::get('id'),
                    'level' => Session::get('level'),
                    'token' => Session::get('tokenId'),
                    'number' => $number,
                    'did_numbers'=>$request->did_numbers,
                    'area_code'=>$request->area_code,
                    'timezone_value'=>$request->timezone_value,
                    'type' => $request->type,
                    'extension' => $request->extension,
                    'campaign' => $request->campaign,
                    'route' => $request->route,
                    'disposition' => $request->disposition,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'lower_limit' => $lower_limit,
                    'upper_limit' => 10,
                );
                try {

                    $cdr_report = Helper::PostApi($url, $body);
                    //echo'<pre>';print_r($cdr_report); exit;
                    if ($cdr_report->success == 'true') {
                        $record_count = $cdr_report->record_count;
                        $report = $cdr_report->data;
                        session()->flash("success", $cdr_report->message);
                        return view('cdr_report.report', compact('report', 'record_count', 'campaign_list', 'disposition_list', 'lower_limit', 'extension_list','did_list','area_codes','timezone_lists','page'));
                    }
                    if ($cdr_report->success == 'false') {
                        return redirect('/');
                    }
                } catch (BadResponseException $e) {
                    return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
                } catch (RequestException $ex) {
                    $message = "Page Not Found";
                    return redirect('/');
                }
            }
        }
    }

    function getLiveCall(Request $request)
    {
        if ($request->isMethod('get'))
        {
            $inherit_list = new InheritApiController;
            $extension_list = $inherit_list->getExtensionList();

            $campaign_list = $inherit_list->getCampaign();
            if (!is_array($campaign_list))
            {
                $campaign_list = array();
            }

            $url = env('API_URL') . 'live-call';
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
            );

            // echo $url ; print_R($body); exit;
            try
            {
                $live_call_report = Helper::PostApi($url, $body);
                if ($live_call_report->success == 'true')
                {
                    $report = $live_call_report->data;
                    return view('cdr_report.live-list', compact('report','extension_list','campaign_list'));
                }
                if ($live_call_report->success == 'false')
                {
                    return redirect('/');
                    //return back()->withSuccess($result->message);
                }
            }

            catch (BadResponseException $e)
            {
                return back()->with('message', "Error code - (live-call): Oops something went wrong :( Please contact your administrator.)");
            }
            catch (RequestException $ex)
            {
                return back()->with('message', "Error code - (live-call): Oops something went wrong :( Please contact your administrator.)");
            }
        }
    }

    function getTransferReport(Request $request) {

        $url = env('API_URL') . 'campaign';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        $campaign = Helper::PostApi($url, $body);

        if ($campaign->success == 'true') {
            $campaign_list = $campaign->data;
        } else {
            session()->flash("message", "Failed to fetch campaigns. " . $campaign->message);
            return view('cdr_report.transfer-report')->with(["campaign_list" => []]);
        }

        if ($request->isMethod('get')) {
            $urlpage = $request->page;
            if (!empty($urlpage)) {
                $lower_limit = $urlpage * 10;
                $url = env('API_URL') . 'transfer-report';
                $body = array(
                    'id' => Session::get('id'),
                    'token' => Session::get('tokenId'),
                    'number' => $request->number,
                    'type' => $request->type,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'lower_limit' => $lower_limit,
                    'upper_limit' => 10,
                );
                $cdr_report = Helper::PostApi($url, $body);

                if ($cdr_report->success == 'true') {
                    $record_count = $cdr_report->record_count;
                    $report = $cdr_report->data;
                    return view('cdr_report.transfer-report', compact('report', 'campaign_list', 'record_count', 'lower_limit'));
                } else {
                    session()->flash("message", "Failed to fetch campaigns. " . $cdr_report->message);
                    return view('cdr_report.transfer-report')->with(["campaign_list" => []]);
                }
            } else {
                session()->flash("message", "No page specified.");
                return view('cdr_report.transfer-report')->with(["campaign_list" => []]);
            }
        }

        if ($request->isMethod('post')) {
            $lower_limit = 0;
            $url = env('API_URL') . 'transfer-report';
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'number' => $request->number,
                'type' => $request->type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'lower_limit' => 1,
                'upper_limit' => 10,
            );
            $cdr_report = Helper::PostApi($url, $body);
            if ($cdr_report->success == 'true') {
                $record_count = $cdr_report->record_count;
                $report = $cdr_report->data;
                return view('cdr_report.transfer-report', compact('report', 'record_count', 'campaign_list', 'lower_limit'));
            } else {
                session()->flash("message", "Failed to fetch campaigns. " . $cdr_report->message);
                return view('cdr_report.transfer-report')->with(["campaign_list" => []]);
            }
        }
    }

    function getExtensionList() {
        $url = env('API_URL') . 'extension-group-list';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'level' => Session::get('level')
        );
        try {
            $extension = Helper::PostApi($url, $body);
            return $extension;
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (getExtensionList): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            $message = "Page Not Found";
            return redirect('/');
        }
    }
	
	function listenCall(Request $request){
		$url = env('API_URL') . 'listen-call';
        $body = array(
            'id' => Session::get('id'),
			'token' => Session::get('tokenId'),
			'listen_id' => $request->listen_id,
			'call_type' => $request->call_type,
            'extension' => $request->extension
        );
        try {
            $hangUp = Helper::PostApi($url, $body);
            echo json_encode(array('status' => $hangUp->success, 'message' => $hangUp->message));
            exit;
        } catch (BadResponseException   $e) {

            return back()->with('message', "Error code - (listen-call): Oops something went wrong :( Please contact your administrator.)");


        }
	}
	
	function bargeCall(Request $request){
		$url = env('API_URL') . 'barge-call';
        $body = array(
            'id' => Session::get('id'),
			'token' => Session::get('tokenId'),
			'listen_id' => $request->listen_id,
			'call_type' => $request->call_type,
            'extension' => $request->extension
            
        );
        try {
            $barge = Helper::PostApi($url, $body);
            echo json_encode(array('status' => $barge->success, 'message' => $barge->message));
            exit;
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (barge-call): Oops something went wrong :( Please contact your administrator.)");


        }
	}


    function loginHistory1()
    {
        /*$login_history = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "login-history";
        try
        {
            $response = Helper::GetApi($url);

            echo "<pre>";print_r($response);die;
            if($response->success)
            {
                $tariff_plans = $response->data;
            }
            else
            {
                $custom_field_labels = [];
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("tariff_plans.tariff_labels", compact("errors", $errors));
        }*/
        return view('cdr_report.login-history');
    }


     function loginHistory(Request $request) {
        ini_set('max_execution_time', 3000);

      

      

        if ($request->isMethod('get')) {
            $number = str_replace(array('(',')', '_', '-',' '), array(''), $request->number); 

            $urlpage = $request->page;
            if (!empty($urlpage)) {
                $page=0;
        $upper_limit=10;
        $urlpage = $request->page;
        if (!empty($urlpage) && $urlpage > 1)
        {
            $urlpage = $urlpage - 1;
            $lower_limit = $urlpage * 10;
        }
        else
        {
            $lower_limit = 0;
        }

        if ($request->isMethod('post')) {
           $lower_limit = 0;
           $page=1;
        }

         if ($request->submit_download == 'excel') {

             $upper_limit='';
         }
                $url = env('API_URL') . 'login-history';
                $body = array(
                    
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'lower_limit' => $lower_limit,
                    'upper_limit' => 10,
                );
                try {
                    //echo "s";die;
                    $cdr_report = Helper::PostApi($url, $body);
                    //echo "<pre>";print_r($cdr_report);die;
                    if ($cdr_report->success == 'true') {
                        $record_count = $cdr_report->record_count;
                        $report = $cdr_report->data;
                        return view('cdr_report.login-history', compact('report', 'record_count', 'lower_limit','page'));
                    }
                    if ($cdr_report->success == 'false') {
                        return redirect('/');
                    }
                } catch (BadResponseException $e) {
                    return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
                } catch (RequestException $ex) {
                    return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
                }
            } else {
                if (empty(Session::get('tokenId'))) {
                    return redirect('/');
                }
                return view('cdr_report.login-history');
            }
        } elseif ($request->isMethod('post')) {
           

                $page=0;
        $upper_limit=10;
        $urlpage = $request->page;
        if (!empty($urlpage) && $urlpage > 1)
        {
            $urlpage = $urlpage - 1;
            $lower_limit = $urlpage * 10;
        }
        else
        {
            $lower_limit = 0;
        }

        if ($request->isMethod('post')) {
           $lower_limit = 0;
           $page=1;
        }

         if ($request->submit_download == 'excel') {

             $upper_limit='';
         }
                
                $url = env('API_URL') . 'login-history';
                $body = array(
                    'id' => Session::get('id'),
                    'level' => Session::get('level'),
                    'token' => Session::get('tokenId'),
                    
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'lower_limit' => $lower_limit,
                    'upper_limit' => 10,
                );
                try {

                    $cdr_report = Helper::PostApi($url, $body);
                    //echo'<pre>';print_r($cdr_report); exit;
                    if ($cdr_report->success == 'true') {
                        $record_count = $cdr_report->record_count;
                        $report = $cdr_report->data;
                        session()->flash("success", $cdr_report->message);
                        return view('cdr_report.login-history', compact('report', 'record_count','lower_limit','page'));
                    }
                    if ($cdr_report->success == 'false') {
                        return redirect('/');
                    }
                } catch (BadResponseException $e) {
                    return back()->with('message', "Error code - (report): Oops something went wrong :( Please contact your administrator.)");
                } catch (RequestException $ex) {
                    $message = "Page Not Found";
                    return redirect('/');
                
            }
        }
    }

}
