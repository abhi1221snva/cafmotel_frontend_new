<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Session;
use App\Classes\ApiClass;



class LeadSourceConfigController extends Controller
{

    public function index(Request $request)
    {

        $inherit_list = new InheritApiController;
        
        $list_details = $inherit_list->getListList();
        if (!is_array($list_details)) {
            $list_details = array();
        }

        //0echo "<pre>";print_r($list_details);die;

        $leadsourceconfig = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "lead-source-configs";
         //   $response = Helper::PostApi($url);

       // echo "<pre>";print_r($response);die;
        try {
            $response = Helper::PostApi($url);
            if ($response->success) {
                $leadsourceconfig = $response->data;
            } else {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("leadsourceconfig.list", compact("errors", $errors));
        }
        
        //echo "<pre>";print_r($apilisturl);die;

        return view("leadsourceconfig.list", compact("leadsourceconfig", $leadsourceconfig,"list_details",$list_details));
    }



    public function showNew(Request $request)
    {
        $inherit_list = new InheritApiController;
        $list_details = $inherit_list->getListList();
        if (!is_array($list_details)) {
            $list_details = array();
        }
        $Api_key = $this->generateRandomString();

        return view("leadsourceconfig.add", compact('list_details','Api_key'));
    }



    function addNew(Request $request)
    {
        $this->validate($request, [
            'list_id' => 'required|string|max:255',
            'api_key' => 'required|string',
            'description' => 'required|string',
            'title' => 'required|string',
            'client_id' => 'required|int'

        ]);

        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "lead-source-config";
            $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");

            if ($response->success) {
                session()->flash("success", "Lead Source Config Added Successfully.");
                return redirect("lead-source-configs");
            } else {
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
            return redirect()->back()->withInput()->withErrors($errors);
        }
    }


    private function getBuildBody(Request $request)
    {
        $body = [
            "description" => trim($request->get("description")),
            "title" => trim($request->get("title")),
            "api_key" => trim($request->get("api_key")),
            "list_id" => trim($request->get("list_id")),
            "client_id" => trim($request->get("client_id")),

        ];

        return $body;
    }


    function generateRandomString($length = 30)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
            {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

        return $randomString;

    }


    public function getLeadHeader($list_id)
    {
        $list_header = null;
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "header-by-listid/$list_id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"]) {
                $list_header = $response["data"];
            } else {
                foreach ( $response["errors"] as $key => $message ) {
                    $errors->add($key, $message);
                }
                return withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return withErrors($errors);
        }
        return $list_header;
       
    }



    public function deleteLeadConfig($config_id)
    {

        $list_header = null;
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "delete-lead-source-config/$config_id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"]) {
                $list_header = $response["data"];
            } else {
                foreach ( $response["errors"] as $key => $message ) {
                    $errors->add($key, $message);
                }
                return withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return withErrors($errors);
        }
        return $list_header;
       
    }


    public function insertLeadSource(Request $request)
    {
		$apiClass = new ApiClass();
		$string = $apiClass->receieveLeadSource($request);
	}
}
