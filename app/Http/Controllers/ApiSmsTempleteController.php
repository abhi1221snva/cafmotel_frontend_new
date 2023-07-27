<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use App\User;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;

use App\Http\Controllers\InheritApiController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class ApiSmsTempleteController extends Controller
{
  function getSmsTemplete()
  {

    $title ="Extensions List | ".env('APP_NAME');
    $inherit_list = new InheritApiController;
    $templete_list =  $inherit_list->getSmsTemplete();
		// echo Session::get('tokenId'); exit;
    if(!is_array($templete_list))
    {
      $templete_list =array();
    }
    if(empty($templete_list))
    {
      if(empty(Session::get('tokenId')))
      {
        return redirect('/');
      }
    }

   // echo "<pre>";print_r($extension_list);die;

    return view('sms-templete.sms-templete',compact('templete_list','title'));
  }

function storeSmsTemplete(Request $request){

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
            return view("sms-templete.add-sms-templete", compact("errors", $errors));
        }



    $inherit_list = new InheritApiController;
    $label_list =  $inherit_list->getLabel();
    // echo Session::get('tokenId'); exit;
    if(!is_array($label_list))
    {
      $label_list =array();
    }
    if(empty($label_list))
    {
      if(empty(Session::get('tokenId')))
      {
        return redirect('/');
      }
    }

    $users = new User();
    $user_column = $users->getTableColumns();
/*echo "<pre>";print_r($user_column);
die;*/


    if(!empty($request->templete_id)){

      $body=array(
        'id' => Session::get('id'),
        'token' => Session::get('tokenId'),
        'templete_name' => $request->templete_name,
        'templete_desc' => $request->templete_desc,
        'templete_id' => $request->templete_id

      );

              //echo "<pre>";print_r($body);die;

    $url = env('API_URL').'edit-sms-templete';
             //echo "<pre>";print_r($body);die;

    try{
      $edit_templete = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
      if($edit_templete->success == 'true'){
              // echo "<pre>";print_r($group);die;
              //return back()->withSuccess($result->message);
        return back()->withSuccess($edit_templete->message);
      }

      if($edit_templete->success == 'false'){
       
                return back()->withSuccess($edit_templete->message);
      }
    }

     catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (edit-sms-templete): Oops something went wrong :( Please contact your administrator.)");
      }


    catch (RequestException $ex) {
    return back()->with('message',"Error code - (add-sms-templete): Oops something went wrong :( Please contact your administrator.)");
    }


    }

    else if(!empty($request->templete_name)){

      $body=array(
        'id' => Session::get('id'),
        'token' => Session::get('tokenId'),
        'templete_name' => $request->templete_name,
        'templete_desc' => $request->templete_desc
      );

            //  echo "<pre>";print_r($body);die;

    $url = env('API_URL').'add-sms-templete';
             //echo "<pre>";print_r($body);die;

    try{
      $add_templete = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
      if($add_templete->success == 'true'){
              // echo "<pre>";print_r($group);die;
              //return back()->withSuccess($result->message);
        return back()->withSuccess($add_templete->message);
      }

      if($add_templete->success == 'false'){
       
                return back()->withSuccess($add_templete->message);
      }
    }

     catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (add-ivr): Oops something went wrong :( Please contact your administrator.)");
      }


    catch (RequestException $ex) {
    return back()->with('message',"Error code - (add-dnc): Oops something went wrong :( Please contact your administrator.)");
    }


    }

  //  echo "<pre>";print_r($label_list);die;
    return view('sms-templete.add-sms-templete',compact('label_list','user_column', 'custom_field_labels'));
    //,compact('templete_list','title')

}


function editSmsTemplete($templete_id){

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
            return view("sms-templete.add-sms-templete", compact("errors", $errors));
        }

   $users = new User();
    $user_column = $users->getTableColumns();

     $inherit_list = new InheritApiController;
    $label_list =  $inherit_list->getLabel();
    // echo Session::get('tokenId'); exit;
    if(!is_array($label_list))
    {
      $label_list =array();
    }
    if(empty($label_list))
    {
      if(empty(Session::get('tokenId')))
      {
        return redirect('/');
      }
    }


  $body=array(
    'id' => Session::get('id'),
    'token' => Session::get('tokenId'),
    'templete_id' => $templete_id,

  );

             

  $url = env('API_URL').'sms-templete';
       

  try
  {
    $templete_data = Helper::PostApi($url,$body);
   /// echo "<pre>";print_r($templete_data);die;
    if($templete_data->success == 'true'){

      $sms_templete = $templete_data->data;

    return view('sms-templete.edit-sms-templete',compact('sms_templete','label_list','user_column', 'custom_field_labels'));
      




    }

    if($ivr_data->success == 'false'){
      return redirect('/');
                //return back()->withSuccess($ext_group->message);
    }
  }


  catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (ivr): Oops something went wrong :( Please contact your administrator.)");
      }

  catch (RequestException $ex) {
    return back()->with('message',"Error code - (ivr): Oops something went wrong :( Please contact your administrator.)");
  }


}


function deleteSmsTemplete($temp_id,$status){


  if($status == '0'){
    $status = 1;

  }

  else if($status == '1'){
    $status = 0;
  }
    $body=array(
      'id'          => Session::get('id'),
      'token'       => Session::get('tokenId'),
      'templete_id'     => $temp_id,
      'is_deleted'  => $status
    );

    //echo "<pre>";print_r($body);die;
    $url = env('API_URL').'delete-sms-templete';

    /* $delete_sms_temp = Helper::PostApi($url,$body);

   echo "<pre>";print_r($delete_sms_temp);die;*/



    
    try
    {
      $delete_sms_temp = Helper::PostApi($url,$body);
      if($delete_sms_temp->success == 'true')
      {
         return back()->withSuccess($delete_sms_temp->message);
        
      }
      if($delete_sms_temp->success == 'false')
      {
    

         return back()->withSuccess($delete_sms_temp->message);
       
        //return back()->withSuccess($ext_group->message);
      }
    }
    catch (BadResponseException   $e) {
      return back()->with('message',"Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
    }
    catch (RequestException $ex){
      return back()->with('message',"Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
    }
  }


  public function delete(int $id)
    {
        try {
            $url = env('API_URL') . "sms-template/$id";
            $response = Helper::RequestApi($url, "DELETE");
            return response()->json($response);
        } catch (\Throwable $ex) {
            return response()->json([
                "success" => false,
                "message" => $ex->getMessage()
            ]);
        }
    }


}


