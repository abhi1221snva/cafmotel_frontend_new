<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Session;

class EmailTempleteController extends Controller
{

    public function index(Request $request)
    {
        $email_templates = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "email-templates";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $email_templates = $response->data;
            } else {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("email-template.list", compact("errors", $errors));
        }
        return view("email-template.list", compact("email_templates", $email_templates));
    }

    public function showNew(Request $request)
    {
        $inherit_list = new InheritApiController;
        $label_list = $inherit_list->getLabel();
        $users = new User();
        $user_column = $users->getTableColumns();

        $custom_field_labels = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "custom-field-labels";
        try
        {
            $response = Helper::GetApi($url);
            if($response->success)
            {
                $custom_field_labels = $response->data;
            }
            else
            {
                $custom_field_labels = [];
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }
        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("email-template.add", compact("errors", $errors));
        }
        return view("email-template.add", compact('label_list', 'user_column','custom_field_labels'));
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

        $custom_field_labels = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "custom-field-labels";
        try
        {
            $response = Helper::GetApi($url);
            if($response->success)
            {
                $custom_field_labels = $response->data;
            }
            else
            {
                $custom_field_labels = [];
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }
        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("email-template.edit")->withErrors($errors);
        }
        return view("email-template.edit")->with(["email_template" => $email_template, "label_list" => $label_list, "user_column" => $user_column,'custom_field_labels'=>$custom_field_labels]);
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
            "template_name" => trim($request->get("template_name")),
            "template_html" => trim($request->get("template_html")),
            "subject" => trim($request->get("subject"))

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

    public function delete(int $id)
    {
        try {
            $url = env('API_URL') . "email-template/$id";
            $response = Helper::RequestApi($url, "DELETE");
            return response()->json($response);
        } catch (\Throwable $ex) {
            return response()->json([
                "success" => false,
                "message" => $ex->getMessage()
            ]);
        }
    }

    function deleteEmailTemplete($temp_id,$status){


  if($status == '0'){
    $status = 1;

  }

  else if($status == '1'){
    $status = 0;
  }
    $body=array(
      'id'          => Session::get('id'),
      'token'       => Session::get('tokenId'),
      'templete_id'     => $temp_id,
      'is_deleted'  => $status
    );

    //echo "<pre>";print_r($body);die;
    $url = env('API_URL').'delete-email-templete';

    /* $delete_sms_temp = Helper::PostApi($url,$body);

   echo "<pre>";print_r($delete_sms_temp);die;*/



    
    try
    {
      $delete_sms_temp = Helper::PostApi($url,$body);
      if($delete_sms_temp->success == 'true')
      {
         return back()->withSuccess($delete_sms_temp->message);
        
      }
      if($delete_sms_temp->success == 'false')
      {
    

         return back()->withSuccess($delete_sms_temp->message);
       
        //return back()->withSuccess($ext_group->message);
      }
    }
    catch (BadResponseException   $e) {
      return back()->with('message',"Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
    }
    catch (RequestException $ex){
      return back()->with('message',"Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
    }
  }
}

