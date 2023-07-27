<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Storage;
use File;


class ApiConferencingController extends Controller
{

    function getConferencing()
    {
        if (empty(Session::get('tokenId'))) {
            return redirect('/');

        }
        $url = env('API_URL') . 'conferencing';

        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );

        $arrLang = [];
        $objApiIvrController = new ApiIvrController();
        $arrGoogleLang = $objApiIvrController->getGoogleLanugages();

        foreach ($arrGoogleLang as $lang) {
            $temp = [];
            $temp['id'] = $lang->id;
            $temp['language'] = $lang->language;
            $temp['voice_name'] = $lang->voice_name;
            $temp['ssml_gender'] = $lang->ssml_gender;
            $arrLang[base64_encode($lang->language)][] = $temp;
        }

        try {
            $conferencing = Helper::PostApi($url, $body);
            if ($conferencing->success == 'true') {
                $conferencing_list = $conferencing->data;
                return view('conferencing.conferencing', compact('conferencing_list', 'arrLang'));
            }

            if ($conferencing->success == 'false') {
                $conferencing_list = array();
                return view('conferencing.conferencing', compact('conferencing_list', 'arrLang'));
            }

        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (conferencing): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (conferencing): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function storeConferencing(Request $request)
    {
        //get asteriskServers
        $arrAstriskServers = [];
        $intPromptOption = 0;

        $url = env('API_URL') . "servers/asterisk-server";
        try {
            $response = Helper::GetApi($url, [], true);
            if ($response["success"]) {
                $arrAstriskServers = $response["data"];
            } else {
                Log::error("No asteriskServers found", ["file" => "ApiIvrController", "line" => "129"]);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to get asteriskServers", [
                "message" => $e->getMessage(),
                "line" => $e->getLine(),
                "file" => $e->getFile(),
                "code" => $e->getCode(),
            ]);
        }

        //To edit Conferencing entry
        if (!empty($request->auto_id)) {

            $strFileName = $strFilenameDb = $strExtension = "";
            $strRootPath = env('FILE_UPLOAD_PATH') . env('CONFERENCE_FILE_UPLOAD_FOLDER_NAME');

            if ($request->ivr_audio_option == 'text_to_speech') {
                $strFileName = Session::get('id') . "_output.mp3";
                $strFilenameDb = Session::get('parentId') . '_conference_' . time();
                $strExtension = 'mp3';

                $intPromptOption = 1;
            } elseif ($request->ivr_audio_option == 'audio_record') {
                $strFileName = Session::get('id') . "_recorded.wav";
                $strFilenameDb = Session::get('parentId') . '_conference_' . time();
                $strExtension = 'wav';

                $intPromptOption = 2;
            } else {
                $file = $request->file('prompt_file');
                $strExtension = $file->getClientOriginalExtension(); // getting image extension
                $strFileName = Session::get('parentId') . '_conference_' . time() . '.' . $strExtension;
                $strFilenameDb = Session::get('parentId') . '_conference_' . time();
                $file->move($strRootPath, $strFileName);

                $intPromptOption = 0;
            }

            $strFilenameDb = ApiIvrController::setAudioFileFormat($strRootPath, $strFileName, $strFilenameDb, $strExtension, $arrAstriskServers, "conference-recordings");

            $strUrl = env('API_URL') . 'edit-conferencing';
            $arrBody = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'title' => $request->title,
                'conference_id' => $request->conference_id,
                'host_pin' => $request->host_pin,
                'part_pin' => $request->part_pin,
                'max_part' => $request->max_part,
                'locked' => $request->locked,
                'mute' => $request->mute,
                'prompt' => $strFilenameDb,
                'auto_id' => $request->auto_id,
                'speech_text' => $request->speech_text,
                'prompt_option' => $intPromptOption,
                'language' => $request->language,
                'voice_name' => $request->voice_name,
            );

            try {
                $editConferencing = Helper::PostApi($strUrl, $arrBody);
                if ($editConferencing->success == 'true') {
                    return back()->withSuccess($editConferencing->message);
                }

                if ($editConferencing->success == 'false') {
                    return back()->withSuccess($editConferencing->message);
                }
            } catch (BadResponseException   $e) {
                return back()->with('message', "Error code - (edit_conferencing): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                return back()->with('message', "Error code - (edit_conferencing): Oops something went wrong :( Please contact your administrator.)");
            }


        } else {
            //To insert new conferencing entry
            $strFileName = $strFilenameDb = $strExtension = "";
            $strRootPath = env('FILE_UPLOAD_PATH') . env('CONFERENCE_FILE_UPLOAD_FOLDER_NAME');

            if ($request->ivr_audio_option == 'text_to_speech') {
                $strFileName = Session::get('id') . "_output.mp3";
                $strFilenameDb = Session::get('parentId') . '_conference_' . time();
                $strExtension = 'mp3';

                $intPromptOption = 1;
            } elseif ($request->ivr_audio_option == 'audio_record') {
                $strFileName = Session::get('id') . "_recorded.wav";
                $strFilenameDb = Session::get('parentId') . '_conference_' . time();
                $strExtension = 'wav';

                $intPromptOption = 2;
            } else {
                $file = $request->file('prompt_file');
                $strExtension = $file->getClientOriginalExtension(); // getting image extension
                $strFileName = Session::get('parentId') . '_conference_' . time() . '.' . $strExtension;
                $strFilenameDb = Session::get('parentId') . '_conference_' . time();
                $file->move($strRootPath, $strFileName);

                $intPromptOption = 0;
            }

            $strFilenameDb = ApiIvrController::setAudioFileFormat($strRootPath, $strFileName, $strFilenameDb, $strExtension, $arrAstriskServers, "conference-recordings");

            $strUrl = env('API_URL') . 'add-conferencing';
            $arrBody = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'title' => $request->title,
                'conference_id' => $request->conference_id,
                'host_pin' => $request->host_pin,
                'part_pin' => $request->part_pin,
                'max_part' => $request->max_part,
                'locked' => $request->locked,
                'mute' => $request->mute,
                'prompt' => $strFilenameDb,
                'speech_text' => $request->speech_text,
                'prompt_option' => $intPromptOption,
                'language' => $request->language,
                'voice_name' => $request->voice_name,
            );

            try {
                $objAddConference = Helper::PostApi($strUrl, $arrBody);
                if ($objAddConference->success == 'true') {
                    return back()->withSuccess($objAddConference->message);
                }

                if ($objAddConference->success == 'false') {
                    return back()->withSuccess($objAddConference->message);
                }
            } catch (BadResponseException   $e) {
                return back()->with('message', "Error code - (add_conference): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                return back()->with('message', "Error code - (add_conference): Oops something went wrong :( Please contact your administrator.)");
            }
        }
    }

