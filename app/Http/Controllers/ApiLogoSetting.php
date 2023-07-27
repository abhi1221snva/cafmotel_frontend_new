<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Session;

class ApiLogoSetting extends Controller
{

    function index()
    {
        $inherit_list = new InheritApiController;
        $extension_list = $inherit_list->getExtension("users.email");
        if (!is_array($extension_list)) {
            Session::put('message', "Failed to fetch extension list");
            return view('configuration.logo-setting');
        }

        #Fetch SMTP setting
        $systemSetting = null;
        try {
            $url = env('API_URL') . '/smtp/type/system';
            $systemSetting = Helper::GetApi($url);
        } catch (\Throwable $e) {
            $systemSetting = [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }

        //Fetch Notification List:
        try {
            $url = env('API_URL') . 'notifications';
            $notifications = Helper::GetApi($url, [], true);
            if ($notifications["success"]) {
                return view('configuration.logo-setting')->with([
                    "extension_list" => $extension_list,
                    "system_setting" => $systemSetting,
                    "notifications" => $notifications["data"]
                ]);
            } else {
                Session::put('message', $notifications["message"]);
                return view('configuration.logo-setting')->with([
                    "extension_list" => $extension_list,
                    "system_setting" => $systemSetting
                ]);
            }
        } catch (\Throwable $e) {
            Session::put('message', "Failed to fetch notifications. ".$e->getMessage());
            return view('configuration.logo-setting')->with([
                "extension_list" => $extension_list,
                "system_setting" => $systemSetting
            ]);
        }
    }

    public function imageUploadPost(Request $request)
    {
        $old_logo = $request->old_logo;

        if (!empty($old_logo)) {
            if ($request->hasFile('image')) {
                if (file_exists(public_path("logo/$old_logo"))) {
                    unlink(public_path("logo/$old_logo"));
                };
            }
        }
        //echo Auth::id();die;
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $client_logo_name = 'client_' . Session::get('parentId');

        $imageName = $client_logo_name . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('logo'), $imageName);

        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'parentId' => Session::get('parentId'),
            'logo' => $imageName
        );

        $url = env('API_URL') . 'update-logo';

        /*$userdetails   = Helper::PostApi($url,$body);
        echo "<pre>";print_r($userdetails);die;*/
        try {
            $userdetails = Helper::PostApi($url, $body);
            if ($userdetails->success == 'true') {
                return back()->with('success', 'You have successfully change your Company profile image.')->with('image', $imageName);
            }

            if ($userdetails->success == 'false') {

                return back()->withSuccess($userdetails->message);

            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (user-detail): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (user-detail): Oops something went wrong :( Please contact your administrator.)");
        }

        //return back()->with('success','You have successfully change your profile image.')->with('image',$imageName);
    }

    function updateEmailSetting(Request $request)
    {
        $this->validate($request, [
            'notification_id' => 'required|array',
            //'active' => 'required|array',
            //'active_sms' => 'required|array',
            'subscribers' => 'required|array'
        ]);
        $body = [];
        $subscriptions = $request->all();
        foreach ($subscriptions["notification_id"] as $key => $val) {
            $body[] = [
                "notification_id" => $val,
                "active" => isset($subscriptions["active"][$key])?$subscriptions["active"][$key]:0,
                "active_sms" => isset($subscriptions["active_sms"][$key])?$subscriptions["active_sms"][$key]:0,
                "subscribers" => isset($subscriptions["subscribers"][$key])?$subscriptions["subscribers"][$key]:[]
            ];
        }
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "notifications";
            $response = Helper::PostApi($url, $body);
            if (!$response->success) {
                $errors->add("message", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage()."|".$ex->getLine()."|".$ex->getFile());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }
        session()->flash("success", "Notification setting saved");
        return redirect()->back();
    }

}

