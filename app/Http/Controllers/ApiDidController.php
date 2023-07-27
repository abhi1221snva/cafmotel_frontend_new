<?php

namespace App\Http\Controllers;

use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Exception;
use InvalidArgumentException;
use File;
use Illuminate\Support\Facades\View;

class ApiDidController extends Controller {

    public function sendToCrmUserPost(Request $request)
    {
        try
        {
            $url = $request->url;
            $array = $request->all();
            $ch = curl_init();
            $curlConfig = array(
                CURLOPT_URL            =>  $url,
                CURLOPT_POST           => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS     => $array
            );

            curl_setopt_array($ch, $curlConfig);
            $result = curl_exec($ch);
            curl_close($ch);

            $final_data = json_decode($result);

            if($final_data->status_code == 422)
            {
                echo "<pre>";print_r(json_encode($final_data));die;
            }
            
            else
                if($final_data->status_code == 200)
                {
                    return redirect('https://vga.webinopoly.co//lead-overview?lead_id='.$final_data->data->lead_id);    
                }

        }
        catch (RequestException $ex) {
            echo "Error";die;
        }
        
    }

    public function getDIDForSale($cid, $sid, $npa, $nxx) {
        $url = "http://api.didforsale.com/didforsaleapi/index.php/api/v2/ListDID/" . config('app.did_api_key.API_KEY') . "?npa=" . $npa . "&nxx=" . $nxx . "&show=10";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $err = curl_error($ch);   //if you need
        curl_close($ch);
        return $did_array = json_decode($response, true);
    }

