<?php

namespace App\Http\Controllers;

use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Events\IncomingLead;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;

/**
* Google client controller
* Text to Speech
*/
class GoogleClientController extends Controller {

    /**
    * Get request controller
    * @param Request $request
    */

    function voiceAudio(Request $request)
    {
        try
        {
            $uniqueId = rand(1000,9999);

            $text = "Abdu Rozik books entire theatre to watch Shah Rukh Khan's Pathaan with paparazzi, dances to Jhoome Jo Pathaan";//$request->speech_text;
            $voice = "en-US ## en-US-Standard-A ## MALE";//$request->voice_name_ddl;
            $voice_name_ddl = explode("##", $voice);

            $langCode = trim($voice_name_ddl[0]);
            $stand_wave = trim($voice_name_ddl[1]);
            $gender = trim($voice_name_ddl[2]);

            $client = new TextToSpeechClient(['credentials' => env('GOOGLE_JSON_KEY')]);
            $input_text = (new SynthesisInput())
            //->setText($text);
            ->setSsml("<speak>".$text."</speak>");

            if($gender == 'FEMALE')
            {
                $voice = (new VoiceSelectionParams())
                ->setLanguageCode($langCode)
                ->setName($stand_wave)
                ->setSsmlGender(SsmlVoiceGender::FEMALE);
            }
            else
            {
                $voice = (new VoiceSelectionParams())
                ->setLanguageCode($langCode)
                ->setName($stand_wave)
                ->setSsmlGender(SsmlVoiceGender::MALE);
            }

            // Effects profile
            $effectsProfileId = "telephony-class-application";

            // select the type of audio file you want returned
            $audioConfig = (new AudioConfig())
            ->setAudioEncoding(AudioEncoding::MP3)
            /*->setPitch($_GET['pitch'])
                ->setSpeakingRate($_GET['speking_rate']);*/
            ->setEffectsProfileId(array($effectsProfileId));

            $response = $client->synthesizeSpeech($input_text, $voice, $audioConfig);
            $audioContent = $response->getAudioContent();
            $file = $uniqueId."_output.mp3";

            $filePath = 'upload/voice_audio/'.$file;

            if(file_exists($filePath))
            {
                unlink($filePath);
            }
            file_put_contents($filePath, $audioContent);

            $client->close();
            return response()->json(['file' => $file]);

            $textToSpeechClient->close();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    

    function getAudioOnText(Request $request)
    {
        $uniqueId = Session::get('id');
        try
        {
            $text = $request->speech_text;
            if(empty($request->speed))
            {
                $speking_rate = 1.00;
            }
            else
            {
                $speking_rate = $request->speed;
            }

            if(empty($request->speed))
            {
                $pitch = 0.00;
            }
            else
            {
                $pitch = $request->pitch;
            }
            

            $voice_name_ddl = explode("##", $request->voice_name_ddl);
            $langCode = trim($voice_name_ddl[0]);
            $stand_wave = trim($voice_name_ddl[1]);
            $gender = trim($voice_name_ddl[2]);
            //$language = $request->language;
            $client = new TextToSpeechClient(['credentials' => env('GOOGLE_JSON_KEY')]);
            $input_text = (new SynthesisInput())
//                ->setText($text);
                ->setSsml("<speak>".$text."</speak>");
            if($gender == 'FEMALE')
            {
                $voice = (new VoiceSelectionParams())
                ->setLanguageCode($langCode)
                ->setName($stand_wave)
                ->setSsmlGender(SsmlVoiceGender::FEMALE);
            }
            else
            {
                $voice = (new VoiceSelectionParams())
                ->setLanguageCode($langCode)
                ->setName($stand_wave)
                ->setSsmlGender(SsmlVoiceGender::MALE);
            }

            // Effects profile
            $effectsProfileId = "telephony-class-application";

            // select the type of audio file you want returned
            $audioConfig = (new AudioConfig())
                ->setAudioEncoding(AudioEncoding::MP3)
                ->setPitch($pitch)
                ->setSpeakingRate($speking_rate)
                ->setEffectsProfileId(array($effectsProfileId));

            $response = $client->synthesizeSpeech($input_text, $voice, $audioConfig);
            $audioContent = $response->getAudioContent();
            $file = $uniqueId."_output.mp3";

            if(isset($request->prompt_for) && $request->prompt_for == 'conferencing'){
                $filePath = env('FILE_UPLOAD_PATH').env('CONFERENCE_FILE_UPLOAD_FOLDER_NAME')."/".$file;
            } 

            if($request->prompt_for == 'announcements')
            {
                $filePath = env('FILE_UPLOAD_PATH') . env('ANNOUNCEMENTS_FILE_UPLOAD_FOLDER_NAME') . "/" . $file;
            }

            else {
                $filePath = env('FILE_UPLOAD_PATH') . env('IVR_FILE_UPLOAD_FOLDER_NAME') . "/" . $file;
            }

            if(file_exists($filePath))
            {
                unlink($filePath);
            }
            file_put_contents($filePath, $audioContent);
            $client->close();
            return response()->json(['file' => $file]);
        }
        catch (\Throwable $e)
        {
            \Log::error("Failed to get audio on text", Helper::buildContext($e));
            echo "Failed to get audio on text";
            die;
        }
    }
}


