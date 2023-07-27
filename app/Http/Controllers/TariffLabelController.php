<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\MessageBag;

class TariffLabelController extends Controller
{
  public function index()
  {
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
      return view("tariff_plans.tariff_labels", compact("errors", $errors));
    }
    return view('tariff_plans.tariff_labels',compact('tariff_plans'));
  }

  public function create(Request $request)
  {
    $this->validate($request, ['title' => 'required|string|max:255','description' =>'required|string|max:255' ]);
    $errors = new MessageBag();

    $tariff_label_id = $request->label_id;
    if(!empty($tariff_label_id))
    {
      return $this->update($request,$tariff_label_id);
    }

    else
    {
      try
      {
        $url = env('API_URL') . "tariff-plan";
        $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");
        if ($response->success)
        {
          session()->flash("success", "Tariff Label added");
          return redirect("tariff-labels");
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
    $tariff_label = null;
    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "tariff-plan/$id";
      $response = Helper::GetApi($url, [], true);
      if ($response["success"])
      {
        $tariff_label = $response["data"];
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
      return view("tariff_plans.tariff_labels")->withErrors($errors);
    }
    return $tariff_label;
  }

  public function update(Request $request, int $id)
  {
    $this->validate($request, ['title' => 'required|string|max:255','description' =>'required|string|max:255' ]);
    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "tariff-plan/$id";
      $response = Helper::PostApi($url, $this->getBuildBody($request));
      if ($response->success)
      {
        session()->flash("success", "Custom Field Label Updated");
        return redirect("tariff-labels");
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
    $body = ["title" => trim(ucwords($request->get("title"))),'description'=> trim(ucwords($request->get("description")))];
    return $body;
  }

  public function delete(Request $request, int $id)
  {
        $custom_field_labels = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "delete-tariff-label/$id";
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
                return view("configuration.custom_field_labels")->withErrors($errors);
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("configuration.custom_field_labels")->withErrors($errors);
        }
        return $response;
  }

}

