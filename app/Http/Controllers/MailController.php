<?php
namespace App\Http\Controllers;
use App\Helper\Helper;
use App\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Session;

class MailController extends Controller
{
    public static $smtpTypes = [
        0 => "none",
        1 => "user",
        2 => "campaign",
        3 => "system"
    ];

    function email(Request $request)
    {
        try
        {
            $url = env('API_URL') . "send-email/generic";
            $response = Helper::PostApi($url, $this->getBuildBody($request));
            return response()->json($response);
        }
        catch (\Throwable $exception) {
            return [
                'success' => false,
                'message' => "Failed to send the email",
            ];
        }

    }

    private function getBuildBody(Request $request)
    {
        $name = strstr($request->get("from"), '@', true);
        $body = [
            "to" => trim($request->get("to")),
            "from" => array('address'=>trim($request->get("from")),'name'=>$name),
            "subject" => trim($request->get("subject")),
            "body" => trim($request->get("message")),
            "senderType" => self::$smtpTypes[$request->get("smtpType")],
            "user_id" => session("id")
        ];
        if ($request->has("campaign_id")) {
            $body["campaign_id"] = $request->get("campaign_id");
        }
        return $body;
    }


    function openMailModal(Request $request)
    {
        $errors = new MessageBag();
        $templates = [];
        $smtpType = self::$smtpTypes[$request->smtpType];
        $query = "";
        if ($smtpType==="none") {
            return response("Emailing is disabled", 400);
        }
        $message = "";
        if ($smtpType==="system") {
            if (session("level") < 7) {
                $message = "Please contact administrator to configure system email setting.";
            } else {
                $message = "Please add system email setting for sending emails. Click <a href='/smtp' target='_blank'>here</a> to add email setting.";
            }
        } elseif ($smtpType==="campaign") {
            $query = "?campaign_id=".$request->campaign_id;
            if (session("level") < 7) {
                $message = "Please contact administrator to configure campaign email setting.";
            } else {
                $message = "Please add campaign email setting for sending emails. Click <a href='/smtp' target='_blank'>here</a> to add email setting.";
            }
        }
        if ($smtpType==="user") {
            $query = "?user_id=".session("id");
            $message = "Please add email setting for sending emails. Click <a href='/smtp' target='_blank'>here</a> to add email setting.";
        }
        try {
            $url = env('API_URL') . "smtp/type/$smtpType".$query;
            $response = Helper::GetApi($url);
            if ($response->success) {
                if (count($response->data) == 0) {
                    return response($message, 400);
                }
                $templates['smtpSetting'] = $response->data[0];
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            return $errors->add("error", $ex->getMessage());
        }

        $inherit_list = new InheritApiController();
        $templates['labels'] = $inherit_list->getLabel();
        $users = new User();
        $templates['user_column'] = $users->getTableColumns();

        try {
            $url = env('API_URL') . "email-templates";
            $response = Helper::GetApi($url);
            if ($response->success) {
                $templates['email_templates'] = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            return $errors->add("error", $ex->getMessage());
        }

        try
        {
            $url = env('API_URL') . "custom-field-labels";
            $response = Helper::GetApi($url);
            if ($response->success)
            {
                $templates['custom_label_labels'] = $response->data;
            }
            else
            {
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }
        catch (RequestException $ex)
        {
            return $errors->add("error", $ex->getMessage());
        }
        
        return $templates;
    }

    public function getTemplate(Request $request, int $id, int $list_id, int $lead_id)
    {
        $email_template = null;
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "email-template/$id/$list_id/$lead_id";
            $response = Helper::GetApi($url, [], true);
            /*echo "<pre>";
            print_r($response);
            die();*/
            if ($response["success"]) {
                $email_template = $response["data"];

            } else {
                foreach ($response["errors"] as $key => $message) {
                    $errors->add($key, $message);
                }
                //return view("email-template.edit")->withErrors($errors);
            }
        } catch (RequestException $ex) {
            return $errors->add("error", $ex->getMessage());
            //view("email-template.edit")->withErrors($errors);
        }

        //echo "<pre>";print_r($email_template[0]);die;


        return $email_template;
    }

    public function getLabelValue(Request $request, int $id, int $list_id, int $lead_id)
    {
        $email_template = null;
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "label-data/$id/$list_id/$lead_id";
            $response = Helper::GetApi($url, [], true);
            // echo "<pre>";print_r($response);die;
        } catch (RequestException $ex) {
            return $errors->add("error", $ex->getMessage());
        }
        return $response[0];
    }

    public function getSenderValue(Request $request, string $id)
    {
        $user = User::findOrFail(Session::get('id'));
        $title = str_replace('[[', '', $id);
        $final_title = str_replace(']]', '', $title);
        // echo "<pre>";print_r($user);die;
        return $user[$final_title];
    }

    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
        
            $request->file('upload')->move(public_path('email-image'), $fileName);
   
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('email-image/'.$fileName); 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";  
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }



}

