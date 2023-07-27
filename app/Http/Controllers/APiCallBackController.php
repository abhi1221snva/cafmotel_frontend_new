<?php

namespace App\Http\Controllers;
use Illuminate\Support\MessageBag;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use stdClass;


class APiCallBackController extends Controller
{
    function getCallBack(Request $request){

        $inherit_list = new InheritApiController;
        $url = env('API_URL').'extension';
        $body=array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'parent_id' => Session::get('parent_id'),
            'role' => 2
        );

        $campaign_list =  $inherit_list->getCampaign();
        if(!is_array($campaign_list)){
            $campaign_list =array();
        }
        $arrCampaignListRekeyed = ClientPackageController::rekeyArray($campaign_list,'id');


        $extension_list =  $inherit_list->getExtensionList();
        if(!is_array($extension_list)){
            $extension_list =array();
        }
        $arrExtensionListRekeyed = ClientPackageController::rekeyArray($extension_list,'extension');
        $arrExtensionListRekeyedByAltExtension = ClientPackageController::rekeyArray($extension_list,'alt_extension');

        $callbackStatus = $this->getReminderStatus();
        if($callbackStatus == null){
            $callbackStatus = new stdClass();
            $callbackStatus->callback_reminder = null;
        }

        if ($request->isMethod('GET'))
        {
            $lower_limit = 0;
            $url = env('API_URL').'callback';
             $body=array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'campaign_id' => $request->campaign,
                'extension'   =>$request->extension,
                'start_date' =>  date('Y-m-d', strtotime("-1 year", time())),
                'end_date'  =>  date('Y-m-d', strtotime("+1 year", time()))
            );

             $callback_data = Helper::PostApi($url,$body);

            if($callback_data->success == 'true'){
                $callback = $callback_data->data;
                return view('callback.callback',compact('callback','arrCampaignListRekeyed','lower_limit','arrExtensionListRekeyed','arrExtensionListRekeyedByAltExtension','callbackStatus'));
                }
            }

        if ($request->isMethod('POST'))
        {
            $lower_limit = 0;
            $url = env('API_URL').'callback';
            $body=array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'campaign' => $request->campaign,
                'extension'   =>$request->extension,
                'start_date' => date('Y-m-d', strtotime($request->start_date)),
                'end_date'  =>  date('Y-m-d', strtotime($request->end_date)),
                'lower_limit'  =>  $lower_limit,
                'upper_limit'  =>  10,
            );

            try
            {
                $callback_data = Helper::PostApi($url,$body);

                if($callback_data->success == 'true'){
                    $callback = $callback_data->data;
                    return view('callback.callback',compact('callback','arrCampaignListRekeyed','lower_limit','arrExtensionListRekeyed','arrExtensionListRekeyedByAltExtension','callbackStatus'));
                }

                if($callback_data->success == 'false'){
                    return redirect('/');
                }
            }
               catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (callback): Oops something went wrong :( Please contact your administrator.)");
      }
            catch (RequestException $ex) {
                $message = "Page Not Found";
                return redirect('/');
            }
        }
    }

    public function editCallback(Request $request)
    {
        $url = env('API_URL') . "callback/edit";
        $body = [
            'mark_as_called' => $request->mark_as_called,
            'converted_to_utc' => $request->converted_to_utc,
            'callback_identifier' => $request->callback_identifier,
            'reassign_callback' => $request->reassign_callback
        ];

        try {
            $response = Helper::PostApi( $url, $body );
            if ($response->success) {
                return [$response->message,$response->data];
            } else {
                $errors = new MessageBag();
                $errors->add("error", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
        } catch (\Throwable $exception) {
            $errors = new MessageBag();
            $errors->add("error", $exception->getMessage());
            return $errors;
        }
    }

    public function getReminderCallBacks(Request $request){
        try{
            $inherit_list = new InheritApiController;
            $campaign_list =  $inherit_list->getCampaign();
            if(!is_array($campaign_list)){
                $campaign_list =array();
            }
            $arrCampaignListRekeyed = ClientPackageController::rekeyArray($campaign_list,'id');

            $url = env('API_URL').'callback';
            $start_date = date('Y-m-d H:i:s');
            $show_end_date = strtotime($start_date.' + 15 minute');
            $end_date =  date('Y-m-d H:i:s', $show_end_date);

            $body=array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'campaign_id' => $request->campaign,
                'extension'   => $request->extension,
                'start_date' => $start_date, //'2023-04-01 19:25:57', //$request->get("start_date"),
                'end_date'  => $end_date,//date('Y-m-d h:i:s'),//'2023-04-01 20:50:57',//$request->get("end_date"),
                'reminder' => true
            );

           // echo "<pre>";print_r($body);die;

            $response = Helper::PostApi($url,$body);
           // echo "<pre>";print_r($response);die;

            if($response->success == 'true') {
                $callbacks = (array) $response->data;
            } else {
                $errors = new MessageBag();
                $errors->add("error", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }

            foreach($callbacks as $key => $callback) {
                if(isset($arrCampaignListRekeyed[$callback->campaign_id])){
                    $callbacks[$key]->campaign_name = $arrCampaignListRekeyed[$callback->campaign_id]->title;
                } else {
                    $callbacks[$key]->campaign_name = '-';
                }
            }

            return $callbacks;
        } catch (\Throwable $exception) {
            $errors = new MessageBag();
            $errors->add("error", $exception->getMessage());
            return $errors;
        }
    }

    public function stopReminder(){
        try{
            $url = env('API_URL').'callback-reminder/stop';

            $response = Helper::GetApi($url);
            if($response->success == true) {
                $callbacks = [$response->message];
            } else {
                $errors = new MessageBag();
                $errors->add("error", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
            return $callbacks;
        } catch (\Throwable $exception) {
            $errors = new MessageBag();
            $errors->add("error", $exception->getMessage());
            return $errors;
        }
    }

    public function showReminder(){
        try{
            $url = env('API_URL').'callback-reminder/show';

            $response = Helper::GetApi($url);
            if($response->success == true) {
                $callbacks = [$response->message];
            } else {
                $errors = new MessageBag();
                $errors->add("error", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
            return $callbacks;
        } catch (\Throwable $exception) {
            $errors = new MessageBag();
            $errors->add("error", $exception->getMessage());
            return $errors;
        }
    }

    public static function getReminderStatus(){
        try{
            $url = env('API_URL').'callback-reminder/status';

            $response = Helper::GetApi($url);
            if($response->success == true) {
                $status = json_decode($response->data[0]);
            } else {
                $errors = new MessageBag();
                $errors->add("error", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
            return $status;
        } catch (\Throwable $exception) {
            $errors = new MessageBag();
            $errors->add("error", $exception->getMessage());
            return $errors;
        }
    }
}



