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
use Session;

class ApiSmtpSetting extends Controller
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

            $url = env('API_URL') . "smtps";
            $response = Helper::GetApi($url);
            $smtps = [];
            if ($response->success) {
                $smtps = $response->data;
            } else {
                $smtps = [];
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
                return view("smtp.list")->withErrors($errors);
            }
            return view("smtp.list")->with([
                "smtps" => $smtps,
                "campaigns" => $campaigns,
                "users" => $users
            ]);
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("smtp.list")->withErrors($errors);
        }
    }

    public function showNew()
    {
        if (session("level") < 7) {
            $url = env('API_URL') . "smtps";
            $response = Helper::GetApi($url);
            if ($response->success) {
                if (count($response->data) > 0) {
                    return redirect()->route('smtp.edit', ['id' => $response->data[0]->id]);
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
        return view("smtp.add")->with([
            "extensions" => $extension_list,
            "campaigns" => $campaign_list
        ]);
    }

    function addNew(Request $request)
    {
        $this->validate($request, [
            'mail_driver' => 'required|string|max:255',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|string',
            'mail_username' => 'required|string',
            'mail_password' => 'required|string',
            'mail_encryption' => 'required|string',
            'sender_type' => 'required|string',
            'from_email' => 'required_unless:sender_type,user|nullable|email',
            'from_name' => 'required_unless:sender_type,user|nullable|string',
            'user_id' => 'required_if:sender_type,user|nullable|string',
            'campaign_id' => 'required_if:sender_type,campaign|nullable|string',
        ]);

        try {
            $errors = new MessageBag();

            $url = env('API_URL') . "smtp";
            $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");
            // echo "<pre>";print_r($response);die;
            if ($response->success) {
                session()->flash("success", "Smtp Added");
                return redirect("smtps");
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
        $smtp = null;
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

            $url = env('API_URL') . "smtp/$id";
            $response = Helper::GetApi($url);
            if ($response->success) {
                $smtp = (array)$response->data;
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
                return view("smtp.edit")->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("smtp.edit")->withErrors($errors);
        }
        return view("smtp.edit")->with([
            "extensions" => $extension_list,
            "campaigns" => $campaign_list,
            "smtp" => $smtp
        ]);
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'mail_driver' => 'required|string|max:255',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|string',
            'mail_username' => 'required|string',
            'mail_password' => 'required|string',
            'mail_encryption' => 'required|string',
            'sender_type' => 'required|string',
            'from_email' => 'required_unless:sender_type,user|nullable|email',
            'from_name' => 'required_unless:sender_type,user|nullable|string',
            'user_id' => 'required_if:sender_type,user|nullable|numeric',
            'campaign_id' => 'required_if:sender_type,campaign|nullable|numeric',
        ]);

        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "smtp/$id";
            $response = Helper::PostApi($url, $this->getBuildBody($request));
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
        session()->flash("success", "Smtp updated");
        return redirect()->back();
    }

    private function getBuildBody(Request $request)
    {
        $body = [
            'mail_driver' => trim($request->get("mail_driver")),
            "mail_host" => trim($request->get("mail_host")),
            "mail_port" => trim($request->get("mail_port")),
            "mail_username" => trim($request->get("mail_username")),
            "mail_password" => trim($request->get("mail_password")),
            "mail_encryption" => trim($request->get("mail_encryption")),
            "sender_type" => trim($request->get("sender_type")),
            "from_email" => trim($request->get("from_email")),
            "from_name" => trim($request->get("from_name"))
        ];
        $userId = $request->get("user_id", null);
        $campaignId = $request->get("campaign_id", null);
        if (!empty($userId)) $body["user_id"] = (int)$userId;
        if (!empty($campaignId)) $body["campaign_id"] = (int)$campaignId;
        return $body;
    }

    function checkSMTPSetting(Request $request)
    {
        //var_dump($request->all());die;
        $config = array(
            'driver' => $request->mail_driver,
            'host' => $request->mail_host,
            'port' => $request->mail_port,
            'encryption' => $request->mail_encryption,
            'username' => $request->mail_username,
            'password' => $request->mail_password,
            'sendmail' => '/usr/sbin/sendmail -bs',
            'pretend' => false
        );

        $toEmail = session()->get('emailId');
        try {
            if ($request->sender_type == "user") {
                $inherit_list = new InheritApiController();
                $extension_list = $inherit_list->getExtension(null, $request->user_id);
                if (empty($extension_list)) {
                    throw new \Exception("Invalid user selection");
                }
                $address = $extension_list->email;
                $name = $extension_list->first_name . " " . $extension_list->last_name;
            } else {
                $address = $request->from_email ?? "test@cafmotel.com";
                $name = $request->from_name ?? "Email Test";
            }

            $tempConfig = Config::get("mail");
            Config::set('mail', $config);
            //var_dump(config("mail"));die;
            Mail::to($toEmail)->sendNow(new TestMail([
                'address' => $address,
                'name' => $name
            ], 'Email Setting Test'));
            Config::set('mail', $tempConfig);
            return response()->json(["success" => true]);
        } catch (\Throwable $throwable) {
            Log::error("checkSMTPSetting", [
                "message" => $throwable->getMessage(),
                "file" => $throwable->getFile(),
                "line" => $throwable->getLine()
            ]);
            return response()->json(["success" => false, "message" => $throwable->getMessage()]);
        }
    }

    public function delete(int $id)
    {
        try {
            $url = env('API_URL') . "smtp/$id";
            $response = Helper::RequestApi($url, "DELETE");
            return response()->json($response);
        } catch (\Throwable $ex) {
            return response()->json([
                "success" => false,
                "message" => $ex->getMessage()
            ]);
        }
    }


    public function copySmtp(Request $request)
    {
        $body = array('id' => Session::get('id'),'token' => Session::get('tokenId'),'c_id' => $request->id);
        $url = env('API_URL') . 'copy-smtp';
        try {
            $addcampaign = Helper::PostApi($url, $body);
            if ($addcampaign->success == 'true') {
                return redirect('/smtps')->withSuccess($addcampaign->message);
            }

            if ($addcampaign->success == 'false') {
                return back()->withSuccess($addcampaign->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (copy-campaign): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {

            return back()->with('message', "Error code - (copy-campaign): Oops something went wrong :( Please contact your administrator.)");
        }
    }
}

