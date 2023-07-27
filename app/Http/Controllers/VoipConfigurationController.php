<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\MessageBag;



class VoipConfigurationController extends Controller
{
    public function index()
    {
        $voip_configurations = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "voip-configurations";
        $response = Helper::GetApi($url);
        try
        {
            if($response->success)
            {
                $voip_configurations = $response->data;
            }
            else
            {
                $custom_field_labels = [];
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("voip_configuration.voip-configurations", compact("errors", $errors));
        }
        return view('voip_configuration.voip-configurations',compact('voip_configurations'));
    }

    public function create(Request $request)
    {
        return view("voip_configuration.add-voip-configuration");
    }

    function addNew(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|string',
            "host"      => "required|string",
            //"username"  => "required|string",
            //"secret"  => "required|string",
            //"prefix"    => "required",
        ]);

        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "voip-configuration";
            $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");
            //echo "<pre>";print_r($response);die;
            if ($response->success)
            {
                session()->flash("success", "VoIP Configuration created");
                return redirect("voip-configurations");
            }
            else
            {
                foreach ( $response->errors as $key => $messages )
                {
                    if (is_array($messages))
                    {
                        foreach ( $messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    }
                    else
                    {
                        $errors->add($key, $messages);
                    }
                }

                return redirect()->back()->withInput()->withErrors($errors);
            }
        }

        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput()->withErrors($errors);
        }
    }

    public function show(Request $request, int $voip_id)
    {
        $voip_configuration = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "voip-configuration/$voip_id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"])
            {
                $voip_configuration = $response["data"];
            }
            else
            {
                foreach ( $response["errors"] as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return view("voip_configuration.edit-voip-configuration")->withErrors($errors);
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("voip_configuration.edit-voip-configuration")->withErrors($errors);
        }
        return view("voip_configuration.edit-voip-configuration",compact("voip_configuration"));
    }

    public function update(Request $request, int $voip_id)
    {
        $this->validate($request, [
            'name'      => 'required|string',
            "host"      => "required|string",
            //"username"  => "required|string",
            //"secret"  => "required|string",
            //"prefix"    => "required",
        ]);

        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "voip-configuration/$voip_id";
            $response = Helper::PostApi($url, $this->getBuildBody($request));
            //echo  "<pre>";print_r($response);die;
            if ($response->success)
            {
                session()->flash("success", "Voip Configuration value Updated");
                return redirect("voip-configurations");
            }

            else
            {
                foreach ($response->errors as $key => $messages)
                {
                    if (is_array($messages))
                    {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    }
                    else
                    {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }
        }

        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }

        session()->flash("success", "Voip Configuration value updated");
        return redirect()->back();
    }

    private function getBuildBody(Request $request)
    {
        $body = ["name" => trim(ucwords($request->get("name"))),'username'=> $request->get("username"),'secret'=>$request->get('secret'),'prefix'=>$request->get('prefix'),'host'=>$request->get('host'),'user_extension_id'=>$request->get('user_extension_id')];
        return $body;
    }

    public function delete(Request $request, int $id)
    {
        $voip_configuration = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "delete-voip-configuration/$id";
            $response = Helper::GetApi($url, [], true);
            echo "<pre>";print_r($response);die;
            if ($response["success"])
            {
                $voip_configuration = $response["data"];
            }
            else
            {
                foreach ($response->errors as $key => $messages)
                {
                    if (is_array($messages))
                    {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    }
                    else
                    {
                        $errors->add($key, $messages);
                    }
                }
                return view("voip_configuration.voip-configurations")->withErrors($errors);
                
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("voip_configuration.voip-configurations")->withErrors($errors);
        }
        return $response;
  }

}

