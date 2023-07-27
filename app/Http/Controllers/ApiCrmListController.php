<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\MessageBag;

class ApiCrmListController  extends Controller
{
    public function index(Request $request)
  {
    $crm_list = [];
    $errors = new MessageBag();
    
    // Default upper limit
    $upper_limit = 10;
  
    // Get selected value from filter
    $show = $request->input('show', 10);
        if ($show == 25 || $show == 50 || $show == 100) {
      $upper_limit = $show;
    }
  
    $urlpage = $request->page;
    
    $page=0;
    $lower_limit=0;
  
    if (!empty($urlpage) && $urlpage > 1)
    {
      $urlpage = $urlpage - 1;
      $lower_limit = $urlpage * $upper_limit;
    }
  
    if ($request->isMethod('post')) {
      $lower_limit = 0;
      $page=1;
    }
    $searchTerm = $request->input('search');
   

    $url = env('API_URL') . "crm-list";
    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'lower_limit' => $lower_limit,
      'upper_limit' => $upper_limit,
      'search'=>$searchTerm,
      
    );
    $response = Helper::PostApi($url,$body);
    //echo "<pre>";print_r($response);die;
    try
    {
        $crm_list = $response->data;
        $record_count = $response->record_count;
        if (!empty($crm_list)) {
          return view('crm_list.list', compact('crm_list', 'lower_limit', 'page', 'record_count', 'show','searchTerm'))->withErrors($errors);
      } else {
          if (!empty($searchTerm)) {
              // Search term is not found in the table
              return redirect('crm-list')->with('error', 'Search term not found');
          } else {
              // Table is empty
              return view('crm_list.list', compact('crm_list', 'lower_limit', 'page', 'record_count', 'show','searchTerm'))->withErrors($errors);
          }
      }
   
    }

    catch(RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return view("crm_list.list", compact("errors", $errors));
    }
  }
 
  public function create(Request $request)
  {
    $this->validate($request, ['title' => 'required|string|max:255','title_url' =>'required|string|max:255' ,'url' =>'required|string|max:255','key' =>'required|string|max:255']);
    $errors = new MessageBag();

    $crm_list_id = $request->crm_id;
    if(!empty($crm_list_id))
    {
      return $this->update($request,$crm_list_id);
    }

    else
    {
      try
      {
        $url = env('API_URL') . "crm-list";
        $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");



        if ($response->success)
        {
          session()->flash("success", "Crm List Added");
          return redirect("crm-list");
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
    $body = ["title" => $request->get("title"),'title_url'=> $request->get("title_url"),'url' => $request->get("url"),'key' => $request->get("key")];
    return $body;
  }
  public function show(Request $request, int $id)
  {
    $campaign_type = null;
    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "crm-list/$id";
      $response = Helper::GetApi($url, [], true);
      if ($response["success"])
      {
        $crm_list = $response["data"];
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
      return view("crm_list.list")->withErrors($errors);
    }
    return $crm_list;
  }
  public function update(Request $request, int $id)
  {
        $this->validate($request, ['title' => 'required|string|max:255','title_url' =>'required|string|max:255','url' =>'required|string|max:255' ]);

    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "crm-list/$id";
      $response = Helper::PostApi($url, $this->getBuildBody($request));
      if ($response->success)
      {
        session()->flash("success", "Crm List Updated");
        return redirect("crm-list");
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
    session()->flash("success", "Crm List Updated");
    return redirect()->back();
  }
  public function delete(Request $request, int $id)
  {
      $url = env('API_URL') . "delete-crm-list/$id";
      $response = Helper::RequestApi($url, "DELETE");
      if ($response->success) {
          session()->flash("success", $response->message);
      } else {
          session()->flash("message", $response->message);
      }
      return response()->json($response);
  }
  

}

