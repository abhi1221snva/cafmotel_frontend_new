<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class ModulesController extends Controller
{
    public function index(Request $request)
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
                return view("modules.list", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("modules.list", compact("errors", $errors));
        }
        return view("modules.list", compact("modules", $modules));
    }

    public function showNew()
    {
        $components = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "components";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $components = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("packages.add", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("packages.add", compact("errors", $errors));
        }
        return view("modules.add", compact('components'));
    }

    public function addNew(Request $request)
    {
        $this->validate($request, [
            "name" => "required|string",
            "components" => "required|array",
            "attributes" => "required|array",
            "is_active" => "required|int",
            "display_order" => "required|int",
        ]);

        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "module";
            $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");
            if ($response->success) {
                session()->flash("success", "Module added");
                return redirect("super/modules");
            } else {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput()->withErrors($errors);
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput()->withErrors($errors);
        }
    }

    public function show(Request $request, string $id)
    {
        $components = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "components";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $components = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("modules.edit", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("modules.edit", compact("errors", $errors));
        }

        $module = null;
        try {
            $url = env('API_URL') . "module/$id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"]) {
                $module = $response["data"];
            } else {
                foreach ($response["errors"] as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("modules.edit")->withErrors($errors);
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("modules.edit")->withErrors($errors);
        }
        return view("modules.edit", compact('module', 'components'));
    }

    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            "name" => "required|string",
            "components" => "required|array",
            "attributes" => "required|array",
            "is_active" => "required|int",
            "display_order" => "required|int",
        ]);
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "module/$id";
            //echo "<pre>";print_r($this->getBuildBody($request));die;

            $response = Helper::PostApi($url, $this->getBuildBody($request));
            if (!$response->success) {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }
        session()->flash("success", "Module updated");
        return redirect('super/modules');
    }

    private function getBuildBody(Request $request)
    {
        $body = [
            "name" => trim($request->get("name")),
            "components" => $request->get("components"),
            "attributes" => $request->get("attributes"),
            "is_active" => $request->get("is_active"),
            "display_order" => $request->get("display_order"),
        ];

        return $body;
    }
}
