<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
class ApiSipChannelController  extends Controller
{
  public function index(Request $request)
  {
      $errors = new MessageBag();
  
      // Default upper limit
      $upper_limit = 10;
  
      // Get selected value from filter
      $show = $request->input('show', 10);
      if ($show == 25 || $show == 50 || $show == 100) {
          $upper_limit = $show;
      }
  
      $urlpage = $request->page;
  
      $page = 0;
      $lower_limit = 0;
  
      if (!empty($urlpage) && $urlpage > 1) {
          $urlpage = $urlpage - 1;
          $lower_limit = $urlpage * $upper_limit;
      }
  
      if ($request->isMethod('post')) {
          $lower_limit = 0;
          $page = 1;
      }
      $searchTerm = $request->input('search');
  
  
      $url = env('API_URL') . "sip-channel-provider";
      $body = [
          'id' => Session::get('id'),
          'token' => Session::get('tokenId'),
          'lower_limit' => $lower_limit,
          'upper_limit' => $upper_limit,
          'search' => $searchTerm,
      ];
      $response = Helper::PostApi($url, $body);
      
      $campaignTypes = $response->campaign_types;
      try {
        $sip_channel = $response->data;
        $record_count = $response->record_count;
        //echo "<pre>";print_r($record_count);die;

        if ($response->success=='true') {
            return view('sip_channel_provider.list', compact('sip_channel', 'lower_limit', 'page', 'record_count', 'show', 'campaignTypes', 'searchTerm'))->withErrors($errors);
        } elseif($response->success=='false') {
          if (!empty($searchTerm)) {
            // Search term is not found in the table
            return redirect('sip-channel-provider')->with('error', 'Search term not found');
        } else {
            // Table is empty
            session()->flash("error", "data not found");
            return view('sip_channel_provider.list', compact('sip_channel', 'lower_limit', 'page', 'record_count', 'show', 'campaignTypes','searchTerm'));
        }
        }
    } catch (\Throwable $exception) {
        $errors = new MessageBag();
        $errors->add("error", $exception->getMessage());
        return view('sip_channel_provider.list', compact('campaignTypes'))->withErrors($errors);
    }
  
   
}

//   public function indexo(Request $request)
//   {
//       $errors = new MessageBag();
  
//       // Default upper limit
//       $upper_limit = 10;
  
//       // Get selected value from filter
//       $show = $request->input('show', 10);
//       if ($show == 25 || $show == 50 || $show == 100) {
//           $upper_limit = $show;
//       }
  
//       $urlpage = $request->page;
  
//       $page = 0;
//       $lower_limit = 0;
  
//       if (!empty($urlpage) && $urlpage > 1) {
//           $urlpage = $urlpage - 1;
//           $lower_limit = $urlpage * $upper_limit;
//       }
  
//       if ($request->isMethod('post')) {
//           $lower_limit = 0;
//           $page = 1;
//       }
//       $searchTerm = $request->input('search');
  
  
//       $url = env('API_URL') . "sip-channel-provider";
//       $body = [
//           'id' => Session::get('id'),
//           'token' => Session::get('tokenId'),
//           'lower_limit' => $lower_limit,
//           'upper_limit' => $upper_limit,
//           'search' => $searchTerm,
//       ];
//       $response = Helper::PostApi($url, $body);
//       //echo "<pre>";print_r($response);die;
  
//       $campaignTypes = $response->campaign_types;
  
//       try {
//         $sip_channel = $response->data;
//         $record_count = $response->record_count;

//         if (!empty($sip_channel)) {
//             return view('sip_channel_provider.list', compact('sip_channel', 'lower_limit', 'page', 'record_count', 'show', 'campaignTypes'));
//         } else {
//             if (!empty($searchTerm)) {
//                 // Search term is not found in the table
//                 return redirect('sip-channel-provider')->with('error', 'Search term not found');
//             } else {
//                 // Table is empty
//                 return view('sip_channel_provider.list', compact('sip_channel', 'lower_limit', 'page', 'record_count', 'show', 'campaignTypes'));
//             }
//         }
//     } catch (RequestException $ex) {
//         $errors->add("error", $ex->getMessage());
//         return view("sip_channel_provider.list", compact("errors", $errors));
//     }
// }
  
    
 
  public function create(Request $request)
  {
    $this->validate($request, ['title' => 'required|string|max:255','channel_provider' =>'required|string|max:255']);
    $errors = new MessageBag();

    $sip_list_id = $request->sip_id;
    if(!empty($sip_list_id))
    {
      return $this->update($request,$sip_list_id);
    }

    else
    {
      try
      {
        $url = env('API_URL') . "sip-channel-provider";
        $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");

      //echo "<pre>";print_r($response);die;


        if ($response->success)
        {
          session()->flash("success", "Sip Channel Provider List Added");
          return redirect("sip-channel-provider");
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
    $body = ["title" => $request->get("title"),'channel_provider'=> $request->get("channel_provider"),'status' => $request->get("status")];
    return $body;
  }
  public function show(Request $request, int $id)
  {
    $campaign_type = null;
    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "sip-channel-provider/$id";
      $response = Helper::GetApi($url, [], true);
      if ($response["success"])
      {
        $sip_list = $response["data"];
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
      return view("sip_channel_provider.list")->withErrors($errors);
    }
    return $sip_list;
  }
  public function update(Request $request, int $id)
  {
        $this->validate($request, ['title' => 'required|string|max:255','channel_provider' =>'required|string|max:255' ]);

    $errors = new MessageBag();
    try
    {
      $url = env('API_URL') . "sip-channel-provider/$id";
      $response = Helper::PostApi($url, $this->getBuildBody($request));
      if ($response->success)
      {
        session()->flash("success", "Sip Provider List Updated");
        return redirect("sip-channel-provider");
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
    session()->flash("success", "Sip Provider List Updated");
    return redirect()->back();
  }
  public function delete(Request $request, int $id)
  {
      $url = env('API_URL') . "delete-sip-channel-provider/$id";
      $response = Helper::RequestApi($url, "DELETE");
      if ($response->success) {
          session()->flash("success", $response->message);
      } else {
          session()->flash("message", $response->message);
      }
      return response()->json($response);
  }

  // public function deleteo(Request $request, int $id)
  // {
  //       $custom_field_labels = null;
  //       $errors = new MessageBag();
  //       try
  //       {
  //           $url = env('API_URL') . "delete-sip-channel-provider/$id";
  //           $response = Helper::GetApi($url, [], true);
  //           if ($response["success"])
  //           {
  //               $custom_field_labels = $response["data"];
  //           }
  //           else
  //           {
  //               foreach ($response->errors as $key => $messages)
  //               {
  //                   if (is_array($messages))
  //                   {
  //                       foreach ($messages as $index => $message)
  //                           $errors->add("$key.$index", $message);
  //                   }
  //                   else
  //                   {
  //                       $errors->add($key, $messages);
  //                   }
  //               }
  //               return view("sip_channel_provider.list")->withErrors($errors);
  //           }
  //       }
  //       catch (RequestException $ex)
  //       {
  //           $errors->add("error", $ex->getMessage());
  //           return view("sip_channel_provider.list")->withErrors($errors);
  //       }
  //       return $response;
  // }

  
}

