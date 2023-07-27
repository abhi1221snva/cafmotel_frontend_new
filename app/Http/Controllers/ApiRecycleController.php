<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;


class ApiRecycleController extends Controller
{
  function getRecycleList()
  {


    $inherit_list = new InheritApiController;
    $campaign_list =  $inherit_list->getCampaign();
    
    if(!is_array($campaign_list)){
      $campaign_list =array();
    }


    $disposition_list =  $inherit_list->getDisposition();
    if(!is_array($disposition_list)){
      $disposition_list =array();
    }

     $list_details =  $inherit_list->getListList();
    if(!is_array($list_details)){
      $list_details =array();
    }


    //echo "<pre>";print_r($list_details);die;

    $recycle_list =  $inherit_list->getRecycleRule();
    if(!is_array($recycle_list)){
      $recycle_list =array();
    }


    
    if(empty($recycle_list)){
        if(empty(Session::get('tokenId'))){
                    return redirect('/');

            }

    }


    //echo "<pre>";print_r($recycle_list);die;


    return view('lists.recycle-rule',compact('campaign_list','disposition_list','recycle_list','list_details'));
  }

  function storeRecycle(Request $request){

      if(empty(Session::get('tokenId'))){
                    return redirect('/');

            }


    $inherit_list = new InheritApiController;
    $campaign_list =  $inherit_list->getCampaign();

    if(!is_array($campaign_list)){
      $campaign_list =array();
    }

     $list_details =  $inherit_list->getListList();
    if(!is_array($list_details)){
      $list_details =array();
    }


    $disposition_list =  $inherit_list->getDisposition();
    if(!is_array($disposition_list)){
      $disposition_list =array();
    }

    if ($request->isMethod('post'))
    {
      $body=array(
        'id' => Session::get('id'),
        'token' => Session::get('tokenId'),
        'campaign_id' => $request->campaign_id,
        'list_id' =>  $request->list_id,
        'disposition' => $request->disposition_id,
        'time' => $request->time,
        'day'=> $request->days,
        'call_time' => $request->call_time,

      );
      
      //echo "<pre>";print_r($body);die;

      $url = env('API_URL').'add-recycle-rule';
    // echo "<pre>";print_r($body);die;



    try
    {
      $add_recycle = Helper::PostApi($url,$body);
      //echo "<pre>";print_r($recycle_list);die;
      if($add_recycle->success == 'true')
      {
        return redirect('/recycle-rule')->withSuccess($add_recycle->message);
        
      }
      if($add_recycle->success == 'false')
      {
        return redirect('/');
        //return back()->withSuccess($ext_group->message);
      }
    }

     catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (add-recycle-rule): Oops something went wrong :( Please contact your administrator.)");
      }

    catch (RequestException $ex)
    {
      return back()->with('message',"Error code - (add-recycle-rule): Oops something went wrong :( Please contact your administrator.)");

    }
     // dd($request->all());die;

    }



    

    

