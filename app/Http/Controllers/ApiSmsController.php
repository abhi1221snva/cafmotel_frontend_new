<?php

namespace App\Http\Controllers;

use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;
use App\Classes\ApiClass;

class ApiSmsController extends Controller
{
    public function getSms()
    {
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }

        // $inherit_list = new InheritApiController;
        // $sms_list = $inherit_list->getSmsList();

        // if (!is_array($sms_list)) {
        //     $sms_list = array();
        // }

        // //     echo "<pre>";print_r($sms_list);die;
        // if (empty($sms_list)) {
        //     $sms_list = array();
        //     /* if(empty(Session::get('tokenId'))){
        //       return redirect('/');
        //       } */
        // }
        $url_sms = env('API_URL') . 'sms';
        $sms_list = Helper::GetApi($url_sms);
        // echo "<pre>";print_r($sms_list);die;

        $url = env('API_URL') . 'sms_did_list';
        $group = Helper::GetApi($url);
  
        
        return view('sms.sms', compact('sms_list', 'group'));

    }

    public function recentSmsList(){
        $url_sms = env('API_URL') . 'sms';
        $sms_list = Helper::GetApi($url_sms);
        return response()->json($sms_list);

    }

    public function editSms($sms_id)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'sms_id' => $sms_id
        );

        //echo "<pre>";print_r($body);die;
        $url = env('API_URL') . 'sms';
        // echo "<pre>";print_r($body);die
        try {
            $ext_group = Helper::PostApi($url, $body);
            /* echo "<pre>";print_r($ext_group);die; */
            if ($ext_group->success == 'true') {
                $group = $ext_group->data;
                return $group;
            }

            if ($ext_group->success == 'false') {
                return redirect('/');
                //return back()->withSuccess($ext_group->message);
            }
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (sms): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (sms): Oops something went wrong :( Please contact your administrator.)");
        }
    }
    
    public function sendSms(Request $request)
    {
        
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'to' => $request->to,
            'from' => $request->from,
            'message' => $request->message,
            'date' => $request->created_date
        );
        $url = env('API_URL') . 'send-sms';
        try {
            $sendSms = Helper::PostApi($url, $body);   
            return response()->json($sendSms);;                   
           
            
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (send-sms): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (send-sms): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public function openSmsDetails(Request $request)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'number' => $request->to,
            'did' => $request->from
        );
        
        $url = env('API_URL') . 'sms-by-did';
        try {
            $sms_details = Helper::PostApi($url, $body);
            if ($sms_details->success == 'true') {
                $sms = $sms_details->data;
                return $sms;
            }

            if ($sms_details->success == 'false') {
                return redirect('/');
                //return back()->withSuccess($ext_group->message);
            }
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (sms-by-did): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (sms-by-did): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public function smsResponse()
    {
        $response = json_decode(file_get_contents('php://input'));
        //echo "<pre>";print_r($response);die;
        $apiClass = new ApiClass();
        $string = $apiClass->receieveSms($response);
    }

    public function voiceMailREceiver()
    {
        $response['extension']   = $_GET['extension'];
        $response['voicemailno'] = $_GET['voicemailno'];
        //echo "<pre>";print_r($response);die;
        $apiClass = new ApiClass();
        $string = $apiClass->voiceMailReceiver($response);
    }

    public function gextensionLiveCallStatus()
    {
        $response['extension']   = $_GET['extension'];
        //echo "<pre>";print_r($response);die;
        $apiClass = new ApiClass();
        $string = $apiClass->gextensionLiveCallStatus($response);
    }

    public function load_message_popup()
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId')
        );
        $url = env('API_URL') . 'get-sms-email-list';
        $popup_details = Helper::PostApi($url, $body);

        $inherit_list = new InheritApiController;
        $group = $inherit_list->getListById();

        if (!is_array($group)) {
            $group = array();
        }

        $sms_array = array(
            'sms_data' => $popup_details,
            'c_number' => $group
        );

        echo json_encode($sms_array);
        //print_r($popup_details);
    }

    public function load_popup_sms_preview(Request $request)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'sms_tpl_id' => $request->sms_tpl_id,
            'lead_id' => $request->lead_id
        );
        $url = env('API_URL') . 'sms-preview';
        $popup_details = Helper::PostApi($url, $body);
        echo json_encode($popup_details);
    }

    public function send_sms_dialer(Request $request)
    {
        $to = str_replace(array('(',')', '_', '-',' '), array(''), $request->to);
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'to' => $to,
            'from' => $request->from,
            'message' => $request->message,
            'date' => $request->created_date
            
        );
        $url = env('API_URL') . 'send-sms';
        try {
            // Get new jobs or execute a job
            $sendSms = Helper::PostApi($url, $body);
            return json_encode($sendSms);
        } catch (Exception $e) {
            // $e->
            return false;
        }
    }
}
