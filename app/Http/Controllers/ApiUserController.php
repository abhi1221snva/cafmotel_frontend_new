<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use App\User;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use  Validator;


use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackMail;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use File;




class ApiUserController extends Controller
{
    function changePassword(Request $request){
        $validator =  Validator::make($request->all(),[
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()){
            return back()
            ->withErrors($validator)
            ->withInput();
        }

        if(isset($request->user_id)){
            $url = env('API_URL').'update-user-password';
            $body=array(
                'id' => $request->user_id,
                'password' => $request->old_password,
                'new_password' => $request->password,
                'token' => Session::get('tokenId')
            );

           // echo "<pre>";print_r($body);die;
           /* $result = Helper::PostApi($url,$body);
            echo "<pre>";print_r($result);die;
*/

            try{
                $result = Helper::PostApi($url,$body);
                if($result->success == 'true'){
                    return back()->withSuccess($result->message);
                }

                 if($result->success == 'false'){
                    return back()->withSuccess($result->message);
                }
            }

            catch (BadResponseException   $e) {
                return back()->with('message',"Error code - (update-user-password): Oops something went wrong :( Please contact your administrator.)");
            }
            catch (RequestException $ex) {
                $message = "Page Not Found";
                return back()->withSuccess($message);
            }  
         }
    }

        function updateProfile(Request $request){


//echo Session::get('tokenId');die;
            if(isset($request->user_id)){
            $url = env('API_URL').'update-profile';
            $body=array(
                'id' => $request->user_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'timezone' => $request->timezone,
                'company_name' => $request->company_name,
                'phone_number' => preg_replace('/\D/', '', $request->phone),
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'token' => Session::get('tokenId'),
                'parentId' => Session::get('parentId')
            );



             //echo "<pre>";print_r($body);die;




            try{
                $result = Helper::PostApi($url,$body);

                //echo "<pre>";print_r($result);die;
                if($result->success == 'true'){
                    return back()->withSuccess($result->message);
                }

                 else if($result->success == 'false'){
                    return back()->withSuccess($result->message);
                }
            }

            catch (BadResponseException   $e) {
                return back()->with('message',"Error code - (update-profile): Oops something went wrong :( Please contact your administrator.)");
            }
            catch (RequestException $ex) {
                $message = "Page Not Found";
                return back()->withSuccess($message);
            }  
         }
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

    function userProfile() {
        $voicemail = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "view-voicemail";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $voicemail = $response->data;
            } else {
                $clients = [];
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("users.profile", compact("errors", $errors));
        }

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
        return view('users.profile', compact('arrLang','voicemail'));
    }


         function editVoiceMail($id = 0) {
        $ivr_data = [];
        if($id > 0)
        {
           $body=array(
              'id' => Session::get('id'),
              'token' => Session::get('tokenId'),
              'voicemail_id' => $id,
              'parentId' => Session::get('parentId'),

            
            );

            $url = env('API_URL').'edit-voicemail';
            $ivr_data = Helper::PostApi($url, $body);
           // echo "<pre>";print_r($ivr_data);die;

            if (empty($ivr_data->data[0])) {
                if (empty(Session::get('tokenId'))) {
                    return redirect('/');
                }
            }
            $ivr_data = $ivr_data->data[0];
        }


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

        return view('users.edit-voicemail', compact('ivr_data', 'arrLang'));
    }


    function updateVoiceMail()
    {
        echo "s";die;
    }


     function editVoiceMail1($voicemail_id){

            $body=array(
              'id' => Session::get('id'),
              'token' => Session::get('tokenId'),
              'voicemail_id' => $voicemail_id,
              'parentId' => Session::get('parentId'),

            
            );

            //\ echo "<pre>";print_r($body);die;

            $url = env('API_URL').'edit-voicemail';
              //$delete_voicemail = Helper::PostApi($url,$body);
            
            // echo "<pre>";print_r($delete_voicemail);die;

            try
            {
              $delete_conferencing = Helper::PostApi($url,$body);
              echo "<pre>";print_r($delete_conferencing);die;
              if($delete_conferencing->success == 'true'){

                $group = $delete_conferencing->data;

      return $group;
              // echo "<pre>";print_r($group);die;
              //return back()->withSuccess($result->message);
                return back()->withSuccess($delete_conferencing->message);
              }

              if($delete_conferencing->success == 'false'){
                 return back()->withSuccess($delete_conferencing->message);
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


    function deleteVoiceMail($auto_id,$voicemail_id){
            $body=array(
              'id' => Session::get('id'),
              'token' => Session::get('tokenId'),
              'auto_id' => $auto_id,
              'voicemail_id' => $voicemail_id,
              'parentId' => Session::get('parentId'),

            
            );

            // echo "<pre>";print_r($body);die;

            $url = env('API_URL').'delete-voicemail';
              $delete_voicemail = Helper::PostApi($url,$body);
            
            // echo "<pre>";print_r($delete_voicemail);die;

            try
            {
              $delete_conferencing = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
              if($delete_conferencing->success == 'true'){
              // echo "<pre>";print_r($group);die;
              //return back()->withSuccess($result->message);
                return back()->withSuccess($delete_conferencing->message);
              }

              if($delete_conferencing->success == 'false'){
                 return back()->withSuccess($delete_conferencing->message);
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

    function voiceMail(Request $request)
    {
        $rules = array('ivr_audio_option' => 'required');
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if($request->ivr_audio_option == 'text_to_speech')
        {
            $rules = array('language' => 'required');
            $rules = array('voice_name' => 'required');
            $rules = array('speech_text' => 'required');
        } elseif(!($request->ivr_audio_option == 'audio_record')) {
            $rules = array('ann_id' => 'required|mimes:wav,mp3');
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $rootPath = env('FILE_UPLOAD_PATH');
        $ivrFolder = env('IVR_FILE_UPLOAD_FOLDER_NAME');
        if (!$rootPath || !$ivrFolder) {
            return redirect()->back()->withErrors("File upload path not set");
        }
        $rootPath .= $ivrFolder;

        //get asteriskServers
        $arrAstriskServers = [];
        $intPromptOption = 0;
        $url = env('API_URL') . "servers/asterisk-server";
        try {
            $response = Helper::GetApi($url, [], true);
                if ($response["success"]) {
                    $arrAstriskServers = $response["data"];
                } else {
                    Log::error("No asteriskServers found", ["file" => "ApiIvrController", "line"=> "129"]);
                }
            } catch (\Throwable $e) {
            Log::error("Failed to get asteriskServers", [
                "message" => $e->getMessage(),
                "line" => $e->getLine(),
                "file" => $e->getFile(),
                "code" => $e->getCode(),
            ]);
        }


        //update entry
        if (!empty($request->old_ann_id)) {
            $old_ann_id = $request->old_ann_id . '.wav';
            if (file_exists($rootPath . '/' . $old_ann_id)) {
                unlink($rootPath . '/' . $old_ann_id);
            }

            if($request->ivr_audio_option == 'text_to_speech')
            {
                $filename = Session::get('id')."_output.mp3";
                $filenameDb = Session::get('extension');
                $extension = 'mp3';

                $intPromptOption = 1;
            } elseif($request->ivr_audio_option == 'audio_record'){
                $filename = Session::get('id')."_recorded.wav";
                $filenameDb = Session::get('extension');
                $extension = 'wav';

                $intPromptOption = 2;
            } else {
                $file = $request->file('ann_id');
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $filenameDb = Session::get('extension');
                $filename = $filenameDb . '.' . $extension;
                $file->move($rootPath, $filename);

                $intPromptOption = 0;
            }

            $filenameDb = self::setAudioFileFormat($rootPath, $filename, $filenameDb, $extension, $arrAstriskServers);
            
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'auto_id' => $request->id,
                'ivr_id' => $filenameDb,
                'ivr_desc' => $request->ivr_desc,
                'ann_id' => $filenameDb,
                'language' => base64_decode($request->language),
                'voice_name' => $request->voice_name,
                'ivr_audio_option' => $request->ivr_audio_option,
                'speech_text' => $request->speech_text,
                'prompt_option' => $intPromptOption,
                );

           // echo "<pre>";print_r($body);die;


            $url = env('API_URL') . 'update-voiemail';
            //$add_dnc = Helper::PostApi($url, $body);



            try {
                $edit_ivr = Helper::PostApi($url, $body);
         //  echo "<pre>";print_r($edit_ivr);die;
                if ($edit_ivr->success == 'true') {
                    return back()->withSuccess($edit_ivr->message);
                }

                if ($edit_ivr->success == 'false') {
                    return back()->withSuccess($edit_ivr->message);
                }
            } catch (BadResponseException $e) {
                return back()->with('message', "Error code - (edit_ivr): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                return redirect('/');
            }
        }
        else
        {

        //add new entry
        if($request->ivr_audio_option == 'text_to_speech')
        {
            $filename = Session::get('id')."_output.mp3";
            $filenameDb = Session::get('extension');
            $extension = 'mp3';

            $intPromptOption = 1;
        }
        elseif($request->ivr_audio_option == 'audio_record') {
            $filename = Session::get('id')."_recorded.wav";
            $filenameDb = Session::get('extension');

            $extension = 'wav';
            $intPromptOption = 2;
        }

        else {
            $file = $request->file('ann_id');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filenameDb = Session::get('extension');

            $filename = $filenameDb . '.' . $extension;
            $file->move($rootPath, $filename);
            $intPromptOption = 0;
        }

        $filenameDb = self::setAudioFileFormat($rootPath, $filename, $filenameDb, $extension, $arrAstriskServers);

            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'ivr_id' => $filenameDb,
                'ivr_desc' => $request->ivr_desc,
                'ann_id' => $filenameDb,
                'language' => base64_decode($request->language),
                'voice_name' => $request->voice_name,
                'ivr_audio_option' => $request->ivr_audio_option,
                'speech_text' => $request->speech_text,
                'prompt_option' => $intPromptOption,
                );

               // echo "<pre>";print_r($body);die;

        $url = env('API_URL') . 'add-voice-mail-drop';
            $add_ivr = Helper::PostApi($url, $body);



        $body=array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'vm_drop_location'=> $filenameDb,
            'auto_id' => Session::get('id'));

            //echo "<pre>";print_r($body);die;

        $url = env('API_URL').'update-voice-mail';
        try {
            $add_ivr = Helper::PostApi($url,$body);
            //echo "<pre>";print_r($ext_group);die;
            if($add_ivr->success == 'true') {
                return back()->withSuccess($add_ivr->message);
            }

            if($add_ivr->success == 'false') {
                return redirect('/');
            }
        }

        catch (BadResponseException   $e) {
            return back()->with('message',"Error code - (add-ivr): Oops something went wrong :( Please contact your administrator.)");
        }

        catch (RequestException $ex) {
            return back()->with('message',"Error code - (add-dnc): Oops something went wrong :( Please contact your administrator.)");
        }

    }
}


    public static function setAudioFileFormat($rootPath, $tmpFilename, $filenameDb, $extension, $arrAstriskServers = [], $strModuleDirectory = null) {
        $rootPath = $rootPath . "/";
        if($strModuleDirectory == null) $strModuleDirectory = "vm-drops";

        switch ($extension) {
            case "wav":
                $convertedFilename = $rootPath . $filenameDb . ".wav";
                $tmpFilename2 = $tmpFilename."_tmp";
                shell_exec("cp $rootPath$tmpFilename $rootPath$tmpFilename2 ");
                shell_exec("sox $rootPath$tmpFilename2 -r 8000 -c 1 $convertedFilename -q");
                shell_exec("unlink $rootPath$tmpFilename2");
                break;
            case "mp3":
                $convertedFilename = $rootPath . $filenameDb . ".wav";
                shell_exec("sox $rootPath$tmpFilename -r 8000 -c 1 $convertedFilename -q");
                if(file_exists($convertedFilename))
                {
                    unlink($rootPath . $tmpFilename);
                }
                break;
        }

        // As of now we are keeping all files on Astrisk Servers. (Ex: root@sip1.voiptella.com:/var/spool/asterisk/audio/ivr-recordings/)
        if(!empty($arrAstriskServers)) {
            foreach ($arrAstriskServers as $arrAsteriskServer) {
                $strAsteriskPath = "root@" . $arrAsteriskServer['domain'] . ":" . env('ASTERISK_UPLOAD_PATH') . "audio/" . $strModuleDirectory . "/";
                shell_exec("scp -P 10347 $convertedFilename $strAsteriskPath");
            }
        }

        return $filenameDb;
    }
          function voiceMail1(Request $request){

            //echo Session::get('id');die;
            $file = $request->file('voice_mail');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filenameDb = Session::get('extension');
            $filename = $filenameDb.'.'.$extension;

            $rootPath = '/var/www/html/api/upload\vm_drop\/';
            //$rootPath = 'C:\xampp\htdocs\rocket_api\upload\vm_drop\/';
            $file->move($rootPath, $filename);

            $body=array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'vm_drop_location'=> 'http://147.135.44.73/api/upload/vm_drop/'.$filename,
                'auto_id' => Session::get('id'));

              //echo "<pre>";print_r($body);die;

    $url = env('API_URL').'update-voice-mail';
           
   /* $add_ivr = Helper::PostApi($url,$body);
      echo "<pre>";print_r($add_ivr);die;*/

    try{
      $add_ivr = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
      if($add_ivr->success == 'true'){
              // echo "<pre>";print_r($group);die;
              //return back()->withSuccess($result->message);
        return back()->withSuccess($add_ivr->message);
      }

      if($add_ivr->success == 'false'){
        return redirect('/');
                //return back()->withSuccess($ext_group->message);
      }
    }

     catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (add-ivr): Oops something went wrong :( Please contact your administrator.)");
      }


    catch (RequestException $ex) {
    return back()->with('message',"Error code - (add-dnc): Oops something went wrong :( Please contact your administrator.)");
    }
          }


    function sendEmailToForgotPasswor(Request $request)
    {
        $url = env('API_URL').'forgot-password-email/email?email='.$request->email;
        $response = Helper::GetApi($url);
        echo json_encode($response);
    }
 
    //timezone code

    public function updateTimezone(Request $request)
    {
        $this->validate($request, [
            'timezone' => 'required|string|max:255',
        ]);

        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "update-timezone";
            $response = Helper::PostApi($url, $this->getBuildBody($request));
            if (!$response->success)
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }

        session()->flash("success", "Timezone updated Successfully");
        return redirect()->back();
    }

    private function getBuildBody(Request $request)
    {
        $body = ["timezone" => trim($request->get("timezone"))];
        return $body;
    }
  
    public function forgotPassword(Request $request)
    {
        $url = env('API_URL') . 'forgot-password';
        $body = [
            'email' => $request->email,
        ];

        try {
            $response = Helper::PostApi($url, $body);
            //dd($response);
           //echo"<pre>";print_r($response);die;
           if (isset($response->message) && $response->message === 'User not found') {
            return response()->json(['error' => 'InvalidEmail']);   
            }
            return back()->withSuccess($response->message);
        } catch (\Exception $e) {
            return back()->with('message', 'Error occurred: ' . $e->getMessage());
        }
    }

    public function verifyToken(Request $request,$token)
    {
        $errors = new MessageBag();
        try
        {
            $expires = $request->input('expires');
            $expiresAt = Carbon::createFromTimestamp($expires);
    
            if (!$expiresAt->isFuture()) {
                return redirect('/')->withErrors('link has been expired.Please try again');
            }
            $url = env('API_URL') . "verify-token/$token";
            
            $response = Helper::GetApi($url, [], true);
            //echo"<pre>";print_r($response);die;
            if($response['message']==='Reset token verified'){
            $request->session()->put('reset_token', $token);
            return view('login.newPassword');
            }
            else{
                return redirect('/')->withErrors($response['message']);
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return redirect('/')->withErrors($response['message']);
        }
    }
    public function resetPasswordUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $token = $request->session()->get('reset_token');
        $url = env('API_URL') . 'resetPasswordUser';
        $body = [
            'token'=>$token,
            'password' => $request->password,
        ];

        //echo"<pre>";print_r($body);die;
        try {
            $response = Helper::PostApi($url, $body);
                 return redirect('/')->withSuccess($response->message);

            

            // if ($response->success == 'true') {
            //     return back()->withSuccess($response->message);
            // }

            // if ($response->success == 'false') {
            //     return back()->withSuccess($response->message);
            // }
        } catch (\Exception $e) {
            return back()->with('message', 'Error occurred: ' . $e->getMessage());
        }
    }


    public function forgotPasswordMobile(Request $request)
    {
        $url = env('API_URL') . 'forgot-password-mobile';
        $body = [
            'mobile' => $request->mobile,
        ];
        
        try {
            $response = Helper::PostApi($url, $body);
            //echo"<pre>";print_r($response);die;
           if (isset($response->message) && $response->message === 'User not found') {
            return response()->json(['error' => 'InvalidMobileNumber']);            }
    
            $otp_id = $response->otp_id; // Assuming this is present in the API response
            $mobile = $response->mobile; // Assuming this is present in the API response
    
            $view = view('login.mobile_otp', compact('otp_id', 'mobile'))->render();
    
            $response = [
                'message' => 'OTP has been sent to your mobile number.',
                'view' => $view,
            ];
    
            return response()->json($response);
        } catch (\Exception $e) {
            return back()->with('message', 'Error occurred: ' . $e->getMessage());
        }
    }
    

    // public function forgotPasswordMobile(Request $request)
    // {
       
    //     $url = env('API_URL') . 'forgot-password-mobile';
    //     $body = [
    //         'mobile' => $request->mobile,
    //     ];
        
    //     try {
    //         $response = Helper::PostApi($url, $body);    
    //         $otp_id = $response->otp_id;
    //         //return $otp_id;
    //          //dd($response);die;

    //         $mobile = $response->mobile;
    //         return view('login.mobile_otp', compact('otp_id', 'mobile'));
            
    //     } catch (\Exception $e) {
            
    //         return back()->with('message', 'Error occurred: ' . $e->getMessage());
            
    //     }
    // }
    public function verifyTokenMobile(Request $request,$otp_id)
    {
       // return $request->all();
        $errors = new MessageBag();
        try
        {
           
            $url = env('API_URL') . "verify-token-mobile/$otp_id";
            $body = [
                'otp' => $request->otp,
            ];
            
            $response = Helper::PostApi($url,$body);
           // echo"<pre>";print_r($response);die;
            if($response->message==='Reset token verified'){
            $request->session()->put('reset_token', $otp_id);
            return view('login.newPasswordMobile');
            }
            else{
                return redirect('/')->withErrors($response->message);
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return redirect('/')->withErrors($response->message);
        }
    }
    public function resetPasswordUserMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $token = $request->session()->get('reset_token');
        $url = env('API_URL') . 'resetPasswordUserMobile';
        $body = [
            'token'=>$token,
            'password' => $request->password,
        ];

        // echo"<pre>";print_r($body);die;
        try {
            $response = Helper::PostApi($url, $body);
                 return redirect('/')->withSuccess($response->message);

            

            // if ($response->success == 'true') {
            //     return back()->withSuccess($response->message);
            // }

            // if ($response->success == 'false') {
            //     return back()->withSuccess($response->message);
            // }
        } catch (\Exception $e) {
            return back()->with('message', 'Error occurred: ' . $e->getMessage());
        }
    }
 

}


