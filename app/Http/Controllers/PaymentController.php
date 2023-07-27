<?php

namespace App\Http\Controllers\III_Ranks;

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Session;

class PaymentController extends Controller
{

    /**
    * Checkout
    * @param Request $request
    * @return type
    */
    public function checkout(Request $request) {
        $cartTotal = 0;
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId')
        );

        $url = env('API_URL') . 'stripe/get-customer-id';
        Helper::GetApi($url, $body);

        $paymentMethods = $this->getPaymentMethods();

        $objCartController = new CartController();
        $intCartTotal = $objCartController->getCartTotalAmount();

        return view('billing.checkout', ["paymentMethods" => $paymentMethods,"intCartTotal" => reset($intCartTotal)]);

    }

    /**
    * Add Balance
    * @param Request $request
    * @return type
    */
    public function recharge(Request $request) {
        $paymentMethods = $this->getPaymentMethods();
        return view('billing.recharge', ["paymentMethods" => $paymentMethods]);
    }

    /**
    * Get Payment Method
    * @param Request $request
    * @return type
    */
    public function getPaymentMethods() {
        $paymentMethods = [];
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId')
        );
        $url = env('API_URL') . 'stripe/get-customer-payment-method';
        $response = Helper::GetApi($url, $body);

        if ($response->success) {
            if($response->data[0] == NULL){
                return [];
            }
            $paymentMethods = (array)$response->data[0]->data;
        }
        return $paymentMethods;
    }

    /**
     * SHow payment method / card lists
     * @return type
     */
    public function showPaymentMethodList() {
        $paymentMethods = $this->getPaymentMethods();
        return view('billing.payment-method-list', ["paymentMethods" => $paymentMethods]);
    }

    /**
     * Delete payment method / card lists
     * @return type
     */
    public function deletePaymentMethod($id) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'payment_method_id' => $id
        );

        $url = env('API_URL') . 'stripe/delete-stripe-payment_method';
        $response = Helper::PostApi($url, $body);
        return response()->json($response);
    }

    /**
    * Add pay method
    * @param Request $request
    * @return type
    */
    public function editPaymentMethod() {
        return view('billing.add-payment-method', []);
    }

    /**
    * Add Card
    * @param Request $request
    * @return type
    */
    public function saveCard(Request $request) {
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
        );

        $url = env('API_URL') . 'stripe/save-card';
        $response = Helper::PostApi($url, $body);
        return response()->json($response);
    }

    /**
    * update pay method
    * @param Request $request
    * @return type
    */
    public function updatePaymentMethod($id) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'payment_method_id' => $id
        );

        $url = env('API_URL') . 'stripe/get-payment-method';
        $response = Helper::PostApi($url, $body);

        if($response->success == 'true') {
            $pmDetails = $response->data[0];
            return view('billing.update-payment-method', ['pmDetails' => $pmDetails]);
        }
        return redirect()->to('payment-method-list');
    }

    /**
    * Update Card
    * @param Request $request
    * @return type
    */
    public function updateCard(Request $request) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'payment_method_id' => $request->payment_method_id,
            'full_name' => $request->full_name,
            'line1' => $request->line1,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'exp_month' => $request->exp_month,
            'exp_year' => $request->exp_year
        );

        $url = env('API_URL') . 'stripe/update-card';
        $response = Helper::PostApi($url, $body);
        return response()->json($response);
    }
}


