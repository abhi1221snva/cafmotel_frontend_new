<?php

namespace App\Http\Controllers;

use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Events\IncomingLead;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;

/**
* Pusher controller
* Catch params and broadcast event to client
*/
class PusherController extends Controller {
    /**
    * 
    * @param type $api_key
    * @param type $from  
    * @param type $to (in sms and fax its number else in call and voicemail its extension)
    * @param type $platform
    * @param type $event
    * @return type
    */
    function index($api_key, $platform, $from, $to, $event = '') {
        if(base64_decode($api_key) != "Y2FmbW90ZWxwdXNoZXJhcGlrZXlpbmJhc2U2NGZvcm1hdA==") {
            return response()->json(['status' => false,'message' => 'Api Key Mismatch','message_code' => 'Api Key Mismatch','status_code' => 401]);
        }
        
        try
        {            
            return $this->sendPusherNotifcation($from, $to, $platform, $event);
        } 
        catch (\Throwable $e)
        {
            \Log::error("Failed to send pusher notification", Helper::buildContext($e));
            echo "Failed to send pusher notification";
            die;
        }
    }
    
    /**
    * 
    * @param type $client_id
    * @param type $extension
    * @param type $number
    * @param type $platform
    * @param type $event
    * @return type
    */
    public function sendPusherNotifcation($from, $to, $platform, $event = '') {
        $arrBroadCast = [];
        $url = env('API_URL') . 'check-and-get-user-id-for-pusher';
        $body = array(
            'platform' => $platform,
            'to' => $to,
            'event' => $event,
        );
        $userIds = Helper::PostApi($url, $body);
        if ($userIds->success && count($userIds->data) > 0)
        {
            if($platform == 'call')
            {
                switch($event)
                {
                    case "ringing":
                        $msg = "Incoming Call From $from";
                    break;
                    case "received":
                        $msg = "Ongoing Call From $from";
                    break;
                    case "completed":
                        $msg = "Call Completed From $from";
                    break;
                }
            } else {
                $msg = "You Have Received a $platform From $from";
            } 

            $arrBroadCast = ['user_ids' => $userIds->data, 'msg' => $msg,
                'platform' => $platform, 'number' => $from, 'event' => $event];
            broadcast(new IncomingLead($arrBroadCast))->toOthers();

            return response()->json($arrBroadCast);
        }
        else
        {
            return response()->json($arrBroadCast);
        }
    }
}


