<?php

namespace App\Http\Controllers;

use Session;
use Pusher\Pusher;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;


class ApiDashboardController extends Controller
{
    function index()
    {
        $availablePackages = [];

		//error_reporting(0);
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }

        $errors = new MessageBag();
        $map = [];
        $url = env('API_URL') . "extension-group-map";
        try
        {
            $response = Helper::GetApi($url);
            if ($response->success)
            {
                $map = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("dashboard.dashboard", compact("errors", $errors));
        }

        $group = [];
        $url = env('API_URL') . "extension-group";
        try
        {
            $response = Helper::GetApi($url);
            if ($response->success)
            {
                $group = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("dashboard.dashboard", compact("errors", $errors));
        }


        $inherit_list = new InheritApiController;
        $didCount = $inherit_list->getDidListCount();
        $userCount = $inherit_list->getUserCount();
        $campaignCount = $inherit_list->getCampaignsCount();
        $leadCount = $inherit_list->getLeadCount();
        $extensionList = $inherit_list->fetchEmployeeDirectory();
        $extension_list = $inherit_list->getExtensionList();

        $liveCalls = $inherit_list->liveCalls();
        //$getSmsList = $inherit_list->getSmsList();

		$getSmsList = $inherit_list->getSmsListNew();
        if (empty($getSmsList)) {
            $getSmsList = array();
        } else {
            // Convert the object to an array
            $getSmsList = json_decode(json_encode($getSmsList), true);
        
            // Sort the array by date in descending order
            usort($getSmsList, function ($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        
            // Get the first 10 records
            $getSmsList = array_slice($getSmsList, 0, 10);
        }
//return $getSmsList;
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
            return view("dashboard.dashboard", compact("errors", $errors));
        }

        /*$campaign_list = $inherit_list->getCampaign();
        if (!is_array($campaign_list))
        {
            $campaign_list = array();
        }*/
        

        $getVoicemailUnreadCount = $inherit_list->getVoicemailUnreadCount();


        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId')
        );
        $urlSms = env('API_URL') . 'unread-sms-count';
        $smsCountResponse = Helper::PostApi($urlSms, $body);
        if(!isset($smsCountResponse->data->countRow)){
            $smsUnreadCount = 0 ;
        }else{
            $smsUnreadCount = $smsCountResponse->data->countRow > 0 ?  $smsCountResponse->data->countRow : '' ;
        }

        $urlClientPackages = env('API_URL') . "client-packages";
        $response = Helper::GetApi($urlClientPackages);
        if ($response->success) {
            $availablePackages = (array) $response->data;
        }

        // $options = array(
        //     'cluster' => env('PUSHER_APP_CLUSTER'),
        //     'encrypted' => true
        // );


        // $pusher = new Pusher(
        //     env('PUSHER_APP_KEY'),
        //     env('PUSHER_APP_SECRET'),
        //     env('PUSHER_APP_ID'), $options
        // );

        // $message= $smsUnreadCount;
        // $message= 5;

        // //Send a message to notify channel with an event name of notify-event
        // $pusher->trigger('notification', 'notification-event', $message);

        // $file_path = sys_get_temp_dir().'/a.pdf';
        // $image = file_get_contents("https://www.tutorialspoint.com/php/php_tutorial.pdf");
        // file_put_contents($file_path, $image);
        // exit;

        return view('dashboard.dashboard')->with([
            'didCount' => $didCount,
            'userCount' => $userCount,
            'campaignCount' => $campaignCount,
            'leadCount' => $leadCount,
            'extensionList' => $extensionList,
            'liveCalls' => $liveCalls,
            'getSmsList' => $getSmsList,
            'getVoicemailUnreadCount' => $getVoicemailUnreadCount,
            'availablePackages' => $availablePackages,
            'extension_list'=>$extension_list,
            'campaign_list'=>$campaign_list,
            "map"=>$map,
            "group"=>$group
        ]);
    }

    function getCallDetail(Request $request)
    {
        //echo "<pre>";print_r($request->all());die;
        $fetchCallDetail = $this->fetchCallDetail($request->start_date, $request->end_date,$request->extension_user_check);
        return response()->json($fetchCallDetail);
    }

    function fetchCallDetail($start_date, $end_date, $extension_user_check)
    {
        if (!empty($start_date) && !empty($end_date)) {

            $date1_ts = strtotime($start_date);
            $date2_ts = strtotime($end_date);
            $diff = $date2_ts - $date1_ts;
            $date_diff = round($diff / 86400);

            $inherit_list = new InheritApiController;

            $extListDashboard = $inherit_list->getExtensionListDashboard($start_date, $end_date, $extension_user_check);
            $data['extListDashboard'] = $extListDashboard;

        //echo "<pre>";print_r($extension_user_check);die;


            $data['getDispositionWiseCalls'] = $inherit_list->getDispositionWiseCalls($start_date, $end_date, $extension_user_check);

            $data['outBoundDialer'] = $inherit_list->getCdrCallCount('OUT', 'dialer', $start_date, $end_date, $extension_user_check);
            $data['outBoundPredictive'] = $inherit_list->getCdrCallCount('OUT', 'predictive_dial', $start_date, $end_date, $extension_user_check);

            $data['outBoundManual'] = $inherit_list->getCdrCallCount('OUT', 'manual', $start_date, $end_date, $extension_user_check);
            $data['inBoundManual'] = $inherit_list->getCdrCallCount('IN', 'manual', $start_date, $end_date, $extension_user_check);
            $data['inBoundDailer'] = $inherit_list->getCdrCallCount('IN', 'dialer', $start_date, $end_date, $extension_user_check);
            $data['loggedInAgent'] = $inherit_list->getLoggedInAgent($start_date, $end_date, $extension_user_check);

       // echo "<pre>";print_r( $data['inBoundDailer']);die;



            $data['voicemails'] = $inherit_list->getVoiceMailCount($start_date, $end_date, $extension_user_check);

            $data['getStatewiseCalls'] = $inherit_list->getStatewiseCalls($start_date, $end_date, $extension_user_check);
            return $data;
        }
    }

    public function getSmsCounts(Request $request)
    {
        $url = env('API_URL') . 'sms-count';
        $body = [
            'startTime' => $request->startTime,
            'endTime' => $request->endTime,
            'userId'=>$request->userId
        ];
        $response = Helper::PostApi($url, $body);
        if ($response->success) {
            return response()->json($response->data);
        } else {
            return response()->json($response->errors, 500);
        }
    }

    public function getSmsCountsunread(Request $request)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId')
        );
        $urlSms = env('API_URL') . 'unread-sms-count';
        $response = Helper::PostApi($urlSms, $body);

        if ($response->success) {
            return response()->json($response->data);
        } else {
            return response()->json($response->errors, 500);
        }
    }

    public function getCdrChartData(Request $request)
    {
        $url = env('API_URL') . 'cdr-count-range';
        $body = [
            'range' => $request->range
        ];
        $response = Helper::PostApi($url, $body);
        if ($response->success) {
            return response()->json($response->data);
        } else {
            return response()->json($response->errors, 500);
        }
    }

    function setSessionValue()
    {
       Session::put('timezone_session', 1);
    }
}