    function getListDid() {
        $ivr_menu = '';
        $inherit_list = new InheritApiController;
        $did_list = $inherit_list->getDidList();
        if (!is_array($did_list)) {
            $did_list = array(); // checking empty record
        }
        $destTypeList = Config::get('desttype.dest_type');
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }
        $country_list = $inherit_list->getCountry();
        if (!is_array($country_list)) {
            $country_list = array(); // checking empty record
        }
        $extension_list = $inherit_list->getExtensionList();
        $dest_type = $inherit_list->getDestType();
        $ivr_list = $inherit_list->getIvr();
        $ring_group_list = $inherit_list->getRingGroupList();
        if (!is_array($ivr_list)) {
            $ivr_list = array();
        }
        if (!is_array($ring_group_list)) {
            $ring_group_list = array();
        }
        return view('did.purchase_list_did', compact('did_list', 'country_list', 'dest_type', 'ivr_list', 'extension_list', 'ring_group_list', 'destTypeList'));
    }
    
    //close list did
    function getListList() {
        $ivr_menu = '';
          // Get the value of the 'title' section
        
        $inherit_list = new InheritApiController;
        $did_list = $inherit_list->getDidList();
        //echo "<pre>";print_r($did_list);die;
        if (!is_array($did_list)) {
            $did_list = array(); // checking empty record
        }
        $destTypeList = Config::get('desttype.dest_type');

        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }
        // if(empty($did_list)){
        // }

        /* conferencing list */
        $url = env('API_URL').'conferencing';
        try
        {
            $response = Helper::PostApi($url);
            if ($response->success) {
                $conferencing = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        }
        catch (RequestException $ex)
        {
            $errors = new \Illuminate\Support\MessageBag();
            $errors->add("error", $ex->getMessage());
        }

        $extension_list = $inherit_list->getExtensionList();
        $dest_type = $inherit_list->getDestType();
        $ivr_list = $inherit_list->getIvr();
        $ring_group_list = $inherit_list->getRingGroupList();
        if (!is_array($ivr_list)) {
            $ivr_list = array();
        }
        if (!is_array($ring_group_list)) {
            $ring_group_list = array();
        }
        if (!is_array($extension_list)) {
            session()->flash("message", $extension_list);
            $extension_list = null;
        }
        return view('did.did_list', compact('did_list', 'dest_type', 'ivr_list', 'extension_list', 'ring_group_list', 'destTypeList','conferencing'));
    }




    function getExtensionList()
    {
        $url = env('API_URL') . 'active-extension-group-list';
        try
        {
            $response = Helper::PostApi($url);
            return $response;
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
        }
    }


    function addDid()
    {
        $inherit_list = new InheritApiController;
        $extension_list = $this->getExtensionList();
        $dest_type = $inherit_list->getDestType();
        $ivr_list = $inherit_list->getIvr();
        $ring_group_list = $inherit_list->getRingGroupList();
        $department_list = $inherit_list->getDepartmentList();

        $errors = new MessageBag();
        /* conferencing list */
        $list = [];
        $url = env('API_URL').'conferencing';
        try
        {
            $response = Helper::PostApi($url);
            if ($response->success)
            {
                $conferencing = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
        }

        return view('did.add',compact('extension_list','dest_type','ivr_list',
                'department_list','conferencing','ring_group_list'));
    }

    function storeDid(Request $request) 
{
    
    
   
      if ($request->isMethod('post'))
        {
             $cli = $request->cli;
            $cnam = $request->cnam;
            $area_code = substr($cli, 1, 3);
            if($request->dest_type == '6')
            { //fax
                $option_1 = 'f';
            }
            else
                $option_1 = 'v';
            if (!empty($cnam)) 
            {
                $body = array(
                    'id' => Session::get('id'),
                    'token' => Session::get('tokenId'),
                    'cli' => $cli,
                    'cnam' => $request->cnam,
                    'area_code' => $area_code,
                    'dest_type' => $request->dest_type,
                    'extension' => $request->extension,
                    'ivr_id' => $request->ivr_id,
                    'conf_id' => $request->conf_id,
                    'operator_check' => $request->operator_check,
                    'operator' => $request->operator,
                    'forward_number' => $request->forward_number,
                    'voicemail_id' => $request->voicemail_id,
                    'ingroup' => $request->ingroup,
                    'default_did' => $request->default_did,
                    'option_1' => $request->option_1,
                    'is_sms' => $request->chk_sms,
                    'queue_id' => $request->queue_id,
                    'sms_phone' => $request->countryCode.$request->sms_phone,
                    'sms_email' => $request->sms_email,
                    'fax_did' => $request->fax_did,
                    'call_time_department_id' =>  $request->call_time_department_id,
                    'call_time_holiday' =>  $request->call_time_holiday,
                    'dest_type_ooh' =>  $request->dest_type_ooh,
                    'ivr_id_ooh' =>  $request->ivr_id_ooh,
                    'extension_ooh' =>  $request->extension_ooh,
                    'voicemail_id_ooh' =>  $request->voicemail_id_ooh,
                    'forward_number_ooh' =>  $request->forward_number_ooh,
                    'conf_id_ooh' =>  $request->conf_id_ooh,
                    'ingroup_ooh' =>  $request->ingroup_ooh,
                    'queue_in_ooh' =>  $request->queue_in_ooh,
                );

                $validator = Validator::make($request->all(), [
                            'cli' => 'required',
                            'cnam' => 'required|string|max:50',
                            'area_code' => 'required|string|max:50',
                            'dest_type' => 'required',
                ]);


                $url = env('API_URL') . 'add-did';

                try 
                {
                    $didResponse = Helper::PostApi($url, $body);
                    //print_r($didResponse); echo Session::get('tokenId');
                    if (isset($didResponse->success) && $didResponse->success == 'true')
                    {

                        if (!empty($request->operator)) 
                        {
                            return redirect('/did')->withSuccess($didResponse->message);
                        } else 
                        {
                            return redirect('/did')->withSuccess($didResponse->message);
                        }
                    }
                } catch (BadResponseException $e) {
                    return back()->with('message', "Error code - (add-did): Oops something went wrong :( Please contact your administrator.)");
                } catch (Exception $e) {
                    echo $e->getMessage();
                } catch (InvalidArgumentException $e) {
                    echo $e->getMessage();
                }
            }
        }
    
    }


    function editList($did_id)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'did' => $did_id,
        );

        $errors = new MessageBag();

        /* Phone Country list */
        $list = [];
        $url = env('API_URL').'phone-country-list';
        try
        {
            $response = Helper::PostApi($url);
            if ($response->success)
            {
                $phone_country = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
        }


        /* conferencing list */
        $list = [];
        $url = env('API_URL').'conferencing';
        try
        {
            $response = Helper::PostApi($url);
            if ($response->success)
            {
                $conferencing = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
        }

        $url = env('API_URL') . 'did_detail';
        try
        {
            $didResponse = Helper::PostApi($url, $body);
            if ($didResponse->success == 'true')
            {
                $didData = $didResponse->data;
                $inherit_list = new InheritApiController;
                $dest_type = $inherit_list->getDestType();
                $ivr_list = $inherit_list->getIvr();
                $extension_list = $this->getExtensionList();
                $ring_group_list = $inherit_list->getRingGroupList();
                $fax_did_list = $inherit_list->getFaxDidList($didData->cli);
                $department_list = $inherit_list->getDepartmentList();

                if (!is_array($fax_did_list))
                {
                    $fax_did_list = array();
                }

                if (!is_array($ivr_list))
                {
                    $ivr_list = array();
                }

                if (!is_array($ring_group_list))
                {
                    $ring_group_list = array();
                }

                $arrLang = [];
                $arrGoogleLang = $this->getGoogleLanugages();
                foreach($arrGoogleLang as $lang)
                {
                    $temp = [];
                    $temp['id'] = $lang->id;
                    $temp['language'] = $lang->language;
                    $temp['voice_name'] = $lang->voice_name;
                    $temp['ssml_gender'] = $lang->ssml_gender;
                    $arrLang[base64_encode($lang->language)][] = $temp;
                }

                return view('did.edit_did', compact('didData', 'dest_type', 'ivr_list','department_list',
                        'extension_list', 'ring_group_list','fax_did_list','conferencing','arrLang','phone_country'));
            }

            if ($didResponse->success == 'false')
            {
                return redirect('/');
                //return back()->withSuccess($ext_group->message);
            }
        }
        catch (BadResponseException $e)
        {
            return back()->with('message', "Error code - (group): Oops something went wrong :( Please contact your administrator.)");
        }
        catch (RequestException $ex)
        {
            return back()->with('message', "Error code - (group): Oops something went wrong :( Please contact your administrator.)");
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

    function saveEditDid(Request $request) {
        if ($request->isMethod('post')) {
 if($request->call_screening_status == 1)
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
        $ivrFolder = env('ANNOUNCEMENTS_FILE_UPLOAD_FOLDER_NAME');
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

       

        if (!empty($request->old_ann_id)) {
            $old_ann_id = $request->old_ann_id . '.wav';
            if (file_exists($rootPath . '/' . $old_ann_id)) {
                unlink($rootPath . '/' . $old_ann_id);
            }

            if($request->ivr_audio_option == 'text_to_speech')
            {
                $filename = Session::get('id')."_output.mp3";
                $filenameDb = Session::get('parentId') . '_ann_' . time();
                $extension = 'mp3';

                $intPromptOption = 1;
            } elseif($request->ivr_audio_option == 'audio_record'){
                $filename = Session::get('id')."_recorded.wav";
                $filenameDb = Session::get('parentId') . '_ann_' . time();
                $extension = 'wav';

                $intPromptOption = 2;
            } else {
                $file = $request->file('ann_id');
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $filenameDb = Session::get('parentId') . '_ann_' . time();
                $filename = $filenameDb . '.' . $extension;
                $file->move($rootPath, $filename);

                $intPromptOption = 0;
            }

            $filenameDb = self::setAudioFileFormat($rootPath, $filename, $filenameDb, $extension, $arrAstriskServers);
}
else{
            //add new entry
            if($request->ivr_audio_option == 'text_to_speech')
            {
                $filename = Session::get('id')."_output.mp3";
                $filenameDb = Session::get('parentId') . '_ann_' . time();
                $extension = 'mp3';

                $intPromptOption = 1;
            } elseif($request->ivr_audio_option == 'audio_record'){
                $filename = Session::get('id')."_recorded.wav";
                $filenameDb = Session::get('parentId') . '_ann_' . time();
                $extension = 'wav';

                $intPromptOption = 2;
            }
            else {
                $file = $request->file('ann_id');
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $filenameDb = Session::get('parentId') . '_ann_' . time();
                $filename = $filenameDb . '.' . $extension;
                $file->move($rootPath, $filename);

                $intPromptOption = 0;
            }

            $filenameDb = self::setAudioFileFormat($rootPath, $filename, $filenameDb, $extension, $arrAstriskServers);
}

}

else
{
    $filenameDb = "";
    $intPromptOption ="0";

}

            if($request->country_code == 1)
            {
                $forward_number_request = $request->country_code.$request->forward_number;
                $forward_number = str_replace(array('(',')', '_', '-',' '), array(''), $forward_number_request); 
            }
            else
            {
                $forward_number_request = '011'.$request->country_code.$request->forward_number;
                 $forward_number = str_replace(array('(',')', '_', '-',' '), array(''), $forward_number_request); 
            }

            if($request->country_code_ooh == 1)
            {
                $forward_number_request = $request->country_code_ooh.$request->forward_number_ooh;
                $forward_number_ooh = str_replace(array('(',')', '_', '-',' '), array(''), $forward_number_request); 
            }
            else
            {
                $forward_number_request = '011'.$request->country_code_ooh.$request->forward_number;
                $forward_number_ooh = str_replace(array('(',')', '_', '-',' '), array(''), $forward_number_request); 
            }
            
            $cli = $request->cli;
            $did_id = $request->did_id;

            $area_code = substr($cli, 1, 3);
            if($request->dest_type == '6'){ //fax
                $option_1 ='';
            }
            else
                $option_1 = 'v';

            $cnam = $request->cnam;
            if (!empty($cnam)) {
                $body = array(
                    'id' => Session::get('id'),
                    'token' => Session::get('tokenId'),
                    'did_id' => $request->did_id,
                    'cli' => $request->cli,
                    'cnam' => $request->cnam,
                    'area_code' => $area_code,
                    'dest_type' => $request->dest_type,
                    'extension' => $request->extension,
                    'ivr_id' => $request->ivr_id,
                    'conf_id' => $request->conf_id,
                    'operator_check' => $request->operator_check,
                    'operator' => $request->operator,
                    'forward_number' => $forward_number,
                    'country_code' => $request->country_code,
                    'voicemail_id' => $request->voicemail_id,
                    'ingroup' => $request->ingroup,
                    'default_did' => $request->default_did,
                    'option_1' => $option_1,
                    'sms' => $request->chk_sms,
                    'queue_id' => $request->queue_id,
                    'sms_phone' => $request->countryCode.$request->sms_phone,
                    'sms_email' => $request->sms_email,
                    'fax_did' => $request->fax_did,
                    'call_time_department_id' =>  $request->call_time_department_id,
                    'call_time_holiday' =>  $request->call_time_holiday,
                    'dest_type_ooh' =>  $request->dest_type_ooh,
                    'ivr_id_ooh' =>  $request->ivr_id_ooh,
                    'extension_ooh' =>  $request->extension_ooh,
                    'voicemail_id_ooh' =>  $request->voicemail_id_ooh,
                    'forward_number_ooh' =>  $forward_number_ooh,
                    'country_code_ooh' => $request->country_code_ooh,
                    'conf_id_ooh' =>  $request->conf_id_ooh,
                    'ingroup_ooh' =>  $request->ingroup_ooh,
                    'queue_in_ooh' =>  $request->queue_in_ooh,
                    'set_exclusive_for_user' => $request->set_exclusive_for_user,
                    'call_screening_status' => $request->call_screening_status,
                    'call_screening_ivr_id' => $filenameDb,
                    'ivr_desc' => $request->ivr_desc,
                    'ann_id' => $filenameDb,
                    'language' => base64_decode($request->language),
                    'voice_name' => $request->voice_name,
                    'ivr_audio_option' => $request->ivr_audio_option,
                    'speech_text' => $request->speech_text,
                    'prompt_option' => $intPromptOption,
                    'redirect_last_agent' => $request->redirect_last_agent,

                );

                //echo "<pre>";print_r($body);die;


                $url = env('API_URL') . 'save-edit-did';

                try {
                    $didResponse = Helper::PostApi($url, $body);
                    if (isset($didResponse->success) && $didResponse->success == 'true') {
                        return redirect('/did')->withSuccess($didResponse->message);
                    }
                } catch (BadResponseException $e) {
                    return back()->with('message', "Error code - (edit-did): Oops something went wrong :( Please contact your administrator.)");
                } catch (Exception $e) {
                    echo $e->getMessage();
                } catch (InvalidArgumentException $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    public static function setAudioFileFormat($rootPath, $tmpFilename, $filenameDb, $extension, $arrAstriskServers = [], $strModuleDirectory = null) {
        $rootPath = $rootPath . "/";
        if($strModuleDirectory == null) $strModuleDirectory = "announcements";

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
                //shell_exec("rm -rf $tmpFilename");
               // shell_exec("mv $tmpFilename /tmp/");
            }
        }

        return $filenameDb;
    }

    function deleteDidData($list_id) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'did_id' => $list_id,
            'is_deleted' => 1,
        );
        $url = env('API_URL') . 'delete-did';
        try {
            $delete_list = Helper::PostApi($url, $body);
            if ($delete_list->success == 'true') {
                return back()->withSuccess($delete_list->message);
            }
            if ($delete_list->success == 'false') {
                return redirect('/');
                //return back()->withSuccess($ext_group->message);
            }
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public function sendToCrmUser(Request $request) {
        $url = env('API_URL') . 'send-to-crm';
        $body = array(
            'id' => $request->user_id,
            'token' => $request->user_token,
            'campaign_id' => $request->campaign,
            'lead_id' => $request->lead,
            'number' => preg_replace('/[^0-9]/', '', $request->number)
        );
        try {
            $result = Helper::PostApi($url, $body);

           // echo "<pre>";print_r($result);die;
            if ($result->success) {
                echo json_encode(array('status' => "success", 'url' => $result->data->url,'main_url'=>$result->data->main_url));
            } else {
                echo json_encode(array('status' => "false", 'message' => "Unable to call CRM"));
            }
            exit;
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (save-disposition): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    


    function findDefaultDid($default_did){
        $body = array(
            'default_did' => $default_did,
        );
         $url = env('API_URL') . "check-default-did";

             //$response = Helper::PostApi($url, $body);
             //echo "<pre>";print_r($response);die;
        try {
             $response = Helper::PostApi($url, $body);

            if ($response->success) {
                return $response->data;
            } else {
                $errors = new MessageBag();
                $errors->add("error", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
        } catch (\Throwable $exception) {
            $errors = new MessageBag();
            $errors->add("error", $exception->getMessage());
            return $errors;
        }
    }

    /**
    * Show Call timing listings
    * @return type
    */
    public function showOfficeHours() {
        $arrDays = ['Monday' => '','Tuesday' => '', 'Wednesday' => '', 'Thursday' => '',
            'Friday' => '', 'Saturday' => '', 'Sunday' => ''];
        $arrDep = $arrResult = [];
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId')
        );

        $url = env('API_URL') . 'get-call-timings';
        $OhResponse = Helper::PostApi($url, $body);

        if(!empty($OhResponse) && isset($OhResponse->data)) {
            foreach($OhResponse->data as $Oh) {
                $arrResult[$Oh->name][$Oh->day] = ['from_time' => $Oh->from_time, 'to_time' => $Oh->to_time];
                $arrDep[$Oh->name] = ['id' => $Oh->department_id, 'description' => $Oh->description];
            }
        }
        return view('did.office_hours', ['arrDays' => $arrDays, 'arrResult' => $arrResult, 'arrDep' => $arrDep]);
    }

    /**
    * Show call timings edti form
    * @return type
    */
    public function showOfficeHoursForm($dept_id = 0) {
        $arrDays = ['Monday' => '','Tuesday' => '', 'Wednesday' => '', 'Thursday' => '',
            'Friday' => '', 'Saturday' => '', 'Sunday' => ''];
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'dept_id' => $dept_id
        );

        $url = env('API_URL') . 'get-department-call-timings';
        $OhResponse = Helper::PostApi($url, $body);
        if(!empty($OhResponse) && isset($OhResponse->data)) {
            $departments = $OhResponse->data;
            foreach($OhResponse->data as $Oh) {
                $arrDays[$Oh->day] = ['from_time' => $Oh->from_time, 'to_time' => $Oh->to_time];
            }
        }

        return view('did.edit_office_hours', ['arrDays' => $arrDays, 'dept_id' => $dept_id,
            'departments' => $departments]);
    }

    /**
    * Save office hours
    * @param Request $request
    * @return type
    */
    public function saveOfficeHours(Request $request) {
        $validator = Validator::make($request->all(), [
                        'name' => 'required|string|max:100',
                        'description' => 'required|string|max:250'
                    ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator);
        }
        $postData = $request->all();
        //validate if either of one field is empty
        for($i=0; $i<7; $i++) {
            if(($postData['from'][$i] != '' && $postData['to'][$i] == '')
                || ($postData['from'][$i] == '' && $postData['to'][$i] != '')) {
                return redirect()->back()->withInput($request->input())->withErrors("Both From and To time are mandatory");
            }
        }
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'data' => $request->all()
        );

        $url = env('API_URL') . 'save-call-timings';

        try {
            $OhResponse = Helper::PostApi($url, $body);
            if (isset($OhResponse->success) && $OhResponse->success == 'true') {
                return redirect('/did/call-timings-listing')->withSuccess($OhResponse->message);
            } else {
                return redirect()->back()->withInput($request->input())->withErrors($OhResponse->message);
            }
        } catch (Exception $e) {
            return redirect('/did/call-timings-listing')->withError("Somethig went wrong!!!");
        }
    }

    /**
    * Show Holidays
    * @return type
    */
    public function showHolidays($id = 0) {
        $arrDates = $this->getDates();
        $arrMonths = $this->getMonths();
        $detail = $arrResult = $holidays = [];
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'holiday_id' => $id
        );

        $url = env('API_URL') . 'get-holiday-datail';
        $holiRes = Helper::PostApi($url, $body);
        $holi_detail = (array)$holiRes;
        if(isset($holi_detail['data']['0'])) {
            $detail['name'] = $holi_detail['data']['0']->name;
            $detail['date'] = $holi_detail['data']['0']->date;
            $detail['month'] = $holi_detail['data']['0']->month;
        }

        $url = env('API_URL') . 'get-all-holidays';
        $hResponse = Helper::PostApi($url, $body);

        if(!empty($hResponse) && isset($hResponse->data)) {
            foreach($hResponse->data as $Oh) {
                $holidays[] = ['name' => $Oh->name,'date' => $Oh->date, 'month' => $Oh->month, 'id' => $Oh->id];
            }
        }
        return view('did.holidays', ['arrDates' => $arrDates, 'arrMonths' => $arrMonths,
            'holidays' => $holidays, 'id' => $id, 'detail' => $detail]);
    }

    /**
    * Save Holiday
    * @param Request $request
    * @return type
    */
    public function saveHoliday(Request $request) {
        $validator = Validator::make($request->all(), [
                        'name' => 'required|string|max:100',
                        'date' => 'required',
                        'month' => 'required'
                    ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator);
        }

        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'data' => $request->all()
        );

        $url = env('API_URL') . 'save-holiday-detail';

        try {
            $OhResponse = Helper::PostApi($url, $body);

            if (isset($OhResponse->success) && $OhResponse->success == 'true') {
                return redirect('/did/holidays')->withSuccess($OhResponse->message);
            } else {
                return redirect()->back()->withInput($request->input())->withErrors($OhResponse->message);
            }
        } catch (Exception $e) {
            return redirect('/did/holidays')->withError("Somethig went wrong!!!");
        }
    }

    /**
    * Delete Holiday
    * @return type
    */
    public function deleteHoliday($id) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'holiday_id' => $id,
        );
        $url = env('API_URL') . 'delete-holiday';

        try {
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                return response()->json($response->message);
            } else {
                return response()->json($response->message, 500);
            }
        } catch (Exception $e) {
            return response()->json("Something went wrong!!", 500);
        }
    }

    /**
    * Show buy did page
    * @return type
    */
    function showBuyDidPage() {
        return view('did.buy_did');
    }

    function showBuyDidPagePlivo() {
        return view('did.plivo_buy_did');
    }


    public function getDidListFromPlivo(Request $request) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'data' => $request->all(),
        );

        ///echo "<pre>";print_r($body);die;
        $url = env('API_URL') . 'get-did-list-from-plivo';

        try {
            $response = Helper::PostApi($url, $body);

       //echo "<pre>";print_r($response);die;
            
            return $response;
        } catch (Exception $e) {
            return response()->json("Something went wrong!!", 500);
        }
    }

    /**
    * Get DId Lit from api.didforsale.com
    * @return type
    */
    public function getDidListFromSale(Request $request) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'data' => $request->all(),
        );
        $url = env('API_URL') . 'get-did-list-from-sale';

        try {
            $response = Helper::PostApi($url, $body);
            return $response;
        } catch (Exception $e) {
            return response()->json("Something went wrong!!", 500);
        }
    }

    /**
    * Buy selected DId
    * @return type
    */

    public function buyDidPlivo(Request $request) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'data' => $request->all(),
        );
        $url = env('API_URL') . 'buy-save-selected-did-plivo';

        try {
            $response = Helper::PostApi($url, $body);
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json("Something went wrong!!", 500);
        }
    }


    public function buyDid(Request $request) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'data' => $request->all(),
        );
        $url = env('API_URL') . 'buy-save-selected-did';

        try {
            $response = Helper::PostApi($url, $body);
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json("Something went wrong!!", 500);
        }
    }

    public function saveRecordedAudio(Request $request){
        return 1;
        try {
            $rootPath = env('FILE_UPLOAD_PATH') . env('ANNOUNCEMENTS_FILE_UPLOAD_FOLDER_NAME');
            $filename = Session::get('id') . '_recorded.wav';
            $file = $request->file('data');
            $file->move($rootPath, $filename);
        } catch (\Throwable $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage(),
                'number' => null,
                'lead_id' => null,
                'data' => []
            ];
        }
        return [
            'success' => true,
            'message' => "File stored successfully",
        ];
    }
   
    function upload(Request $request)
    {
        if(!empty($request->file('did_file')))
        {        
            $file = $request->file('did_file');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().'.'.$extension;
            $rootPath = env("FILE_UPLOAD_PATH");
            //$rootPath='C:\xampp\htdocs\cafmotel\backend\public\api/';
            $file->move($rootPath, $filename);
            
            $body=array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'file' => $filename,
                'upload_title' => 'Did List',
            );
            $url = env('API_URL').'upload-did';
            //echo $url;
      

            try
            {
                $did_upload = Helper::PostApi($url,$body);
                //echo "<pre>";print_r($did_upload);die;

            if($did_upload->success == 'true')
            {

            unlink($rootPath.$filename);
            return back()->withSuccess($did_upload->message);
                
            }

            if($did_upload->success == 'false')
            {
                return back()->withSuccess($did_upload->message);
            }
        }

        catch (BadResponseException $e) 
        {
            return back()->with('message',"Error code - (upload-did): Oops something went wrong :( Please contact your administrator.)");
        }

        catch (RequestException $ex) 
        {
        return back()->with('message',"Error code - (upload-did): Oops something went wrong :( Please contact your administrator.)");
        }
        }

    }
}
