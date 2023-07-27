<?php
namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;


class ApiDispositionController extends Controller
{

  function getDisposition(){
    $inherit_disposition = new InheritApiController;
    $disposition_list =  $inherit_disposition->getDisposition();
    if(!is_array($disposition_list)){
      $disposition_list =array();
    }
    if(empty($disposition_list)){
      if(empty(Session::get('tokenId'))){
        return redirect('/');
      }
    }
    return view('campaign.disposition',compact('disposition_list'));
  }

  function addDisposition($title,$d_type,$enable_sms)
    {
        $body = array(
          'id' => Session::get('id'),
        'token' => Session::get('tokenId'),
            'title' => $title,
            'd_type' => $d_type,
            'enable_sms'=>$enable_sms,
        );
        //echo "<pre>";print_r($body);die;
        $url = env('API_URL').'add-disposition';
        $response = Helper::PostApi($url,$body);
        //echo "<pre>";print_r($response);die;

        if ($response->success)
        {
          $inherit_disposition = new InheritApiController;
          $disposition_list =  $inherit_disposition->getDisposition();
          if(!is_array($disposition_list)){
          $disposition_list =array();
        }
      }
        return $disposition_list;
    }
  
  function storeDisposition(Request $request){
    if(!empty($request->id)){
      $body=array(
        'id' => Session::get('id'),
        'token' => Session::get('tokenId'),
        'disposition_id' => $request->id,
        'title' => $request->title,
        'd_type' => $request->d_type,
        'enable_sms' => $request->enable_sms,

      );
      $url = env('API_URL').'edit-disposition';
      try{
        $ext_group = Helper::PostApi($url,$body);
        //echo "<pre>";print_r($ext_group);die;
        //echo '<pre>'; print_r($body); echo $url; exit;
        if($ext_group->success == 'true'){
          return back()->withSuccess($ext_group->message);
        }
        if($ext_group->success == 'false'){
          return redirect('/');
        }
      }
      catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (edit-disposition): Oops something went wrong :( Please contact your administrator.)");
      }
      catch (RequestException $ex) {
        return back()->with('message',"Error code - (edit-disposition): Oops something went wrong :( Please contact your administrator.)");
     }
    }else{
      $body=array(
        'id' => Session::get('id'),
        'token' => Session::get('tokenId'),
        'title' => $request->title,
        'd_type' => $request->d_type,
        'enable_sms' => $request->enable_sms,
      );
      $url = env('API_URL').'add-disposition';
      try{
        $add_disposition = Helper::PostApi($url,$body);
        if($add_disposition->success == 'true'){
          return back()->withSuccess($add_disposition->message);
        }
        if($add_disposition->success == 'false'){
          return redirect('/');
        }
      }
      catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (add-disposition): Oops something went wrong :( Please contact your administrator.)");
      }
      catch (RequestException $ex) {
        return back()->with('message',"Error code - (add-disposition): Oops something went wrong :( Please contact your administrator.)");
      }
    }
  }
  
  function editDisposition($disposition_id){
    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'disposition_id' => $disposition_id,
    );
    $url = env('API_URL').'disposition';
    try
    {
      $edit_disposition = Helper::PostApi($url,$body);
      if($edit_disposition->success == 'true'){
        $disposition = $edit_disposition->data;
        return $disposition;
      }
      if($edit_disposition->success == 'false'){
        return redirect('/');
      }
    }
    catch (BadResponseException   $e) {
      return back()->with('message',"Error code - (disposition): Oops something went wrong :( Please contact your administrator.)");
    }
    catch (RequestException $ex) {
      return back()->with('message',"Error code - (disposition): Oops something went wrong :( Please contact your administrator.)");
    }
  }
  function deleteDisposition($disposition_id)
  {
    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'disposition_id' => $disposition_id,
      'is_deleted'=> 1
    );

    $url = env('API_URL').'edit-disposition';
    try
    {
      $ext_group = Helper::PostApi($url,$body);
      if($ext_group->success == 'true')
      {
        return back()->withSuccess($ext_group->message);
      }

      if($ext_group->success == 'false')
      {
        return redirect('/');
      }
    }

    catch (BadResponseException   $e)
    {
      return back()->with('message',"Error code - (edit-disposition): Oops something went wrong :( Please contact your administrator.)");
    }

    catch (RequestException $ex)
    {
      return back()->with('message',"Error code - (edit-disposition): Oops something went wrong :( Please contact your administrator.)");
    }
  }
}