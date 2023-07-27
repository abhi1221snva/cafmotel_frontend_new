<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\MessageBag;



class TariffLabelValuesController extends Controller
{
  public function index()
  {

    /* Phone Country list */
        $phone_country = [];
        $url = env('API_URL').'phone-country-list';
        try
        {
            $response = Helper::PostApi($url);
            if ($response->success)
            {
                $phone_country = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
        }


        $tariff_plans = [];
    $errors = new MessageBag();
    $url = env('API_URL') . "tariff-labels";
    try
    {
      $response = Helper::GetApi($url);
      if($response->success)
      {
        $tariff_plans = $response->data;
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
      return view("tariff_plans.tariff_label_values", compact("errors", $errors));
    }

    $tariff_plans_value = [];
    $errors = new MessageBag();
    $url = env('API_URL') . "tariff-label-values";
      $response = Helper::GetApi($url);
      //echo "<pre>";print_r($response);die;
    try
    {
      if($response->success)
      {
        $tariff_plans_value = $response->data;
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
      return view("tariff_plans.tariff_label_values", compact("errors", $errors));
    }
    return view('tariff_plans.tariff_label_values',compact('tariff_plans','tariff_plans_value','phone_country'));
  }


  public function create(Request $request)
  {
    /* Phone Country list */
    $phone_country = [];
    $url = env('API_URL').'phone-country-list';
    try
    {
      $response = Helper::PostApi($url);
      if ($response->success)
      {
        $phone_country = $response->data;
      }
      else
      {
        foreach ( $response->errors as $key => $message )
        {
          $errors->add($key, $message);
        }
      }
    }
    catch (RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
    }

    $tariff_plans = [];
    $errors = new MessageBag();
    $url = env('API_URL') . "tariff-labels";
    try
    {
      $response = Helper::GetApi($url);
      if($response->success)
      {
        $tariff_plans = $response->data;
      }
      else
      {
        $tariff_plans = [];
        foreach ($response->errors as $key => $message)
        {
          $errors->add($key, $message);
        }
      }
    }
    catch (RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
    }
    return view("tariff_plans.add-tariff-values",compact('phone_country','tariff_plans'));
  }

  public function show(Request $request, int $tariff_id)
  {
    $tariff_label_value = null;
    $errors = new MessageBag();
    try {
            $url = env('API_URL') . "tariff-label-value/$tariff_id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"]) {
                $tariff_label_value = $response["data"];
            } else {
                foreach ( $response["errors"] as $key => $message ) {
                    $errors->add($key, $message);
                }
                return view("tariff_plans.edit-tariff-values")->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("tariff_plans.edit-tariff-values")->withErrors($errors);
        }

         /* Phone Country list */
        $phone_country = [];
        $url = env('API_URL').'phone-country-list';
        try
        {
            $response = Helper::PostApi($url);
            if ($response->success)
            {
                $phone_country = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
        }


        $tariff_plans = [];
    $errors = new MessageBag();
    $url = env('API_URL') . "tariff-labels";
    try
    {
      $response = Helper::GetApi($url);
      if($response->success)
      {
        $tariff_plans = $response->data;
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
      return view("tariff_plans.tariff_label_values", compact("errors", $errors));
    }
    
    
    return view("tariff_plans.edit-tariff-values",compact("tariff_label_value","tariff_plans","phone_country"));
  }

  function addNew(Request $request)
  {
    //dd($request);
        $this->validate($request, [
            'tariff_id'      => 'required|integer',
            "phone_countries_id"    => "required|array",
            "rate"          => "required|array"
        ]);
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "tariff-label-value";
            $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");
            //echo "<pre>";print_r($response);die;
            if ($response->success) {
                session()->flash("success", "Tariff Label Values created");
                return redirect("tariff-label-values");
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

  public function update(Request $request, int $tariff_id)
  {
      $this->validate($request, [
            'tariff_id'      => 'required|integer',
            "phone_countries_id"    => "required|array",
            "rate"          => "required|array"
        ]);
    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "tariff-label-value/$tariff_id";
      $response = Helper::PostApi($url, $this->getBuildBody($request));
      
      if ($response->success)
      {
        session()->flash("success", "Tariff Label value Updated");
                return redirect("tariff-label-values");
        
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
    session()->flash("success", "Tariff Label Values updated");
    return redirect()->back();
  }

  private function getBuildBody(Request $request)
  {
    $body = ["tariff_id" => trim(ucwords($request->get("tariff_id"))),'phone_countries_id'=> $request->get("phone_countries_id"),'rate'=>$request->get('rate'),'tariff_value_id'=>$request->get('tariff_value_id')];
    return $body;
  }

  public function delete(Request $request, int $id)
  {
        $custom_field_labels = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "delete-tariff-label-value/$id";
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
                return view("tariff_plans.edit-tariff-values")->withErrors($errors);
                
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("tariff_plans.edit-tariff-values")->withErrors($errors);
        }
        return $response;
  }

}

