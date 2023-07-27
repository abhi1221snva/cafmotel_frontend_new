<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class ModuleComponentController extends Controller
{
    public function index(Request $request)
    {
        $modules = [];
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "parent-menu";
            $response = Helper::GetApi($url);
            if ($response->success) {
                $modules = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("module-component.list", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("module-component.list", compact("errors", $errors));
        }

        $sub_menu = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "sub-menu";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $sub_menu = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("module-component.list", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("module-component.list", compact("errors", $errors));
        }

        return view("module-component.list", compact("modules", "sub_menu"));
    }

    public function add()
    {
        $modules = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "modules";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $modules = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("packages.add", compact("errors", $errors));
        }
        return view("packages.add", compact('modules'));
    }

    public function edit(Request $request, string $id)
    {
        $modules = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "modules";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $modules = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("packages.edit", compact("errors", $errors));
        }

        $package = null;
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "package/$id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"]) {
                $package = $response["data"];
            } else {
                foreach ($response["errors"] as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("packages.edit")->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("packages.edit")->withErrors($errors);
        }
        return view("packages.edit", compact('package', 'modules'));
    }

    public function copy(Request $request, string $id)
    {
        $modules = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "modules";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $modules = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("packages.copy", compact("errors", $errors));
        }

        $package = null;
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "package/$id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"]) {
                $package = $response["data"];
            } else {
                foreach ($response["errors"] as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("packages.copy")->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("packages.copy")->withErrors($errors);
        }
        return view("packages.copy", compact('package', 'modules'));
    }

    public function activePlans(Request $request)
    {
        $errors = new MessageBag();
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
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
        }

        /* close subscription list */
        $active_plans = [];
        $url = env('API_URL') . "active-client-plans";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $active_plans = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.active-plans", compact("errors", $errors));
        }
        return view("subscriptions.active-plans", compact("active_plans", "packages"));
    }

    public function planHistory(Request $request)
    {
        /* subscription list */
        $errors = new MessageBag();
        $url = env('API_URL') . 'packages';
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $packages = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
        }
        /* close subscription list */

        $plan_history = [];
        $url = env('API_URL') . "history-client-plans";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $plan_history = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.plan-history", compact("errors", $errors));
        }
        return view("subscriptions.plan-history", compact("plan_history", "packages"));
    }
}