    //TODO: below function is not getting used discuss with Rohit and teammates in order to remove it.
    function editConferencing($auto_id)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'auto_id' => $auto_id,

        );

        $url = env('API_URL') . 'conferencing';

        try {
            $show_conference_details = Helper::PostApi($url, $body);
            if ($show_conference_details->success == 'true') {
                $show_conference_details = $show_conference_details->data;
                return $show_conference_details;
            }

            if ($show_conference_details->success == 'false') {
                return false;
                //return back()->withSuccess($ext_group->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function deleteConferencing($auto_id)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'auto_id' => $auto_id,

        );

        $url = env('API_URL') . 'delete-conferencing';
        $delete_conferencing = Helper::PostApi($url, $body);

        try {
            $delete_conferencing = Helper::PostApi($url, $body);

            if ($delete_conferencing->success == 'true') {
                return back()->withSuccess($delete_conferencing->message);
            }

            if ($delete_conferencing->success == 'false') {
                return back()->withSuccess($delete_conferencing->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (delete-dnc): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (delete-dnc): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    //TODO: this function definition is repeated in many files but not used, need to take some action, ask Rohit.
    function uploadExcel()
    {
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

    public function saveRecordedAudio(Request $request)
    {
        try {
            $strRootPath = env('FILE_UPLOAD_PATH') . env('CONFERENCE_FILE_UPLOAD_FOLDER_NAME');
            $strFilename = Session::get('id') . '_recorded.wav';
            $file = $request->file('data');
            $file->move($strRootPath, $strFilename);
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

