<?php

namespace App\Http\Controllers;

use Illuminate\Support\MessageBag;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;


class ApiIpSettingController extends Controller
{
    public function getIpSetting()
    {
        $url = env('API_URL') . "servers/client-servers";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                return view('configuration.ip-setting')->with([
                    'asteriskServers' => $response->data
                ]);
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
                return view('configuration.ip-setting')->withErrors($errors);
            }
        } catch (\Throwable $exception) {
            $errors = new MessageBag();
            $errors->add("error", $exception->getMessage());
            return view('configuration.ip-setting')->withErrors($errors);
        }
    }

    public function queryWhitelist(Request $request)
    {
        $where = [];

        $fromWeb = $request->get("fromWeb", null);
        if (!is_null($fromWeb)) {
            array_push($where, "fromWeb=$fromWeb");
        }
        $approvalStatus = $request->get("approvalStatus", null);
        if (!is_null($approvalStatus)) {
            array_push($where, "approvalStatus=$approvalStatus");
        }
        $asteriskServer = $request->get("asteriskServer", null);
        if (!is_null($asteriskServer)) {
            array_push($where, "asteriskServer=$asteriskServer");
        }
        $whitelistIp = $request->get("whitelistIp", null);
        if (!is_null($whitelistIp)) {
            array_push($where, "whitelistIp=$whitelistIp");
        }
        $url = env('API_URL') . "ip/query-ip-whitelist";
        if (count($where) > 0) {
            $url .= "?".implode("&", $where);
        }
        $response = Helper::GetApi($url);
        return response()->json($response);
    }

    public function approveWhitelist(Request $request)
    {
        $this->validate($request, [
            'serverIp' => ["sometimes", "required", "ip"],
            'whitelistIp' => ["sometimes", "required", "ip"]
        ]);
        try {
            $url = env('API_URL') . "ip/approve";
            $response = Helper::PostApi($url, $request->all());
            return response()->json($response);
        } catch (\Throwable $ex) {
            return response()->json([
                "success" => false,
                "message" => $ex->getMessage()
            ]);
        }
    }

    public function rejectWhitelist(Request $request)
    {
        $this->validate($request, [
            'serverIp' => ["sometimes", "required", "ip"],
            'whitelistIp' => ["sometimes", "required", "ip"]
        ]);
        try {
            $url = env('API_URL') . "ip/reject";
            $response = Helper::PostApi($url, $request->all());
            return response()->json($response);
        } catch (\Throwable $ex) {
            return response()->json([
                "success" => false,
                "message" => $ex->getMessage()
            ]);
        }
    }

    public function whitelistIp(Request $request)
    {
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "servers/client-servers";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"]) {
                return view("configuration.ip-whitelist")->with(["asteriskServers" => $response["data"]]);
            } else {
                session()->flash("message", $response["message"]);
                foreach ( $response["errors"] as $key => $messages ) {
                    foreach ($messages as $index => $message)
                        $errors->add("$key.$index", $message);
                }
                return view("configuration.ip-whitelist")->withInput($request->input())->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("configuration.ip-whitelist")->withErrors($errors);
        }
    }

    public function whitelistIpSave(Request $request)
    {
        $this->validate($request, [
            'whitelistIp' => 'required|ip',
            'asteriskServers' => 'required|array',
            'asteriskServers.*' => 'required|integer'
        ]);

        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "ip/whitelist-ip";
            $response = Helper::PostApi($url, $request->all());
            if ($response->success) {
                session()->flash("success", "Ip whitelisted");
                return redirect("ip-setting");
            } else {
                foreach ( $response->errors as $key => $messages ) {
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

