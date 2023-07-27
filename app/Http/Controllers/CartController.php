<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class CartController extends Controller
{
    public function getCartCount()
    {
        $errors = new MessageBag();
        $url = env('API_URL') . 'cart/count';
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

    public function getCartItems()
    {
        $cartItems = NULL;
        $errors = new MessageBag();
        $url = env('API_URL') . 'cart';

        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $cartItems = $response->data;
            } else {
                $errors->add("error", $response->message);
                return view("subscriptions.cart", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.cart", compact("errors", $errors));
        }
        return view("subscriptions.cart", compact("cartItems"));
    }

    public function addToCart(Request $request, string $packageName)
    {
        $errors = new MessageBag();
        $url = env('API_URL') . "/cart/add/$packageName";
        $body = [
            'billingPeriod' => $request->billingPeriod,
            'NoOfUsers' => $request->NoOfUsers
        ];

        try {
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                return [$response->message];
            } else {
                $errors->add("error", $response->message);
                return view("subscriptions.upgrade-plan", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.upgrade-plan", compact("errors", $errors));
        }
    }

    public function updateCart(Request $request, int $cartId)
    {
        $errors = new MessageBag();
        $url = env('API_URL') . "/cart/update/$cartId";
        $body = [
            'operation' => $request->operation
        ];

        try {
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                return [$response->message];
            } else {
                $errors->add("error", $response->message);
                return view("subscriptions.cart", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.cart", compact("errors", $errors));
        }
    }

    public function deleteCart(Request $request, int $cartId)
    {
        $errors = new MessageBag();
        $url = env('API_URL') . "/cart/delete/$cartId";

        try {
            $response = Helper::PostApi($url);
            if ($response->success) {
                return [$response->message];
            } else {
                $errors->add("error", $response->message);
                return view("subscriptions.cart", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.cart", compact("errors", $errors));
        }
    }

    public function getCartTotalAmount()
    {
        $errors = new MessageBag();
        $url = env('API_URL') . 'cart/total';
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
}
