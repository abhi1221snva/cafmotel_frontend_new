<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Support\MessageBag;
use Session;

class WalletController extends Controller
{
    public function getWalletBalance()
    {
        $errors = new MessageBag();
        $url = env('API_URL') . 'wallet/balance';
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                return $response->data;
            } else {
                $errors->add("error", $response->message);
                return view("dashboard.dashboard", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("dashboard.dashboard", compact("errors", $errors));
        }
    }

    function getWalletTransactions()
    {

        $walletTransactions = NULL;
        $errors = new MessageBag();
        $url = env('API_URL') . 'wallet/transactions';
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $walletTransactions = $response->data;
            } else {
                $errors->add("error", $response->message);
                return view("billing.wallet-transactions", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("billing.wallet-transactions", compact("errors", $errors));
        }
        return view("billing.wallet-transactions", compact("walletTransactions"));
    }
}
