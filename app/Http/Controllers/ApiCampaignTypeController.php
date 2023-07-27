<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\MessageBag;

class ApiCampaignTypeController extends Controller
{
  public function index()
  {
    $campaign_type = [];
    $errors = new MessageBag();
    $url = env('API_URL') . "campaign-type";
    try
    {
      $response = Helper::GetApi($url);
     // echo "<pre>";print_r($response);die;
      if($response->success)
      {
        $campaign_type = $response->data;
      }
      else
      {
        $campaign_type = [];
        foreach ($response->errors as $key => $message)
        {
          $errors->add($key, $message);
        }
      }
    }

    catch(RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return view("campaign_type.list", compact("errors", $errors));
    }
    return view('campaign_type.list',compact('campaign_type'));
  }
  public function create(Request $request)
  {
    $this->validate($request, ['title' => 'required|string|max:255','title_url' =>'required|string|max:255' ]);
    $errors = new MessageBag();

    $campaign_type_id = $request->campaign_id;
    if(!empty($campaign_type_id))
    {
      return $this->update($request,$campaign_type_id);
    }

    else
    {
      try
      {
        $url = env('API_URL') . "campaign-type";
        $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");



        if ($response->success)
        {
          session()->flash("success", "Campaign Type Added");
          return redirect("campaign-type");
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
  private function getBuildBody(Request $request)
  {
    $body = ["title" => $request->get("title"),'title_url'=> $request->get("title_url"),'status' => $request->get("status")];
    return $body;
  }
  public function show(Request $request, int $id)
  {
    $campaign_type = null;
    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "campaign-type/$id";
      $response = Helper::GetApi($url, [], true);
      if ($response["success"])
      {
        $campaign_type = $response["data"];
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
      return view("campaign_type.list")->withErrors($errors);
    }
    return $campaign_type;
  }
  public function update(Request $request, int $id)
  {
        $this->validate($request, ['title' => 'required|string|max:255','title_url' =>'required|string|max:255' ]);

    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "campaign-type/$id";
      $response = Helper::PostApi($url, $this->getBuildBody($request));
      if ($response->success)
      {
        session()->flash("success", "Campaign Type Updated");
        return redirect("campaign-type");
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
    session()->flash("success", "Campaign Type Updated");
    return redirect()->back();
  }

  public function delete(Request $request, int $id)
  {
        $custom_field_labels = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "delete-campaign-type/$id";
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
                return view("campaign_type.list")->withErrors($errors);
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("campaign_type.list")->withErrors($errors);
        }
        return $response;
  }


}

