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

class VoiceTempleteController extends Controller
{
  function getVoiceTemplete()
  {

    $title ="Extensions List | ".env('APP_NAME');
    $templete_list = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "voice-templete";
        try
        {
            $response = Helper::GetApi($url);

           // echo "<pre>";print_r($response);die;
            if($response->success)
            {
                $templete_list = $response->data;
            }
            else
            {
                $templete_list = [];
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }
        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("voice-templete.list", compact("errors", $errors));
        }

    return view('voice-template.list',compact('templete_list','title'));
  }

  public function getGoogleLanugages() {
        $resArr = [];
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId')
        );
        $url = env('API_URL') . 'get-google-languages';
        $result =  Helper::PostApi($url, $body);

        if($result->success == 1) {
            $resArr = $result->data;
        }
        return $resArr;
    }

function storeVoiceTemplete(Request $request){


  $arrLang = [];
        $arrGoogleLang = $this->getGoogleLanugages();

        foreach($arrGoogleLang as $lang) {
            $temp = [];
            $temp['id'] = $lang->id;
            $temp['language'] = $lang->language;
            $temp['voice_name'] = $lang->voice_name;
            $temp['ssml_gender'] = $lang->ssml_gender;
            $arrLang[base64_encode($lang->language)][] = $temp;
        }

        //echo "<pre>";print_r($arrLang);die;

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
            return view("voice-template.add", compact("errors", $errors));
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
        'templete_id' => $request->templete_id,
         'language' => $request->language,
        'voice_name' => $request->voice_name,
        'speed' => $request->speed_value,
        'pitch' => $request->pitch_value


      );

              //echo "<pre>";print_r($body);die;

    $url = env('API_URL').'edit-voice-templete';
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
        return back()->with('message',"Error code - (edit-voice-templete): Oops something went wrong :( Please contact your administrator.)");
      }


    catch (RequestException $ex) {
    return back()->with('message',"Error code - (add-voice-templete): Oops something went wrong :( Please contact your administrator.)");
    }


    }

    else if(!empty($request->templete_name)){

      $body=array(
        'id' => Session::get('id'),
        'token' => Session::get('tokenId'),
        'templete_name' => $request->templete_name,
        'templete_desc' => $request->templete_desc,
        'language' => $request->language,
        'voice_name' => $request->voice_name,
        'speed' => $request->speed_value,
        'pitch' => $request->pitch_value

      );

             // echo "<pre>";print_r($body);die;

    $url = env('API_URL').'add-voice-templete';
             //echo "<pre>";print_r($body);die;

    try{
      $add_templete = Helper::PostApi($url,$body);
            //  echo "<pre>";print_r($add_templete);die;
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
    return view('voice-template.add',compact('label_list','user_column', 'custom_field_labels','arrLang'));
    //,compact('templete_list','title')

}


function editVoiceTemplete($templete_id){

   $arrLang = [];
        $arrGoogleLang = $this->getGoogleLanugages();

        foreach($arrGoogleLang as $lang) {
            $temp = [];
            $temp['id'] = $lang->id;
            $temp['language'] = $lang->language;
            $temp['voice_name'] = $lang->voice_name;
            $temp['ssml_gender'] = $lang->ssml_gender;
            $arrLang[base64_encode($lang->language)][] = $temp;
        }


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
            return view("voice-template.edit", compact("errors", $errors));
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

             

  $url = env('API_URL').'voice-templete';
       

  try
  {
    $templete_data = Helper::PostApi($url,$body);
   // echo "<pre>";print_r($templete_data);die;
    if($templete_data->success == 'true'){

      $sms_templete = $templete_data->data;

    return view('voice-template.edit',compact('sms_templete','label_list','user_column', 'custom_field_labels','arrLang'));
      




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


function deleteVoiceTemplate($temp_id,$status){


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
    $url = env('API_URL').'delete-voice-templete';

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
            $url = env('API_URL') . "voice-template/$id";
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


