<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\InheritApiController;
use Validator;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use File;
use Illuminate\Support\MessageBag;


class AudioMessageController extends Controller {

    /**
     * Iver Listing
     * @return type
     */
    public function index()
    {
        $audio_message = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "audio-message";
        try
        {
          $response = Helper::GetApi($url);
           // echo "<pre>";print_r($response);die;
          if($response->success)
          {
            $audio_message = $response->data;
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
          return view("audio-message.list", compact("errors", $errors));
        }
        return view('audio-message.list',compact('audio_message'));
    }

    /**
     * Show ivr edit form
     * @param type $id
     * @return type
     */
    function editIvrForm($id = 0) {
        $ivr_data = [];
        if($id > 0)
        {
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'auto_id' => $id,
            );


            $url = env('API_URL') . 'audio-message';
            $ivr_data = Helper::GetApi($url, $body);

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

        return view('audio-message.edit-audio-message', compact('ivr_data', 'arrLang'));
    }

    function storeDid(Request $request) {
        echo 'loop start';
        print_r($request);
    }

    /**
     * Add.edit ivr
     * @param Request $request
     * @return type
     */
    function storeAudioMessage(Request $request) {
        $rules = array('ivr_desc' => 'required', 'ivr_audio_option' => 'required');
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
                $filenameDb = Session::get('parentId') . '_audio_' . time();
                $extension = 'mp3';

                $intPromptOption = 1;
            } elseif($request->ivr_audio_option == 'audio_record'){
                $filename = Session::get('id')."_recorded.wav";
                $filenameDb = Session::get('parentId') . '_audio_' . time();
                $extension = 'wav';

                $intPromptOption = 2;
            } else {
                $file = $request->file('ann_id');
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $filenameDb = Session::get('parentId') . '_audio_' . time();
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
                'speed' => $request->speed_value,
                'pitch' => $request->pitch_value
                );


            //echo "<pre>";print_r($body);die;


            $url = env('API_URL') . 'edit-audio-message';


            $add_dnc = Helper::PostApi($url, $body);
           // echo "<pre>";print_r($add_dnc);die;

            try {
                $edit_ivr = Helper::PostApi($url, $body);
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
        } else {
            //add new entry
            if($request->ivr_audio_option == 'text_to_speech')
            {
                $filename = Session::get('id')."_output.mp3";
                $filenameDb = Session::get('parentId') . '_audio_' . time();
                $extension = 'mp3';

                $intPromptOption = 1;
            } elseif($request->ivr_audio_option == 'audio_record'){
                $filename = Session::get('id')."_recorded.wav";
                $filenameDb = Session::get('parentId') . '_audio_' . time();
                $extension = 'wav';

                $intPromptOption = 2;
            }
            else {
                $file = $request->file('ann_id');
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $filenameDb = Session::get('parentId') . '_audio_' . time();
                $filename = $filenameDb . '.' . $extension;
                $file->move($rootPath, $filename);

                $intPromptOption = 0;
            }

            $filenameDb = self::setAudioFileFormat($rootPath, $filename, $filenameDb, $extension, $arrAstriskServers);

            $url = env('API_URL') . 'add-audio-message';
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
                'speed' => $request->speed_value,
                'pitch' => $request->pitch_value
                );

          //  echo "<pre>";print_r($body);die;

            try {
                $add_ivr = Helper::PostApi($url, $body);
                if ($add_ivr->success == 'true') {
                    return redirect('/audio-message')->withSuccess($add_ivr->message);
                }

                if ($add_ivr->success == 'false') {
                    return redirect('/');
                }
            } catch (BadResponseException $e) {
                return back()->with('message', "Error code - (add-ivr): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                return back()->with('message', "Error code - (add-dnc): Oops something went wrong :( Please contact your administrator.)");
            }
        }
    }

    /**
     * convert mp3, wav to wav 8000
     * @param string $rootPath
     * @param type $tmpFilename
     * @param type $filenameDb
     * @param type $extension
     * @return type
     */
    public static function setAudioFileFormat($rootPath, $tmpFilename, $filenameDb, $extension, $arrAstriskServers = [], $strModuleDirectory = null) {
        $rootPath = $rootPath . "/";
        if($strModuleDirectory == null) $strModuleDirectory = "audio_message";

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

    function editIvr($id) {

        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'auto_id' => $id,
        );

        $url = env('API_URL') . 'ivr';
        try {
            $ivr_data = Helper::PostApi($url, $body);

            if ($ivr_data->success == 'true') {

                $ivr = $ivr_data->data;

                return $ivr;
            }

            if ($ivr_data->success == 'false') {
                return redirect('/');
                //return back()->withSuccess($ext_group->message);
            }
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (ivr): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (ivr): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function deleteIvr($auto_id, $ivr_id) {

        $rootPath = env('FILE_UPLOAD_PATH').env('IVR_FILE_UPLOAD_FOLDER_NAME');
        if(file_exists($rootPath ."/".$ivr_id . '.wav')) {
            unlink($rootPath ."/".$ivr_id . '.wav');
        }

        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'auto_id' => $auto_id,
        );

        $url = env('API_URL') . 'delete-ivr';
        try {
            $delete_ivr = Helper::PostApi($url, $body);

            if ($delete_ivr->success == 'true') {
                return back()->withSuccess($delete_ivr->message);
            }

            if ($delete_ivr->success == 'false') {
                return redirect('/');
            }
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (delete-ivr): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (delete-dnc): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function uploadExcel() {
        $file = $request->file('list_file');
        $extension = $file->getClientOriginalExtension(); // getting image extension
        $filename = time() . '.' . $extension;
        $rootPath = '/var/www/html/api/upload/';
        //$rootPath = 'C:\xampp\htdocs\api\upload\/';
        $file->move($rootPath, $filename);

        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'file' => $filename,
        );

        $url = env('API_URL') . 'add-list';
        try {
            $add_list = Helper::PostApi($url, $body);

            if ($add_list->success == 'true') {

                $list_id = $add_list->list_id;
                $campaign_id = $add_list->campaign_id;

                return redirect('/editList/' . $list_id . '/' . $campaign_id);
            }

            if ($add_list->success == 'false') {
                return redirect('/');
            }
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (add-dnc): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (add-dnc): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    /**
     * Get goggle client language
     * @return type
     */
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

    /**
     * Get voice name on selected language
     * @param Request $request
     * @return type
     */
    public function getVoiceNameOnLanugage(Request $request)
    {
        $arrLang = [];
        $arrGoogleLang = $this->getGoogleLanugages();
        foreach($arrGoogleLang as $lang)
        {
//            if(strstr($lang->voice_name, 'Wavenet'))
//            {
                $temp = [];
                $temp['id'] = $lang->id;
                $temp['language'] = $lang->language;
                $temp['language_code'] = $lang->language_code;
                $temp['voice_name'] = $lang->voice_name;
                $temp['ssml_gender'] = $lang->ssml_gender;
                if($request->language == base64_encode($lang->language))
                {
                    $arrLang[] = $temp;
                }
//            }
        }
        return response()->json($arrLang);
    }

    public function saveRecordedAudio(Request $request){
        try {
            $rootPath = env('FILE_UPLOAD_PATH') . env('IVR_FILE_UPLOAD_FOLDER_NAME');
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
}
