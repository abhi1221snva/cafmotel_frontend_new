<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\MessageBag;

class ShowHistoryController extends Controller
{
   
    public function showHistory(Request $request)
    {
      $show_history = [];
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
      $start_date = $request->input('start_date');
      $end_date = $request->input('end_date');
      $url_title = $request->input('url_title');

     
  
      $url = env('API_URL') . "show-upload-history";
      $body=array(
        'id' => Session::get('id'),
        'token' => Session::get('tokenId'),
        'lower_limit' => $lower_limit,
        'upper_limit' => $upper_limit,
        'start_date'=>$start_date,
        'end_date'=>$end_date,
        'url_title'=>$url_title
        
      );
      // echo "<pre>";print_r($body);die;
      $response = Helper::PostApi($url,$body);
      //echo "<pre>";print_r($response);die;
      try
      {
          $show_history = $response->data;
          $record_count = $response->record_count;
          $uniqueUrlTitles = $response->unique_url_titles;
          if (!empty($show_history)) {
            return view('history.list', compact('show_history', 'lower_limit', 'page', 'record_count', 'show','start_date','end_date','url_title','uniqueUrlTitles'))->withErrors($errors);
        } else {
            if (!empty($searchTerm)) {
                // Search term is not found in the table
                return redirect('show-upload-history')->with('error', 'Search term not found');
            } else {
                // Table is empty
                return view('history.list', compact('show_history', 'lower_limit', 'page', 'record_count', 'show','start_date','end_date','url_title','uniqueUrlTitles'))->withErrors($errors);
            }
        }
     
      }
  
      catch(RequestException $ex)
      {
        $errors->add("error", $ex->getMessage());
        return view("history.list", compact("errors", $errors));
      }
    }
}
