<?php
namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use DB;
use Illuminate\Support\MessageBag;



class ApiDncController extends Controller
{
    
  function getDNC(Request $request)
  {
    $errors = new MessageBag();
    $url = env('API_URL').'get-client-extension';
    $body = array (
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
    );
  
    $ext = Helper::PostApi($url, $body);
    if($ext->success == 'true')
    {
      $extension_list =  $ext->data;
    }
    else
    {
      $extension_list = array();
    }
    //echo "<pre>";print_r($extension_list);die;

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
    $url = env('API_URL').'dnc';
    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'lower_limit' => $lower_limit,
      'upper_limit' => $upper_limit,
      'search' => $searchTerm,
      
    );
    
    $dnc = Helper::PostApi($url,$body);
    //echo "<pre>";print_r($dnc);die;
    
    try
    {
      $dnc_list = $dnc->data;
      $record_count = $dnc->record_count;
      if (!empty($dnc_list)) {
        return view('donotcall.dnc', compact('dnc_list', 'extension_list','lower_limit', 'page', 'record_count', 'show','searchTerm'))->withErrors($errors);
    } else {
        if (!empty($searchTerm)) {
            // Search term is not found in the table
            return redirect('dnc')->with('error', 'Search term not found');
        } else {
            // Table is empty
            return view('donotcall.dnc',compact('dnc_list','extension_list','lower_limit','page','record_count','show','searchTerm'))->withErrors($errors);
          }
    }
 
    }
  
    catch (BadResponseException $e)
    {
      return back()->with('message',"Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
    }
  
    catch (RequestException $ex)
    {
      return back()->with('message',"Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
    }
  }

  
  function storeDNC(Request $request){
    if(!empty($request->file('dnc_file')))
    {
      
       $file = $request->file('dnc_file');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().'.'.$extension;
           $rootPath = '/var/www/html/api/upload/'; function getDNC(Request $request)
  {
    $url = env('API_URL').'get-client-extension';
    $body = array (
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
    );
  
    $ext = Helper::PostApi($url, $body);
    if($ext->success == 'true')
    {
      $extension_list =  $ext->data;
    }
    else
    {
      $extension_list = array();
    }
  
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
    $url = env('API_URL').'dnc';
    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'lower_limit' => $lower_limit,
      'upper_limit' => $upper_limit,
      'search' => $searchTerm,
      
    );
    
    $dnc = Helper::PostApi($url,$body);
    //echo "<pre>";print_r($dnc);die;
    
    try
    {
      if($dnc->success == 'true')
      {
        $dnc_list = $dnc->data;
        $record_count = $dnc->record_count;
        return view('donotcall.dnc', compact('dnc_list', 'extension_list', 'lower_limit', 'page', 'record_count', 'show'))
        ->with(['searchTerm' => $searchTerm]);
        return view('donotcall.dnc',compact('dnc_list','extension_list','lower_limit','page','record_count','show'));
      }
  
      if($dnc->success == 'false')
      {
        $dnc_list = array();
  
        return view('donotcall.dnc',compact('dnc_list','extension_list','lower_limit','show'));
      }
    }
  
    catch (BadResponseException $e)
    {
      return back()->with('message',"Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
    }
  
    catch (RequestException $ex)
    {
      return back()->with('message',"Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
    }
  }
  
            //$rootPath = 'D:\xampp\htdocs\api\upload\/';
            $file->move($rootPath, $filename);

            $body=array(
              'id' => Session::get('id'),
              'token' => Session::get('tokenId'),
              'file' => $filename,
            );
      // echo "<pre>";print_r($body);die;
             $url = env('API_URL').'upload-dnc';
 //   $dnc_upload = Helper::PostApi($url,$body);
    // return redirect('/editList/6/5');
             //echo "<pre>";print_r($ext_group);die;
      try
      {
        $dnc_upload = Helper::PostApi($url,$body);
                //echo "<pre>";print_r($ext_group);die;
        if($dnc_upload->success == 'true')
        {

          unlink($rootPath.$filename);

        
                  return back()->withSuccess($dnc_upload->message);
              
        }

        if($dnc_upload->success == 'false')
        {
        // return redirect('/');
                  return back()->withSuccess($dnc_upload->message);
        }
      }

       catch (BadResponseException   $e) 
      {
        return back()->with('message',"Error code - (upload-dnc): Oops something went wrong :( Please contact your administrator.)");
      }

      catch (RequestException $ex) 
      {
      return back()->with('message',"Error code - (upload-dnc): Oops something went wrong :( Please contact your administrator.)");
      }
    }

   else if(!empty($request->dnc)){

    $number = str_replace(array('(',')', '_', '-',' '), array(''), $request->number); 
    


    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'number' => $number,
      'extension' => $request->extension,

      'comment'=> $request->comment);

           //echo "<pre>";print_r($body);

     $url = env('API_URL').'edit-dnc';
            // echo "<pre>";print_r($body);die;

    //  $add_dnc = Helper::PostApi($url,$body);
             // echo "<pre>";print_r($add_dnc);die;

     try
     {
      $add_dnc = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
      if($add_dnc->success == 'true'){
              // echo "<pre>";print_r($group);die;
              //return back()->withSuccess($result->message);
        return back()->withSuccess($add_dnc->message);
      }

      if($add_dnc->success == 'false'){
       // return redirect('/');
                return back()->withSuccess($add_dnc->message);
      }
    }


     catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (edit-dnc): Oops something went wrong :( Please contact your administrator.)");
      }

    catch (RequestException $ex) {
       return redirect('/');
    }



  }else{

    $number = str_replace(array('(',')', '_', '-',' '), array(''), $request->number); 
    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'number' => $number,
      'extension' => $request->extension,

      'comment'=> $request->comment);

              // echo "<pre>";print_r($body);die;

    $url = env('API_URL').'add-dnc';
             //echo "<pre>";print_r($body);die;

    try{
      $ext_group = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
      if($ext_group->success == 'true'){
              // echo "<pre>";print_r($group);die;
              //return back()->withSuccess($result->message);
        return back()->withSuccess($ext_group->message);
      }

      if($ext_group->success == 'false'){
         return back()->withMessage($ext_group->message);
                //return back()->withSuccess($ext_group->message);
      }
    }

     catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (add-dnc): Oops something went wrong :( Please contact your administrator.)");
      }


