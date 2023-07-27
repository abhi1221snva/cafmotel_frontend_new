<?php


namespace App\Http\Controllers;

use App\Helper\Helper;
use App\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Session;

class CliReportController extends Controller
{
  public function index(Request $request)
  {
    $cli_report = [];
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
    $cli = $request->input('cli');
    $cnam = $request->input('cnam');

    $url = env('API_URL') . "cli-report";
    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'lower_limit' => $lower_limit,
      'upper_limit' => $upper_limit,
      'search'=>$searchTerm,
      // 'cli' => $cli,
      // 'cnam' => $cnam,
      
    );
    $response = Helper::PostApi($url,$body);
    //echo "<pre>";print_r($response);die;
    try
    {
      
      if($response->success=='true')
      {
        $cli_report = $response->data;
        $record_count = $response->record_count;
        return view('cli-report.list',compact('cli_report','lower_limit','page','record_count','show'));
      }
      else
      {
        $cli_report = [];
        foreach ($response->errors as $key => $message)
        {
          $errors->add($key, $message);
        }
      }
    }

    catch(RequestException $ex)
    {
      $errors->add("error", $ex->getMessage());
      return view("cli-report.list", compact("errors", $errors));
    }
  }
  public function callManually(Request $request)
  {
    $body = array('number'=>$request->phone_number);
        $errors = new MessageBag();
       // try {
            $url = env('API_URL') . "run-manually-call-for-cname";
            $response = Helper::PostApi($url, $body);
             return json_encode($response);
            /*if (!$response->success) {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }*/
        /*} catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }
        session()->flash("success", "Manually Call Created");
        return redirect()->back();*/
   
    
  }

  public function callManuallyCNAM(Request $request)
  {
    $body = array('number'=>$request->phone_number,'did_value' => $request->did_value);
        $errors = new MessageBag();
       // try {
            $url = env('API_URL') . "run-manually-call-for-did";
            $response = Helper::PostApi($url, $body);
             return json_encode($response);
            /*if (!$response->success) {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }*/
        /*} catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }
        session()->flash("success", "Manually Call Created");
        return redirect()->back();*/
   
    
  }


  public function findCliReport(Request $request) {
//echo $request->phone_number;die;
            $url = env('API_URL') . "find-cli-report/".$request->phone_number;
            $response = Helper::GetApi($url);
             return json_encode($response);

  }

   private function getBuildBody(Request $request)
    {
        $body = [
            "number" => trim($request->get("phone_number"))
        ];

        return $body;
    }

    // function fetch_data(Request $request)
    // {
    //   $url = env('API_URL') . "cli-report/fetch_data";
    //   return $url;
    //   $response = Helper::GetApi($url);
    //         echo "<pre>";print_r($response);die;

    //    return json_encode($response);
    //  }
    }




