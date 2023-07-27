<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "clients";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $clients = $response->data;
            } else {
                $clients = [];
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("clients.list", compact("errors", $errors));
        }

        $userId = Session::get('id');

        $permission = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "user/".$userId."/permission";
        try {
            $response = Helper::GetApi($url);
            if (!empty($response)) {
                $permission = $response;
                $mapping = array();
                    foreach ( $permission as $map ) {
                        $mapping[] = $map->companyName;
                    }
            } else {
                $permission = [];
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("clients.list", compact("errors", $errors));
        }

        return view("clients.list")->with(["clients" => $clients,"permission"=> $permission,"mapping"=>$mapping]);
    }

    public function showNew(Request $request)
    {
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "servers/asterisk-server";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"]) {
                return view("clients.add")->with(["asteriskServers" => $response["data"]]);
            } else {
                session()->flash("message", $response["message"]);
                foreach ($response["errors"] as $key => $messages) {
                    foreach ($messages as $index => $message)
                        $errors->add("$key.$index", $message);
                }
                return view("clients.add")->withInput($request->input())->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("clients.add")->withErrors($errors);
        }
    }

    public function addNew(Request $request)
    {
        $this->validate($request, [
            'company_name' => 'required|string|max:255',
            'trunk' => 'required|string|max:30',
            'asterisk_servers' => 'required|array',
            'asterisk_servers.*' => 'required|integer',
            'enable_2fa'=>'required|string',
        ]);

        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "client";
            $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");
            if ($response->success) {
                session()->flash("success", "Client added");
                return redirect("client/" . $response->data->id);
            } else {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput()->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput()->withErrors($errors);
        }
    }

    public function show(Request $request, int $id)
    {
        $sms_provider = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "sms-provider/$id";
        try {
            $response = Helper::GetApi($url);
        //echo "<pre>";print_r($response);die;

            if ($response->success) {
                $sms_provider = $response->data;
            } else {
                $clients = [];
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("clients.list", compact("errors", $errors));
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
                return view("clients.edit")->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("clients.edit")->withErrors($errors);
        }

        //get packages
        $url = env('API_URL') . "packages";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $arrPackages = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("clients.edit", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("clients.edit", compact("errors", $errors));
        }

        return view("clients.edit")->with(["client" => $client, "arrPackages" => $arrPackages,'sms_provider' => $sms_provider]);
    }

    public function update(Request $request, int $id)
    {
        if($request->provider == 'didforsale' || $request->provider == 'plivo')
        {
            return $this->storeSMSProvider($request,$id);
        }

        $this->validate($request, [
            'company_name' => 'required|string|max:255',
            'trunk' => 'required|string|max:30',
            'asterisk_servers' => 'required|array',
            'asterisk_servers.*' => 'required|integer',
            'enable_2fa'   =>'required|string',
        ]);
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "client/$id";
            $response = Helper::PostApi($url, $this->getBuildBody($request));

            //echo "<pre>";print_r($response);die;

            if ($response->success) {
                Session::put('permissions', (array)$response->data->userPermissions);
            } else {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }
        session()->flash("success", "Client updated");
        return redirect()->back();
    }

    private function getBuildBody(Request $request)
    {
        $body = [
            "company_name" => trim($request->get("company_name")),
            "trunk" => trim($request->get("trunk")),
            "asterisk_servers" => $request->get("asterisk_servers"),
            "fax" => $request->get("fax")?1:0,
            "sms" => $request->get("sms")?1:0,
            "chat" => $request->get("chat")?1:0,
            "webphone" => $request->get("webphone")?1:0,
            "enable_2fa" => $request->get("enable_2fa"),

        ];
        if ($request->has("address_1")) $body["address_1"] = trim($request->get("address_1"));
        if ($request->has("address_2")) $body["address_2"] = trim($request->get("address_2"));
        if ($request->has("sms_plateform")) $body["sms_plateform"] = trim($request->get("sms_plateform"));
        
       
        return $body;
    }

     private function getBuildBodySMS(Request $request)
    {
        $body = [
            "auth_id" => trim($request->get("auth_id")),
            "api_key" => $request->get("api_key"),
            "provider" => $request->get("provider"),

        ];
        
        
       
        return $body;
    }

    public function performManualSubscription(Request $request)
    {
        $this->validate($request, [
            'package' => 'required|string',
            'parent' => 'required|integer',
            'quantity' => 'required|integer',
            'billing' => [
                "integer",
                Rule::requiredIf($request->get("package") != "588703ba-e78a-430f-8872-bb088dc1abba"),
            ]
        ]);

        $manualSubscriptionResponse = NULL;
        $errors = new MessageBag();
        $body = array(
            'client_id' => $request->get('parent'),
            'package' => $request->get("package"),
            'billing' => $request->get("billing"),
            'quantity' => $request->get("quantity")
        );

        try {
            $url = env('API_URL') . "client/manual-subscription";
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                $manualSubscriptionResponse = $response->message;
            } else {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }
        session()->flash("success", $manualSubscriptionResponse);
        return redirect()->back();
    }

    public function creditWallet(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|integer'
        ]);

        $creditWalletResponse = NULL;
        $errors = new MessageBag();
        $body = array(
            'amount' => $request->get('amount')
        );

        try {
            $url = env('API_URL') . "client/credit-wallet";
            $response = Helper::PostApi($url, $body);

            if ($response->success) {
                $creditWalletResponse = $response->message;
            } else {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }
        session()->flash("success", $creditWalletResponse);
        return redirect()->back();
    }


    public function storeSMSProvider(Request $request,$id)
    {
        $this->validate($request, [
            'auth_id' => 'required|string',
            'api_key' => 'required|string',
            'provider' => 'required|string'
        ]);



        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "sms-provider/$id";
            $response = Helper::RequestApi($url, "PUT", $this->getBuildBodySMS($request), "json");

            //echo "<pre>";print_r($response);die;
            if ($response->success) {
                session()->flash("success", "SMS Provider added");
                return redirect("client/" . $id);
            } else {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput()->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput()->withErrors($errors);
        }
    }
}
