<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;


class LiveConferenceController extends Controller
{
  public function index(Request $request)
    {
        $live_conference = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "live-conference";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $live_conference = $response->data;
            } else {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("conferencing.live", compact("errors", $errors));
        }
        return view("conferencing.live", compact("live_conference", $live_conference));
    }
}

