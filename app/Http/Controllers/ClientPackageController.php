<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use DateTime;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Session;

class ClientPackageController extends Controller
{
    public function activePlans(Request $request)
    {

        $active_plans = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "active-client-plans";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $active_plans = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("subscriptions.active-plans", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.active-plans", compact("errors", $errors));
        }

    //echo "<pre>";print_r($active_plans);die;
        return view("subscriptions.active-plans", compact("active_plans"));
    }

    public function planHistory(Request $request)
    {
        $errors = new MessageBag();
        /* Client list */
        $url = env('API_URL') . 'clients';
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $clients = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("subscriptions.plan-history", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.plan-history", compact("errors", $errors));
        }

        /* close Client list */

        /* subscription list */
        $url = env('API_URL') . 'packages';
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $packages = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("subscriptions.plan-history", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.plan-history", compact("errors", $errors));
        }

        /* close subscription list */

        $plan_history = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "history-client-plans";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $plan_history = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("subscriptions.plan-history", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.plan-history", compact("errors", $errors));
        }
        return view("subscriptions.plan-history", compact("plan_history", "packages", "clients"));
    }

    public function upgradePlan(Request $request)
    {
        $errors = new MessageBag();
        $arrPackagesDetails = $arrTrialPackageDetails = [];

        /* subscription list */
        $url = env('API_URL') . 'packages';
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $arrPackagesDetails = (array)$response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
        }

        //get trial package details
        $strTrialPackageDetailsUrl = env('API_URL') . "client-packages/trial";

        try {
            $TrialPackageDetailsResponse = Helper::GetApi($strTrialPackageDetailsUrl);
            if ($TrialPackageDetailsResponse->success){
                $arrTrialPackageDetails = $TrialPackageDetailsResponse->data;
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.upgrade-plan", compact("errors", $errors));
        }

        return view("subscriptions.upgrade-plan", compact("arrPackagesDetails","arrTrialPackageDetails"));
    }

    /**
     * @param $arrDataToRekey
     * @param $key
     * @return array
     */
    public static function rekeyArray( $arrDataToRekey, $key ): array
    {
        if( empty( $arrDataToRekey ) ) return [];

        $arrDataToReturn = [];
        foreach ($arrDataToRekey as $arrSingleData )
        {
            $arrDataToReturn[$arrSingleData->$key] = $arrSingleData;
        }
        return $arrDataToReturn;
    }
}
