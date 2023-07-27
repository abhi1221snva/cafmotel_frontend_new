<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\MessageBag;

class HubspotController extends Controller
{
  public function getContactInAList(Request $request, int $id)
  {
    $contact_list = null;
    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "get-contact-in-a-list/$id";
      $response = Helper::GetApi($url, [], true);

   //  echo "<pre>";print_r($response);die;

      if ($response["success"])
      {
        $contact_list = $response["data"];
      }
      else
      {
        /*foreach ($response->errors as $key => $messages)
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
        }*/
        return view("hubspot.contact_list")->withErrors($errors);
      }
    }
    catch (RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return view("hubspot.contact_list")->withErrors($errors);
    }

     // echo "<pre>";print_r($contact_list);die;

        return view('hubspot.contact_list',compact('contact_list'));

  }

  

}

