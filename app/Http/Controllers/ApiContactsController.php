<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Support\MessageBag;

class ApiContactsController extends Controller
{

    public function getCompanyUsers()
    {
        $arrCompanyUser = NULL;
        $errors = new MessageBag();
        $url = env('API_URL') . 'company-users';

        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $arrCompanyUser = $response->data;
            } else {
                return ["Failed to get contacts", $errors];
            }
        } catch (\Throwable $ex) {
            return ["Failed to get contacts", $ex->getMessage()];
        }
        return $arrCompanyUser;
    }
}
