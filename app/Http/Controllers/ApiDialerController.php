<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Session;


class ApiDialerController extends Controller
{
    function __construct(Request $request)
    {
        $this->request = $request;
    }


    function indexStartDialingNew(Request $request)
    {
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }

        $webPhoneEnabled = 0;
        $vm_drop = Session::get('vm_drop');

        //get webPhone setting
        $url = env('API_URL') . 'webphone/status';
        try{
            $webphoneStatusResponse = (array) Helper::GetApi($url);
            $webPhoneEnabled = $webphoneStatusResponse['data'][0];
        } catch(\Throwable $e){
            //Only logging the issue, no dependencies on old dialer functionality
            Log::error("ApiDialerController.warning", [
                "user-id" => Session::get("id"),
                "parent-id" => Session::get("parentId"),
                "error" => $e->getMessage()
            ]);
        }


        //Fetch Campaign List:
        $url = env('API_URL') . 'agent-campaign';
        try {
            $campaign = Helper::PostApi($url);
            if (!$campaign->success) {
                Session::put('message', $campaign->message);
                return view('dialer.start_dialer_new',[$webPhoneEnabled]);
            }
        } catch (\Throwable $e) {
            Session::put('message', "Failed to fetch campaigns." . $e->getMessage());
            return view('dialer.start_dialer_new',[$webPhoneEnabled]);
        }

        #Campaign not selected and submitted
        $campaignId = null;
        if ($request->has("campaign")) {
            $campaignId = $request->campaign;
        } elseif ($campaign->login) {
            $campaignId = $campaign->login->campaign_id;
        }

        if (empty($campaignId)) {
            return view('dialer.start_dialer_new', ['campaign' => $campaign, 'webPhoneEnabled' => $webPhoneEnabled]);
        }

        //extension login
        try {
            $url = env('API_URL') . 'extension-login';
            $body = array(
                'campaign_id' => $campaignId
            );
            $extension = Helper::PostApi($url, $body);
            if (!$extension->success) {
                Session::put('message', $extension->message);
                return view('dialer.start_dialer_new', compact('campaign','webPhoneEnabled'));
            }
        } catch (\Throwable $e) {
            Session::put('message', "Extension login failed");
            return view('dialer.start_dialer_new', compact('campaign','webPhoneEnabled'));
        }

        //campaign detail
        $campaignDetail = [];
        try {
            $url = env('API_URL') . 'campaign-by-id';
            $body = array(
                'campaign_id' => $campaignId
            );
            $campaignDetail = Helper::PostApi($url, $body);
            $campaignDetail = (array)$campaignDetail[0];
            $campaignDetail['user_id'] = Session::get('id');
            $campaignDetail['user_token'] = Session::get('tokenId');
            $campaignDetail['extension'] = Session::get('extension');
        } catch (\Throwable $e) {
            Session::put('message', "Failed to fetch campaign details. ". $e->getMessage());
            return view('dialer.start_dialer_new', compact('campaign','webPhoneEnabled'));
        }