    return view('lists.add-recycle',compact('campaign_list','disposition_list','list_details'));
  }


  function editRecycleRule($recycle_id="",Request $request)
  {


    if ($request->isMethod('post'))
    {

      $time_explode = explode(":",$request->time);

      $time = $time_explode[0].':'.$time_explode[1];

      $body=array(
        'id' => Session::get('id'),
        'token' => Session::get('tokenId'),
        'recycle_rule_id' => $recycle_id,
        'campaign_id' => $request->campaign_id,
        'list_id' =>  $request->list_id,
        'disposition_id' => $request->disposition_id,
        'time' => $time,
        'day'=> $request->days,
        'call_time' => $request->call_time,

      );
      
     

      $url = env('API_URL').'edit-recycle-rule';
    // echo "<pre>";print_r($body);die;

     // $add_recycle = Helper::PostApi($url,$body);
    //echo "<pre>";print_r($add_recycle);die;




    try
    {
      $add_recycle = Helper::PostApi($url,$body);
      //echo "<pre>";print_r($add_recycle);die;
      if($add_recycle->success == 'true')
      {
      //  return redirect('/recycle-rule')->withSuccess($add_recycle->message);

        return back()->withSuccess($add_recycle->message);

        
      }
      if($add_recycle->success == 'false')
      {
        return redirect('/');
        //return back()->withSuccess($ext_group->message);
      }
    }

      catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (edit-recycle-rule): Oops something went wrong :( Please contact your administrator.)");
      }

    catch (RequestException $ex)
    {
      return back()->with('message',"Error code - (edit-recycle-rule): Oops something went wrong :( Please contact your administrator.)");
    }
    }

  else{
    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'recycle_rule_id' => $recycle_id,
    );


    $url = env('API_URL').'recycle-rule';
    // echo "<pre>";print_r($body);die;

    try
    {
      $recycle_list = Helper::PostApi($url,$body);
      //echo "<pre>";print_r($recycle_list);die;
      if($recycle_list->success == 'true')
      {
        $recycle = $recycle_list->data;
        
      }
      if($recycle_list->success == 'false')
      {
        return redirect('/');
        //return back()->withSuccess($ext_group->message);
      }
    }

       catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (recycle-rule): Oops something went wrong :( Please contact your administrator.)");
      }

    catch (RequestException $ex)
    {
       return back()->with('message',"Error code - (recycle-rule): Oops something went wrong :( Please contact your administrator.)");
    }

    $inherit_list = new InheritApiController;
    $campaign_list =  $inherit_list->getCampaign();
    if(!is_array($campaign_list))
    {
      $campaign_list =array();
    }

    $disposition_list =  $inherit_list->getDisposition();
    if(!is_array($disposition_list))
    {
      $disposition_list =array();
    }

      $list_details =  $inherit_list->getListList();
    if(!is_array($list_details)){
      $list_details =array();
    }

    return view('lists.edit-recycle',compact('campaign_list','disposition_list','recycle','list_details'));


  }


   
    }

    function deleteRecycleRule($recycle_id=""){

      $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'recycle_rule_id' => $recycle_id,
      'is_deleted' => 1,
    );


    $url = env('API_URL').'edit-recycle-rule';
     //echo "<pre>";print_r($body);die;

    try
    {
      $recycle_list = Helper::PostApi($url,$body);
      //echo "<pre>";print_r($recycle_list);die;
      if($recycle_list->success == 'true')
      {
         return back()->withSuccess($recycle_list->message);
        
      }
      if($recycle_list->success == 'false')
      {
        return redirect('/');
        //return back()->withSuccess($ext_group->message);
      }
    }

     catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (edit-recycle-rule): Oops something went wrong :( Please contact your administrator.)");
      }

    catch (RequestException $ex)
    {
       return back()->with('message',"Error code - (edit-recycle-rule): Oops something went wrong :( Please contact your administrator.)");
    }

    }

    function findRecycleListDelete($list_id,$disposition_id){
    	//echo $list_id;
    	//echo $disposition_id;die;

    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'list_id' => $list_id,
      'disposition_id' => $disposition_id,
    );


    $url = env('API_URL').'delete-leads-rule';
    /*$recycle_list = Helper::PostApi($url,$body);
     echo "<pre>";print_r($recycle_list);die;*/

    try
    {
      $recycle_list = Helper::PostApi($url,$body);
      //echo "<pre>";print_r($recycle_list);die;
      if($recycle_list->success == 'true')
      {
         $count = $recycle_list->data;
         // $inherit_list = new InheritApiController;
         // $sendmail =  $inherit_list->sendMail($count);
         return back()->withSuccess($recycle_list->message);
        
      }
      if($recycle_list->success == 'false')
      {
        return back()->withSuccess($recycle_list->message);
        //return back()->withSuccess($ext_group->message);
      }
    }

     catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (edit-recycle-rule): Oops something went wrong :( Please contact your administrator.)");
      }

    catch (RequestException $ex)
    {
       return back()->with('message',"Error code - (edit-recycle-rule): Oops something went wrong :( Please contact your administrator.)");
    }
    }

    function searchRecycleRule(Request $request){



    $inherit_list = new InheritApiController;
    $campaign_list =  $inherit_list->getCampaign();
    
    if(!is_array($campaign_list)){
      $campaign_list =array();
    }


    $disposition_list =  $inherit_list->getDisposition();
    if(!is_array($disposition_list)){
      $disposition_list =array();
    }

     $list_details =  $inherit_list->getListList();
    if(!is_array($list_details)){
      $list_details =array();
    }


  


    //echo "<pre>";print_r($recycle_list);die;
    	$body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'campaign_id' => $request->campaign_id,
      'list_id' => $request->list_id,
      'disposition_id' => $request->disposition_id,
      'call_time' => $request->call_time,
      'day' =>$request->days,
      
    );

    // echo "<pre>";print_r($body);die;



    $url = env('API_URL').'search-recycle-rule';
	  /*  $recycle_list_search = Helper::PostApi($url,$body);
	     echo "<pre>";print_r($recycle_list_search);die;*/

    try
    {
      $recycle_list_search = Helper::PostApi($url,$body);
      //echo "<pre>";print_r($recycle_list);die;
      if($recycle_list_search->success == 'true')
      {
      	$recycle_list = $recycle_list_search->data;
    return view('lists.recycle-rule',compact('campaign_list','disposition_list','recycle_list','list_details'));
         
        
      }
      if($recycle_list_search->success == 'false')
      {
        //return redirect('/');
        return back()->withSuccess($recycle_list_search->message);
      }
    }

     catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (edit-recycle-rule): Oops something went wrong :( Please contact your administrator.)");
      }

    catch (RequestException $ex)
    {
       return back()->with('message',"Error code - (edit-recycle-rule): Oops something went wrong :( Please contact your administrator.)");
    }

    }
  }

