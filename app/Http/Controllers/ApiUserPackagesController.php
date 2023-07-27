<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Session;

class ApiUserPackagesController extends Controller
{
    public function getUsersByClientId(Request $request)
    {
        $userPackages = $availablePackages = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "user-packages";
        $urlClientPackages = env('API_URL') . "client-packages";

        //get user packages info
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $userPackages = $response->data;
            } else {
                $userPackages = [];
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }

        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.user-packages", compact("errors", $errors));
        }

        //get packages availability
        try {
            $response = Helper::GetApi($urlClientPackages);
            if ($response->success) {
                $availablePackages = (array) $response->data;
            } else {
                $availablePackages = [];
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.user-packages", compact("errors", $errors));
        }


        return view("subscriptions.user-packages", compact("userPackages","availablePackages"));
    }

    public function updateUserPackage(Request $request, string $packageKey)
    {
        $url = env('API_URL') . "user-package/update/$packageKey";
        $body = [
            'user_id' => $request->user_id,
            'client_id' => $request->client_id
        ];
        try {
            $response = Helper::PostApi( $url, $body );

            if ($response->success) {
                Session::forget('userMenu');
                return [$response->message];
            } else {
                $errors = new MessageBag();
                $errors->add("error", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
        } catch (\Throwable $exception) {
            $errors = new MessageBag();
            $errors->add("error", $exception->getMessage());
            return $errors;
        }
    }

    public function deleteUserPackage(Request $request, string $packageKey)
    {
        $url = env('API_URL') . "user-package/delete/$packageKey";
        $body = [
            'user_id' => $request->user_id,
            'client_id' => $request->client_id
        ];
        try {
            $response = Helper::PostApi( $url, $body );
            if ($response->success) {
                Session::forget('userMenu');
                return [$response->message];
            } else {
                $errors = new MessageBag();
                $errors->add("error", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
        } catch (\Throwable $exception) {
            $errors = new MessageBag();
            $errors->add("error", $exception->getMessage());
            return $errors;
        }
    }

}