        //disposition
        try {
            $url = env('API_URL') . 'disposition_by_campaignId';
            $body = array(
                'campaign_id' => $campaignId
            );
            $dispositionDetail = Helper::PostApi($url, $body);
            $dispositionDetail = (array)$dispositionDetail->data;

            return view('dialer.start_dialer_new', compact('vm_drop', 'campaign', 'campaignDetail', 'dispositionDetail','webPhoneEnabled'));
        } catch (\Throwable $e) {
            Session::put('message', "Failed to fetch disposition list");
            return view('dialer.start_dialer_new', compact('campaign','webPhoneEnabled'));
        }
    }

    function indexDemo(Request $request)
    {

        $id = Session::get('parentId');
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }


        $client = null;
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "client/$id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"]) {
                $client = $response["data"];
            } else {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
              //  return view("clients.edit")->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            //return view("clients.edit")->withErrors($errors);
        }

        //echo "<pre>";print_r($client);die;


        $inherit_list = new InheritApiController;
        $fax_did_list = $inherit_list->getUserFaxDidList();

        //echo "<pre>";print_r($group);die;


        $receiveFax = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "receive-fax-list";
        try {
            $response = Helper::PostApi($url);
            if ($response->success) {
                $receiveFax = $response->data;
            } else {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("dialer.start_dialer_demo", compact("errors", $errors));
        }

        //echo "<pre>";print_r($receiveFax);die;



        $sms_number_list =[];
        $url = env('API_URL') . 'sms_did_list';
        $sms_number_list = Helper::GetApi($url);
        //echo "<pre>";print_r($sms_number_list);die;

        $webPhoneEnabled = 0;
        $vm_drop = Session::get('vm_drop');

        //get webPhone setting
        $url = env('API_URL') . 'webphone/status';
        try{
            $webphoneStatusResponse = (array) Helper::GetApi($url);
            $webPhoneEnabled = $webphoneStatusResponse['data'][0];
        } catch(\Throwable $e){
            //Only logging the issue, no dependencies on old dialer functionality
            Log::error("ApiDialerController.warning", [
                "user-id" => Session::get("id"),
                "parent-id" => Session::get("parentId"),
                "error" => $e->getMessage()
            ]);
        }

        /* Phone Country list */
        $phone_country = [];
        $url = env('API_URL').'phone-country-list';
        try
        {
            $response = Helper::PostApi($url);
            //echo "<pre>";print_r($response);die;
            if ($response->success)
            {
                $phone_country = $response->data;
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
        }


            //echo "<pre>";print_r($phone_country);die;

        $url_sms = env('API_URL') . 'sms';
        $sms_list = Helper::GetApi($url_sms);



        //Fetch Campaign List:
        $url = env('API_URL') . 'agent-campaign';
        try {
            $campaign = Helper::PostApi($url);
            if (!$campaign->success) {
                Session::put('message', $campaign->message);

       // echo "<pre>";print_r($sms_number_list);die;

                return view('dialer.start_dialer_demo',[$webPhoneEnabled,'sms_number_list' => $sms_number_list,'phone_country'=>$phone_country,'sms_list'=>$sms_list,'fax_did_list' => $fax_did_list,'receiveFax'=>$receiveFax,'client'=>$client]);
            }
        } catch (\Throwable $e) {
            Session::put('message', "Failed to fetch campaigns." . $e->getMessage());
            return view('dialer.start_dialer_demo',[$webPhoneEnabled]);
        }


        #Campaign not selected and submitted
        $campaignId = null;
        if ($request->has("campaign")) {
            $campaignId = $request->campaign;
        } elseif ($campaign->login) {
            $campaignId = $campaign->login->campaign_id;
        }

        if (empty($campaignId)) {
            return view('dialer.start_dialer_demo', ['campaign' => $campaign, 'webPhoneEnabled' => $webPhoneEnabled,'sms_number_list' => $sms_number_list,'phone_country'=>$phone_country,'sms_list'=>$sms_list,'fax_did_list'=>$fax_did_list,'receiveFax'=>$receiveFax,'client'=>$client]);
        }

        //extension login
        try {
            $url = env('API_URL') . 'extension-login';
            $body = array(
                'campaign_id' => $campaignId
            );
            $extension = Helper::PostApi($url, $body);
            if (!$extension->success) {
                Session::put('message', $extension->message);
                return view('dialer.start_dialer_demo', compact('campaign','webPhoneEnabled'));
            }
        } catch (\Throwable $e) {
            Session::put('message', "Extension login failed");
            return view('dialer.start_dialer_demo', compact('campaign','webPhoneEnabled'));
        }

        //campaign detail
        $campaignDetail = [];
        try {
            $url = env('API_URL') . 'campaign-by-id';
            $body = array(
                'campaign_id' => $campaignId
            );
            $campaignDetail = Helper::PostApi($url, $body);
            $campaignDetail = (array)$campaignDetail[0];
            $campaignDetail['user_id'] = Session::get('id');
            $campaignDetail['user_token'] = Session::get('tokenId');
            $campaignDetail['extension'] = Session::get('extension');
        } catch (\Throwable $e) {
            Session::put('message', "Failed to fetch campaign details. ". $e->getMessage());
            return view('dialer.start_dialer_demo', compact('campaign','webPhoneEnabled'));
        }

        //disposition
        try {
            $url = env('API_URL') . 'disposition_by_campaignId';
            $body = array(
                'campaign_id' => $campaignId
            );
            $dispositionDetail = Helper::PostApi($url, $body);
            $dispositionDetail = (array)$dispositionDetail->data;


            return view('dialer.start_dialer_demo', compact('vm_drop', 'campaign', 'campaignDetail', 'dispositionDetail','webPhoneEnabled','sms_number_list'));
        } catch (\Throwable $e) {
            Session::put('message', "Failed to fetch disposition list");
            return view('dialer.start_dialer_demo', compact('campaign','webPhoneEnabled'));
        }
    }

    function index(Request $request)
    {
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }

        $webPhoneEnabled = 0;
        $vm_drop = Session::get('vm_drop');

        //get webPhone setting
        $url = env('API_URL') . 'webphone/status';
        try{
            $webphoneStatusResponse = (array) Helper::GetApi($url);
            $webPhoneEnabled = $webphoneStatusResponse['data'][0];
        } catch(\Throwable $e){
            //Only logging the issue, no dependencies on old dialer functionality
            Log::error("ApiDialerController.warning", [
                "user-id" => Session::get("id"),
                "parent-id" => Session::get("parentId"),
                "error" => $e->getMessage()
            ]);
        }


        //Fetch Campaign List:
        $url = env('API_URL') . 'agent-campaign';
        try {
            $campaign = Helper::PostApi($url);
            if (!$campaign->success) {
                Session::put('message', $campaign->message);
                return view('dialer.start_dialer',[$webPhoneEnabled]);
            }
        } catch (\Throwable $e) {
            Session::put('message', "Failed to fetch campaigns." . $e->getMessage());
            return view('dialer.start_dialer',[$webPhoneEnabled]);
        }

        #Campaign not selected and submitted
        $campaignId = null;
        if ($request->has("campaign")) {
            $campaignId = $request->campaign;
        } elseif ($campaign->login) {
            $campaignId = $campaign->login->campaign_id;
        }

        if (empty($campaignId)) {
            return view('dialer.start_dialer', ['campaign' => $campaign, 'webPhoneEnabled' => $webPhoneEnabled]);
        }

        //extension login
        try {
            $url = env('API_URL') . 'extension-login';
            $body = array(
                'campaign_id' => $campaignId
            );
            $extension = Helper::PostApi($url, $body);
            if (!$extension->success) {
                Session::put('message', $extension->message);
                return view('dialer.start_dialer', compact('campaign','webPhoneEnabled'));
            }
        } catch (\Throwable $e) {
            Session::put('message', "Extension login failed");
            return view('dialer.start_dialer', compact('campaign','webPhoneEnabled'));
        }

        //campaign detail
        $campaignDetail = [];
        try {
            $url = env('API_URL') . 'campaign-by-id';
            $body = array(
                'campaign_id' => $campaignId
            );
            $campaignDetail = Helper::PostApi($url, $body);
            $campaignDetail = (array)$campaignDetail[0];
            $campaignDetail['user_id'] = Session::get('id');
            $campaignDetail['user_token'] = Session::get('tokenId');
            $campaignDetail['extension'] = Session::get('extension');
        } catch (\Throwable $e) {
            Session::put('message', "Failed to fetch campaign details. ". $e->getMessage());
            return view('dialer.start_dialer', compact('campaign','webPhoneEnabled'));
        }

        //disposition
        try {
            $url = env('API_URL') . 'disposition_by_campaignId';
            $body = array(
                'campaign_id' => $campaignId
            );
            $dispositionDetail = Helper::PostApi($url, $body);
            $dispositionDetail = (array)$dispositionDetail->data;

            return view('dialer.start_dialer', compact('vm_drop', 'campaign', 'campaignDetail', 'dispositionDetail','webPhoneEnabled'));
        } catch (\Throwable $e) {
            Session::put('message', "Failed to fetch disposition list");
            return view('dialer.start_dialer', compact('campaign','webPhoneEnabled'));
        }
    }

    public function callNumber()
    {
        $url = env('API_URL') . 'call-number';
        $body = array(
            'id' => $this->request->user_id,
            'token' => $this->request->user_token,
            'number' => $this->request->number,
            'campaign_id' => $this->request->campaign,
            'lead_id' => $this->request->lead

        );
        try {
            $call = Helper::PostApi($url, $body);
            if (isset($call->success) && $call->success == 'true') {
                echo json_encode(array('status' => "true", 'message' => "Connecting call ......"));
            } else {
                echo json_encode(array('status' => "false", 'message' => "Unable to connect call."));
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (call-number): Oops something went wrong :( Please contact your administrator.)");
        }
        exit;
    }

    public function hangUp()
    {
        $url = env('API_URL') . 'hang-up';
        $body = array(
            'id' => $this->request->user_id,
            'token' => $this->request->user_token
        );

        try {
            $hangUp = Helper::PostApi($url, $body);
            echo json_encode(array('status' => $hangUp->success, 'message' => $hangUp->message));
            exit;
        } catch (BadResponseException   $e) {

            return back()->with('message', "Error code - (hang-up): Oops something went wrong :( Please contact your administrator.)");


        }

    }

    public function dtmf()
    {
        $url = env('API_URL') . 'dtmf';
        $body = array(
            'id' => $this->request->user_id,
            'token' => $this->request->user_token,
            'number' => $this->request->digit
        );

        try {
            $dtmf = Helper::PostApi($url, $body);
            echo json_encode(array('status' => $dtmf->success, 'message' => $dtmf->message));
            exit;
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (dtmf): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public function voicemailDrop()
    {
        $url = env('API_URL') . 'voicemail-drop';
        $body = array(
            'id' => $this->request->user_id,
            'token' => $this->request->user_token
        );

        try {
            $voicemail = Helper::PostApi($url, $body);
            echo json_encode(array('status' => $voicemail->success, 'message' => $voicemail->message));
            exit;
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (voicemail-drop): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public function getLead()
    {
        $url = env('API_URL') . 'get-lead';
        try {
            $lead = Helper::GetApi($url, [], true);
            $lead["csrf_token"] = csrf_token();
            return response()->json($lead);
        } catch (\Throwable $exception) {
            return [
                'success' => false,
                'message' => "Failed to fetch lead data",
                'number' => null,
                'lead_id' => null,
                'data' => []
            ];
        }
    }

    public function saveDisposition()
    {
        try {
            $url = env('API_URL') . 'save-disposition';
            $callBack = (!empty($this->request->call_back_time)) ? date('Y-m-d H:i:s', strtotime($this->request->call_back_time)) : '';
            $body = array(
                'id' => $this->request->user_id,
                'token' => $this->request->user_token,
                'campaign_id' => $this->request->campaign,
                'disposition_id' => $this->request->disposition,
                'lead_id' => $this->request->lead,
                'api_call' => $this->request->api,
                'comment' => $this->request->comment,
                'pause_calling' => $this->request->pause_calling,
                'call_back' => $callBack
            );

            $result = Helper::PostApi($url, $body);
            try {
                if (empty($result)) {
                    return response()->json(['status' => false, 'message' => "Failed to dispose"]);
                } else {
                    return response()->json(['status' => (bool)$result->success, 'message' => $result->message]);
                }
            } catch (\Throwable $throwable) {
                Log::error("saveDisposition.error", [
                    "user-id" => Session::get("id"),
                    "parent-id" => Session::get("parentId"),
                    "url" => $url,
                    "body" => $body,
                    "result" => $result,
                    "error" => $throwable->getMessage()
                ]);
                return response()->json(['status' => false, 'message' => "Failed to dispose"]);
            }
        } catch (\Throwable $exception) {
            Log::error("saveDisposition.error", [
                "user-id" => Session::get("id"),
                "parent-id" => Session::get("parentId"),
                "error" => $exception->getMessage()
            ]);
            return response()->json(['status' => false, 'message' => "Failed to dispose."]);
        }
    }

    public function redialCall()
    {
        try {
            $url = env('API_URL') . 'redial-call';
            $callBack = (!empty($this->request->call_back_time)) ? date('Y-m-d H:i:s', strtotime($this->request->call_back_time)) : '';
            $body = array(
                'id' => $this->request->user_id,
                'token' => $this->request->user_token,
                'campaign_id' => $this->request->campaign,
                'disposition_id' => $this->request->disposition,
                'lead_id' => $this->request->lead,
                'api_call' => $this->request->api,
                'comment' => $this->request->comment,
                'pause_calling' => $this->request->pause_calling,
                'call_back' => $callBack,
                'listId' => $this->request->listId,


            );


            $result = Helper::PostApi($url, $body);
            //echo "<pre>";print_r($result);die;
            try {
                if (empty($result)) {
                    return response()->json(['status' => false, 'message' => "Failed to dispose"]);
                } else {
                    return response()->json(['status' => (bool)$result->success, 'message' => $result->message]);
                }
            } catch (\Throwable $throwable) {
                Log::error("saveDisposition.error", [
                    "user-id" => Session::get("id"),
                    "parent-id" => Session::get("parentId"),
                    "url" => $url,
                    "body" => $body,
                    "result" => $result,
                    "error" => $throwable->getMessage()
                ]);
                return response()->json(['status' => false, 'message' => "Failed to dispose"]);
            }
        } catch (\Throwable $exception) {
            Log::error("saveDisposition.error", [
                "user-id" => Session::get("id"),
                "parent-id" => Session::get("parentId"),
                "error" => $exception->getMessage()
            ]);
            return response()->json(['status' => false, 'message' => "Failed to dispose."]);
        }
    }

    public function sendToCrmUser()
    {
        $url = env('API_URL') . 'send-to-crm';
        $body = array(
            'id' => $this->request->user_id,
            'token' => $this->request->user_token,
            'campaign_id' => $this->request->campaign,
            'lead_id' => $this->request->lead,
            'number' => $this->request->number
        );

        $result = Helper::PostApi($url, $body);
        //echo "<pre>";print_r($result);die;
        try {
            $result = Helper::PostApi($url, $body);
            if (isset($result->url) && !empty($result->data)) {
                $queryString = $result->url . "?" . http_build_query($result->data);
                echo json_encode(array('status' => "success", 'url' => $queryString));
            } else {
                echo json_encode(array('status' => "false", 'message' => "Unable to call CRM"));
            }
            exit;
        } catch (BadResponseException   $e) {

            return back()->with('message', "Error code - (save-disposition): Oops something went wrong :( Please contact your administrator.)");


        }
    }

    public function updateLeadData(Request $request, int $leadId)
    {
        $this->validate($request, [
            'values' => 'required|array'
        ]);

        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "update-lead/$leadId";
            $body = [];
            foreach ( $request->input("values") as $field => $value ) {
                $body[$field] = $value;
            }
            $response = Helper::PostApi($url, $body);
            return response()->json($response);
        } catch (\Throwable $exception) {
            return [
                'success' => false,
                'message' => "Failed to update lead data",
                'data' => []
            ];
        }
    }

    public function getCsrfToken()
    {
        return response()->json(["token" => csrf_token()]);
    }

    public function getHopperCount($campaignId)
    {
        try {
            $url = env('API_URL') . 'lead-temp';
            $body = [
                'campaign_id' => $campaignId
            ];
            $response = Helper::PostApi($url, $body);
            return response()->json($response);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => "Failed to fetch lead count",
                'count' => null
            ];
        }
    }

    public function add_new_lead_pd(){
        $nxt_call   = $this->request->nxt_call;
        $lead_id    = $this->request->lead_id;
        $list_id    = $this->request->list_id;
        $url        = env('API_URL') . 'add-new-lead-pd';
        $body = [
            'nxt_call'=> $this->request->nxt_call,
            'lead_id' => $this->request->lead_id,
            'list_id' => $this->request->list_id,
            'token' => Session::get('tokenId')
        ];
        $response = Helper::PostApi($url, $body);
        echo $url; print_r($body); echo Session::get('tokenId'); exit;
        if ($response->success) {
            return response()->json($response->data);
        } else {
            return response()->json($response->errors, 500);
        }
    }

    public function switchSoftphoneUse(Request $request)
    {
        $strResponseMessage = '';
        $errors = new MessageBag();
        $url = env('API_URL') . 'webphone/switch-access';
        $body = ['is_checked' => $request->is_checked];

        try {
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                $strResponseMessage = (array)$response->message;
            } else {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return $errors;
        }

        return $strResponseMessage;
    }

    public function openWebPhone(){
        return view('dialer.webphone');
    }
}
