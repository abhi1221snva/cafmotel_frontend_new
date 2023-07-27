<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Support\Facades\Session;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $orders = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "orders";

        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $orders = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("billing.invoices", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("billing.invoices", compact("errors", $errors));
        }
        return view("billing.invoices", compact('orders'));
    }

    public function show(Request $request, int $orderId)
    {
        $arrOrderData = $arrProspectDetails = $arrUser = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "order/$orderId";

        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $arrOrderData = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("billing.invoice", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("billing.invoice", compact("errors", $errors));
        }


        //get user details
        $url = env('API_URL') . 'user-detail';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'parentId' => Session::get('parentId')
        );

        try {
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                $arrUser = (array)$response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("billing.invoice", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("billing.invoice", compact("errors", $errors));
        }

        if (empty($arrUser)) {
            $arrProspectDetails['name'] = env('INVOICE_COMPANY_NAME');
            $arrProspectDetails['address1'] = env('INVOICE_COMPANY_ADDRESS1');
            $arrProspectDetails['address2'] = env('INVOICE_COMPANY_ADDRESS2');
            $arrProspectDetails['phone'] = env('INVOICE_COMPANY_PHONE');
            $arrProspectDetails['email'] = env('INVOICE_COMPANY_EMAIL');
        } else {
            $arrProspectDetails['name'] = $arrUser['first_name'] . " " . $arrUser['last_name'];
            $arrProspectDetails['address1'] = $arrUser['address_1'] ?: '--';
            $arrProspectDetails['address2'] = ($arrUser['address_2']) ?: '--';
            $strCountryCode = $arrUser['country_code'] ? "+".$arrUser['country_code'] : "";
            $arrProspectDetails['phone'] =  $strCountryCode . " " . $arrUser['mobile'] ?: '--';
            $arrProspectDetails['email'] = $arrUser['email'];
        }

        return view("billing.invoice", compact('arrOrderData', 'arrProspectDetails'));
    }

    public function generatePdf(Request $request, int $orderId)
    {
        $arrOrderData = $arrProspectDetails = $arrUser = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "order/$orderId";

        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $arrOrderData = $response->data;

            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("billing.invoice-pdf", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("billing.invoice-pdf", compact("errors", $errors));
        }

        //get user details
        $url = env('API_URL') . 'user-detail';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'parentId' => Session::get('parentId')
        );

        try {
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                $arrUser = (array)$response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("billing.invoice-pdf", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("billing.invoice-pdf", compact("errors", $errors));
        }

        if (empty($arrUser)) {
            $arrProspectDetails['name'] = env('INVOICE_COMPANY_NAME');
            $arrProspectDetails['address1'] = env('INVOICE_COMPANY_ADDRESS1');
            $arrProspectDetails['address2'] = env('INVOICE_COMPANY_ADDRESS2');
            $arrProspectDetails['phone'] = env('INVOICE_COMPANY_PHONE');
            $arrProspectDetails['email'] = env('INVOICE_COMPANY_EMAIL');
        } else {
            $arrProspectDetails['name'] = $arrUser['first_name'] . " " . $arrUser['last_name'];
            $arrProspectDetails['address1'] = $arrUser['address_1'] ?: '--';
            $arrProspectDetails['address2'] = ($arrUser['address_2']) ?: '--';
            $strCountryCode = $arrUser['country_code'] ? "+".$arrUser['country_code'] : "";
            $arrProspectDetails['phone'] =  $strCountryCode . " " . $arrUser['mobile'] ?: '--';
            $arrProspectDetails['email'] = $arrUser['email'];
        }

        return view("billing.invoice-pdf", compact('arrOrderData', 'arrProspectDetails'));
    }
}