    catch (RequestException $ex) {
    return back()->with('message',"Error code - (add-dnc): Oops something went wrong :( Please contact your administrator.)");
    }
  }

}

function editDnc($number){

  $body=array(
    'id' => Session::get('id'),
    'token' => Session::get('tokenId'),
    'number' => $number,

  );

            // echo "<pre>";print_r($body);die;

  $url = env('API_URL').'dnc';
            // echo "<pre>";print_r($body);die;

    $ext_group = Helper::PostApi($url,$body);
    //echo "<pre>";print_r($ext_group);die;

  try
  {
    $ext_group = Helper::PostApi($url,$body);
    /* echo "<pre>";print_r($ext_group);die;*/
    if($ext_group->success == 'true'){

      $group = $ext_group->data;

      return $group;




    }

    if($ext_group->success == 'false'){
      return redirect('/');
                //return back()->withSuccess($ext_group->message);
    }
  }


  catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
      }

  catch (RequestException $ex) {
    return back()->with('message',"Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
  }


}


function deleteDnc($number){
            $body=array(
              'id' => Session::get('id'),
              'token' => Session::get('tokenId'),
              'number' => $number,
            
            );

            // echo "<pre>";print_r($body);die;

            $url = env('API_URL').'delete-dnc';
             //echo "<pre>";print_r($body);die;

            try
            {
              $delete_dnc = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
              if($delete_dnc->success == 'true'){
              // echo "<pre>";print_r($group);die;
              //return back()->withSuccess($result->message);
                return back()->withSuccess($delete_dnc->message);
              }

              if($delete_dnc->success == 'false'){
                return redirect('/');
                //return back()->withSuccess($ext_group->message);
              }
            }


            catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (delete-dnc): Oops something went wrong :( Please contact your administrator.)");
      }

            catch (RequestException $ex) {
              return back()->with('message',"Error code - (delete-dnc): Oops something went wrong :( Please contact your administrator.)");
            }
          }


          function uploadExcel(){
            $file = $request->file('list_file');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().'.'.$extension;
            $rootPath = '/var/www/html/api/upload/';
            //$rootPath = 'C:\xampp\htdocs\api\upload\/';
            $file->move($rootPath, $filename);

            $body=array(
              'id' => Session::get('id'),
              'token' => Session::get('tokenId'),
              'file' => $filename,
            );

      // echo "<pre>";print_r($body);die;

    $url = env('API_URL').'add-list';
   // $ext_group = Helper::PostApi($url,$body);

    // return redirect('/editList/6/5');
            // echo "<pre>";print_r($ext_group);die;

    try{
      $add_list = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
      if($add_list->success == 'true'){

        $list_id = $add_list->list_id;
        $campaign_id = $add_list->campaign_id;

              return redirect('/editList/'.$list_id.'/'.$campaign_id);
      }

      if($add_list->success == 'false'){
        return redirect('/');
                //return back()->withSuccess($ext_group->message);
      }
    }

    
            catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (add-dnc): Oops something went wrong :( Please contact your administrator.)");
      }

    catch (RequestException $ex) {
     return back()->with('message',"Error code - (add-dnc): Oops something went wrong :( Please contact your administrator.)");
    }
          }
         
}
          


