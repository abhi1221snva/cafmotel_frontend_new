<?php

namespace App\Http\Controllers\III_Ranks;

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Session;

class StripeController extends Controller
{
    /**
    * Add Balance
    * @param Request $request
    * @return type
    */
    public function pay(Request $request) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
        );

        $url = env('API_URL') . 'stripe/charge';
        $response = Helper::PostApi($url, $body);
        return response()->json($response);
    }

    /**
    * Create Stripe Customer Payment Method
    * @param type $param
    */
    public function createStripeCustomerPaymentMethod(Request $request) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'full_name' => $request->full_name,
            'line1' => $request->line1,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'number' => $request->number,
            'exp_month' => $request->exp_month,
            'exp_year' => $request->exp_year,
            'cvc' => $request->cvc,
            'amount' => $request->amount,
        );

        $url = env('API_URL') . 'stripe/create-customer-payment-method';
        $response = Helper::PostApi($url, $body);
        return response()->json($response);
    }

    public function processCheckout(Request $request) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'full_name' => $request->full_name,
            'line1' => $request->line1,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'number' => $request->number,
            'exp_month' => $request->exp_month,
            'exp_year' => $request->exp_year,
            'cvc' => $request->cvc,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'request_type' => $request->request_type
        );

        $url = env('API_URL') . 'checkout';
        $response = Helper::PostApi($url, $body);
        return response()->json($response);
    }

    /**
    * Attach Stripe Customer Payment Method
    * @param type $param
    */
    public function attachCustomerAndPaymentMethod(Request $request) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'payment_method' => $request->payment_method
        );

        $url = env('API_URL') . 'stripe/attach-customer-and-payment-method';
        $response = Helper::PostApi($url, $body);
        return response()->json($response);
    }
}


