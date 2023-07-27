<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class MarketingCampaignController extends Controller
{

    public function index(Request $request)
    {
        $marketing_campaigns = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "marketing-campaigns";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $marketing_campaigns = $response->data;
            } else {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("marketing_campaigns.list", compact("errors", $errors));
        }
        return view("marketing_campaigns.list", compact("marketing_campaigns", $marketing_campaigns));
    }

    public function showNew(Request $request)
    {
        $inherit_list = new InheritApiController;
        $label_list = $inherit_list->getLabel();
        $users = new User();
        $user_column = $users->getTableColumns();
        return view("email-template.add", compact('label_list', 'user_column'));
    }

    public function show(Request $request, int $id)
    {
        $inherit_list = new InheritApiController;
        $label_list = $inherit_list->getLabel();
        $users = new User();
        $user_column = $users->getTableColumns();
        $email_template = null;
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "email-template/$id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"]) {
                $email_template = $response["data"];
            } else {
                foreach ( $response["errors"] as $key => $message ) {
                    $errors->add($key, $message);
                }
                return view("email-template.edit")->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("email-template.edit")->withErrors($errors);
        }
        return view("email-template.edit")->with(["email_template" => $email_template, "label_list" => $label_list, "user_column" => $user_column]);
    }

    function addNew(Request $request)
    {
        $this->validate($request, [
            'template_name' => 'required|string|max:255',
            'template_html' => 'required|string',
            'subject'       => 'required|string'
        ]);
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "email-template";
            $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");
            if ($response->success) {
                session()->flash("success", "Template Added");
                return redirect("email-templates");
            } else {
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ( $messages as $index => $message )
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return redirect()->back()->withInput()->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput()->withErrors($errors);
        }
    }


    private function getBuildBody(Request $request)
    {
        $body = [
            "title" => trim($request->get("title")),
            "description" => trim($request->get("description"))

        ];

        return $body;
    }


    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'template_name' => 'required|string|max:255',
            'template_html' => 'required|string',
            'subject'       => 'required|string',


        ]);
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "email-template/$id";
            $response = Helper::PostApi($url, $this->getBuildBody($request));
            // echo "<pre>";print_r($response);die;
            if (!$response->success) {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }
        session()->flash("success", "Template updated");
        return redirect()->back();
    }
}

