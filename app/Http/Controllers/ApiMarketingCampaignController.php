<?php

namespace App\Http\Controllers;
use App\Helper\Helper;
use App\User;
use Session;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
class ApiMarketingCampaignController extends Controller
{
  public function index(Request $request)
  {

    $errors = new MessageBag();

    /* list list */
    $list = [];
    $url = env('API_URL') . "list";
    try
    {
      $response = Helper::PostApi($url);
      if ($response->success) {
        $list = $response->data;
      }
      else
      {
        foreach ( $response->errors as $key => $message ) {
          $errors->add($key, $message);
        }
      }
    }
    catch (RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return view("marketing_campaign.list", compact("errors", $errors));
    }

    /* close list */

    /* sms templates list */
    $sms_templates = [];
    $url = env('API_URL') . "sms-templete";
      //$response = Helper::GetApi($url);
      //echo "<pre>";print_r($response);die;
    try
    {
      $response = Helper::GetApi($url);
      if ($response->success) {
        $sms_templates = $response->data;
      //echo "<pre>";print_r($sms_templates);die;

      }
      else
      {
        foreach ( $response->errors as $key => $message ) {
          $errors->add($key, $message);
        }
      }
    }

    catch (RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return view("marketing_campaign.list", compact("errors", $errors));
    }

    /* close sms templates */

    /* email templates list */
    $email_templates = [];
    $url = env('API_URL') . "email-templates";
    try
    {
      $response = Helper::GetApi($url);
      if ($response->success) {
        $email_templates = $response->data;
      }
      else
      {
        foreach ( $response->errors as $key => $message ) {
          $errors->add($key, $message);
        }
      }
    }

    catch (RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return view("marketing_campaign.list", compact("errors", $errors));
    }

    /* close email templates */

    /* smtps list */
    $smtp_setting = [];
    $url = env('API_URL') . "smtps";
    try
    {
      $response = Helper::GetApi($url);
      if ($response->success) {
        $smtp_setting = $response->data;
      }
      else
      {
        foreach ( $response->errors as $key => $message ) {
          $errors->add($key, $message);
        }
      }
    }

    catch (RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return view("marketing_campaign.list", compact("errors", $errors));
    }

    /* close smtps */

    /* did list */
    $did = [];
    $url = env('API_URL') . "did";
    $body = array(

            'id' => Session::get('id')
        );

      //echo "<pre>";print_r($body);die;


    try
    {
      //$response = Helper::PostApi($url,$body);
      $response = Helper::PostApi($url, $body);

     // echo "<pre>";print_r($response);die;
      if ($response->success) {
        $did = $response->data;
      }
      else
      {
        foreach ( $response->errors as $key => $message ) {
          $errors->add($key, $message);
        }
      }
    }

    catch (RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return view("marketing_campaign.list", compact("errors", $errors));
    }

    /* close did */

    $marketing_campaigns = [];
    $url = env('API_URL') . "marketing-campaigns";
    try
    {
      $response = Helper::GetApi($url);
      if ($response->success) {
        $marketing_campaigns = $response->data;
      }

      else
      {
        foreach ( $response->errors as $key => $message ) {
          $errors->add($key, $message);
        }
      }
    }

    catch (RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return view("marketing_campaign.list", compact("errors", $errors));
    }
    return view("marketing_campaign.list", compact("marketing_campaigns",'email_templates','smtp_setting','list','sms_templates','did'));
  }

  public function show(Request $request, int $id)
  {
    $marketing_campaign = null;
    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "marketing-campaign/$id";
      $response = Helper::GetApi($url, [], true);
      if ($response["success"])
      {
        $marketing_campaign = $response["data"];
      }
      else
      {
        foreach ( $response["errors"] as $key => $message )
        {
          $errors->add($key, $message);
        }
        //return view("marketing_campaign.edit")->withErrors($errors);
        return $errors;
      }
    }
    catch (RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      //return view("marketing_campaign.edit")->withErrors($errors);
      return $errors;
    }
    return $marketing_campaign;
    //return view("marketing_campaign.edit")->with(["marketing_campaign" => $marketing_campaign]);
  }

  function addNew(Request $request)
  {
    if(!empty($request->input('marketing_id'))) {
      $id = $request->input('marketing_id');
      return $this->update($request,$id);
    }
    else{
    $this->validate($request, [
      'title' => 'required|string|max:255',
      'description' => 'required|string'
    ]);
    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "marketing-campaign";
      $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");
      if ($response->success)
      {
        session()->flash("success", "Marketing Campaign Added");
        return redirect("marketing-campaigns");
      }
      else
      {
        foreach ( $response->errors as $key => $messages )
        {
          if (is_array($messages))
          {
            foreach ( $messages as $index => $message )
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
    $body = [
      "title" => trim($request->get("title")),
      "description" => trim($request->get("description"))
    ];
    return $body;
  }

  public function update(Request $request, int $id)
  {
    //echo "s";die;
    $this->validate($request, [
      'title' => 'required|string|max:255',
      'description' => 'required|string'
    ]);

    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "marketing-campaign/$id";
      $response = Helper::PostApi($url, $this->getBuildBody($request));
      // echo "<pre>";print_r($response);die;
      if (!$response->success)
      {
        foreach ( $response->errors as $key => $message )
        {
          $errors->add($key, $message);
        }
        return redirect()->back()->withInput($request->input())->withErrors($errors);
      }
    }
    catch (RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return redirect()->back()->withInput($request->input())->withErrors($errors);
    }

    session()->flash("success", "Campaign updated");
    return redirect()->back();
  }
}

