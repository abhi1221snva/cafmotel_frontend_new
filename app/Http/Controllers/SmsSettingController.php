<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Mail\TestMail;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;

class SmsSettingController extends Controller
{

    public function index(Request $request)
    {
        $errors = new MessageBag();
        try {
            $users = [];
            $inherit_list = new InheritApiController();
            $extension_list = $inherit_list->getExtension("users.email");
            if (!is_array($extension_list)) {
                $errors->add("message", "Failed to fetch extension list");
            } else {
                foreach ( $extension_list as $user ) {
                    $users[$user->id] = $user->first_name . " " . $user->last_name;
                }
            }

            $campaigns = [];
            $campaign_list = $inherit_list->getCampaign();
            if (!is_array($campaign_list)) {
                $errors->add("message", "Failed to fetch campaign list");
            } else {
                foreach ( $campaign_list as $campaign ) {
                    $campaigns[$campaign->id] = $campaign->title;
                }
            }

            $url = env('API_URL') . "sms-setting";
            $response = Helper::GetApi($url);
            $sms = [];
            if ($response->success) {
                $sms = $response->data;
            } else {
                $smtps = [];
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
                return view("sms-setting.list")->withErrors($errors);
            }
            return view("sms-setting.list")->with([
                "sms_setting" => $sms,
                "campaigns" => $campaigns,
                "users" => $users
            ]);
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("sms-setting.list")->withErrors($errors);
        }
    }

    public function showNew()
    {
        if (session("level") < 7) {
            $url = env('API_URL') . "smtps";
            $response = Helper::GetApi($url);
            if ($response->success) {
                if (count($response->data) > 0) {
                    return redirect()->route('sms-setting.edit', ['id' => $response->data[0]->id]);
                }
            }
        }

        $errors = session('errors');
        if (empty($errors)) $errors = new MessageBag();
        $inherit_list = new InheritApiController();
        $extension_list = $inherit_list->getExtension("users.email");
        if (!is_array($extension_list)) {
            $errors->add("message", "Failed to fetch extension list");
            $extension_list = [];
        }
        $campaign_list = $inherit_list->getCampaign();
        if (!is_array($campaign_list)) {
            $errors->add("message", "Failed to fetch campaign list");
            $campaign_list = [];
        }
        return view("sms-setting.add")->with([
            "extensions" => $extension_list,
            "campaigns" => $campaign_list
        ]);
    }

    function addNew(Request $request)
    {
        $this->validate($request, [
            'sms_url' => 'required|string|max:255',
            'sender_name' => 'required|string|max:255',
            'api_key' => 'required|string',
            'sender_type' => 'required|string',
            'user_id' => 'required_if:sender_type,user|nullable|int',
            'campaign_id' => 'required_if:sender_type,campaign|nullable|int',
            'from_number' => 'required|string|max:255',
            'sms_auth_id' => 'required|string|max:255',
        ]);

        try {
            $errors = new MessageBag();

            $url = env('API_URL') . "setting-sms";
            $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");
            echo "<pre>";print_r($response);die;
            if ($response->success) {
                session()->flash("success", "SMS Setting Added");
                return redirect("sms-settings");
            } else {
                $errors->add("message", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ( $messages as $index => $message )
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput()->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function show(Request $request, int $id)
    {
        $sms = null;
        $errors = session('errors');
        if (empty($errors)) $errors = new MessageBag();
        try {
            $inherit_list = new InheritApiController();
            $extension_list = $inherit_list->getExtension("users.email");
            if (!is_array($extension_list)) {
                $errors->add("message", "Failed to fetch extension list");
                $extension_list = [];
            }
            $campaign_list = $inherit_list->getCampaign();
            if (!is_array($campaign_list)) {
                $errors->add("message", "Failed to fetch campaign list");
                $campaign_list = [];
            }

            $url = env('API_URL') . "setting-sms/$id";
            $response = Helper::GetApi($url);
            //echo "<pre>";print_r($response);die;
            if ($response->success) {
                $sms = (array)$response->data;
            } else {
                $errors->add("message", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ( $messages as $index => $message )
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return view("sms-setting.edit")->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("sms-setting.edit")->withErrors($errors);
        }
        return view("sms-setting.edit")->with([
            "extensions" => $extension_list,
            "campaigns" => $campaign_list,
            "smtp" => $sms
        ]);
    }

    public function update(Request $request, int $id)
    {
            //dd($request);die;
        $this->validate($request, [
            'sms_url' => 'required|string|max:255',
            'sender_name' => 'required|string|max:255',
            'api_key' => 'required|string',
            'sender_type' => 'required|string',
            'user_id' => 'required_if:sender_type,user|nullable|int',
            'campaign_id' => 'required_if:sender_type,campaign|nullable|int',
            'from_number' => 'required|string|max:255',
            'sms_auth_id' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "setting-sms/$id";
            $response = Helper::PostApi($url, $this->getBuildBody($request));

            //echo "<pre>";print_r($this->getBuildBody($request));die;
            // echo "<pre>";print_r($response);die;
            if (!$response->success) {
                $errors->add("message", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ( $messages as $index => $message )
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()
                    ->withInput($request->input())
                    ->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()
                ->withInput($request->input())
                ->withErrors($errors);
        }
        session()->flash("success", "SMS Setting updated");
        return redirect()->back();
    }

    private function getBuildBody(Request $request)
    {
        $body = [
            'sms_url' => trim($request->get("sms_url")),
            "sender_name" => trim($request->get("sender_name")),
            "api_key" => trim($request->get("api_key")),
            "sender_type" => trim($request->get("sender_type")),
            "from_number" => trim($request->get("from_number")),
            "sms_auth_id" => trim($request->get("sms_auth_id")),
            "status" => $request->get("status"),
        ];
        $userId = $request->get("user_id", null);
        $campaignId = $request->get("campaign_id", null);
        if (!empty($userId)) $body["user_id"] = (int)$userId;
        if (!empty($campaignId)) $body["campaign_id"] = (int)$campaignId;
        return $body;
    }

    function checkSMSSetting(Request $request)
    {
        $apiKey = urlencode('NjE3NTUwNGQ1NjVhNGQ0MzZjNjM3YTUxNTk1ODQyNDU=');
// Message details
$numbers = array(917838626612,917053747047);
$sender = urlencode('TXTLCL');
$message = rawurlencode('This is your message');
 
$numbers = implode(',', $numbers);
 
// Prepare data for POST request
$data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
// Send the POST request with cURL
$ch = curl_init("https://api.textlocal.in/send/");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
// Process your response here
echo $response;
    }

    public function delete(int $id)
    {
        try {
            $url = env('API_URL') . "sms-delete/$id";
            $response = Helper::RequestApi($url, "DELETE");
            return response()->json($response);
        } catch (\Throwable $ex) {
            return response()->json([
                "success" => false,
                "message" => $ex->getMessage()
            ]);
        }
    }
}

