<?php


namespace App\Services;


use App\Helper\Helper;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;

class ClientService
{
    public static function getClientList()
    {
        $url = env('API_URL') . "clients";
        try {
            $clients = Helper::GetApi($url);
            if ($clients->success) {
                return $clients->data;
            } else {
                return $clients->error;
            }
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (extension): Oops something went wrong :( Please contact your administrator.)");
        }
    }
}
