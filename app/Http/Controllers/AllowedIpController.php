<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\MessageBag;

class AllowedIpController extends Controller
{
  public function index()
  {
    $allowed_ips = [];
    $errors = new MessageBag();
    $url = env('API_URL') . "allowed-ips";
    try
    {
      $response = Helper::GetApi($url);
     // echo "<pre>";print_r($response);die;
      if($response->success)
      {
        $allowed_ips = $response->data;
      }
      else
      {
        $allowed_ips = [];
        foreach ($response->errors as $key => $message)
        {
          $errors->add($key, $message);
        }
      }
    }

    catch(RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return view("allowed-ip.list", compact("errors", $errors));
    }
    return view('allowed-ip.list',compact('allowed_ips'));
  }

  public function create(Request $request)
  {
    $this->validate($request, ['ip_address' => 'required|string|max:255','label' =>'required|string|max:255' ]);
    $errors = new MessageBag();

    $ip_id = $request->ip_id;
    if(!empty($ip_id))
    {
      return $this->update($request,$ip_id);
    }

    else
    {
      try
      {
        $url = env('API_URL') . "allowed-ip";
        $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");



        if ($response->success)
        {
          session()->flash("success", "Allowed IP added");
          return redirect("allowed-ips");
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
          return redirect()->back()->withInput()->withErrors($errors);
        }
      }
      catch (RequestException $ex)
      {
        $errors->add("error", $ex->getMessage());
        return redirect()->back()->withInput()->withErrors($errors);
      }
    }
  }

  public function show(Request $request, int $id)
  {
    $allowed_ip = null;
    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "allowed-ip/$id";
      $response = Helper::GetApi($url, [], true);
      if ($response["success"])
      {
        $allowed_ip = $response["data"];
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
        return view("tariff_plans.tariff_labels")->withErrors($errors);
      }
    }
    catch (RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return view("allowed-ip.list")->withErrors($errors);
    }
    return $allowed_ip;
  }

  public function update(Request $request, int $id)
  {
        $this->validate($request, ['ip_address' => 'required|string|max:255','label' =>'required|string|max:255' ]);

    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "allowed-ip/$id";
      $response = Helper::PostApi($url, $this->getBuildBody($request));
      if ($response->success)
      {
        session()->flash("success", "Allowed IP Updated");
        return redirect("allowed-ips");
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
    session()->flash("success", "Tariff Label updated");
    return redirect()->back();
  }

  private function getBuildBody(Request $request)
  {
    $body = ["ip_address" => trim($request->get("ip_address")),'label'=> trim(ucwords($request->get("label"))),'status' => $request->get("status"),'is_primary' => $request->get('is_primary')];
    return $body;
  }

  public function delete(Request $request, int $id)
  {
        $custom_field_labels = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "delete-allowed-ip/$id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"])
            {
                $custom_field_labels = $response["data"];
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
                return view("allowed-ip.list")->withErrors($errors);
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("allowed-ip.list")->withErrors($errors);
        }
        return $response;
  }

}

