<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\MessageBag;



class CustomFieldLabelsValuesController extends Controller
{
    public function index()
    {
        $custom_field_labels = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "custom-field-labels";
        try
        {
            $response = Helper::GetApi($url);
            if($response->success)
            {
                $custom_field_labels = $response->data;
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
            return view("configuration.custom_fields_values", compact("errors", $errors));
        }

        $custom_fields_values = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "custom-field-labels-values";
        try
        {
            $response = Helper::GetApi($url);
            if($response->success)
            {
                $custom_fields_values = $response->data;
            }
            else
            {
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("configuration.custom_fields_values", compact("errors", $errors));
        }

        return view('configuration.custom_fields_values',compact('custom_fields_values','custom_field_labels'));
    }

    public function create(Request $request)
    {
        $this->validate($request, ['title_links' => 'required|string|max:255','title_match' => 'required|string']);
        $errors = new MessageBag();
        $custom_field_label_id = $request->value_id;
        if(!empty($custom_field_label_id))
        {
            return $this->update($request,$custom_field_label_id);
        }
        else
        {
            try
            {
                $url = env('API_URL') . "custom-field-labels-value";
                $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");
                if ($response->success)
                {
                    session()->flash("success", "Custom Field Label added");
                    return redirect("custom-fields-values");
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
        $custom_field_labels = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "custom-field-value/$id";
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
            return view("clients.edit")->withErrors($errors);
        }
        return $custom_field_labels;
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, ['title_links' => 'required|string|max:255','title_match' => 'required|string']);
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "custom-field-value/$id";
            $response = Helper::PostApi($url, $this->getBuildBody($request));
            if ($response->success)
            {
                session()->flash("success", "Custom Field Label Updated");
                return redirect("custom-fields-values");
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
        session()->flash("success", "Client updated");
        return redirect()->back();
    }

    private function getBuildBody(Request $request)
    {
        $body = ["title_links" => trim($request->get("title_links")),
              "title_match" => trim($request->get("title_match")),
              "custom_id" => $request->get("custom_id"),
              "user_id" => trim($request->get("user_id"))];
        return $body;
    }

    public function getCustomFieldValue(Request $request, int $id)
    {
        $custom_field_labels = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "custom-label-value/$id";
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
            return view("clients.edit")->withErrors($errors);
        }
        return $custom_field_labels['title_links'];
    }

    public function delete(Request $request, int $id)
  {
        $custom_field_labels = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "delete-custom-field-value/$id";
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

