<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;


class RecordingConferenceController extends Controller
{
  public function index(Request $request)
    {
        $recording_conference = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "recording-conference";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $recording_conference = $response->data;
            } else {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("conferencing.recording", compact("errors", $errors));
        }
        return view("conferencing.recording", compact("recording_conference", $recording_conference));
    }
}

