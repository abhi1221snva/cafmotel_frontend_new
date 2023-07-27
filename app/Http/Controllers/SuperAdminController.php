<?php

namespace App\Http\Controllers\III_Ranks;

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\InheritApiController;

use Session;

class SuperAdminController extends Controller
{
    function index(Request $request)
    {
        $title = "Super Admin List | " . env('APP_NAME');
        $inherit_list = new InheritApiController;
        $extension_list = $inherit_list->getExtensionList();
        if (!is_array($extension_list))
        {
            $extension_list = array();
        }

        if (empty($extension_list))
        {
            if (empty(Session::get('tokenId')))
            {
                return redirect('/');
            }
        }
        return view('super-admin.index', compact('extension_list', 'title'));
    }

    public function show(Request $request, int $id)
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
            return view("super-admin.edit", compact("errors", $errors));
        }

        $permission = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "user/".$id."/permission";
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
            return view("super-admin.edit", compact("errors", $errors));
        }

       //echo "<pre>";print_r($permission);die;
    
        return view("super-admin.edit")->with(["clients" => $clients,"permission"=> $permission,"mapping"=>$mapping]);
    }


    public function update(Request $request, $userId)
    {
        $this->validate($request, [
            "clients_name" => "required|array"
        ]);

        $body = [
            "clients_name" => $request->input("clients_name"),
            "user_id"=>$userId,
            "roleId"=>5
        ];

        $url = env('API_URL') . "user/{userId}/super-admin-permission";
        $response = Helper::PostApi($url, $body, "json");
        return redirect()->back()->withSuccess("Super Admin updated Successfully");
    }


 
}


