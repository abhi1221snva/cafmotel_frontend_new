<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Session;

class MarketingCampaignScheduleController extends Controller
{
    public function index(Request $request, int $id)
    {
        /* did list */
        $did = [];
        $url = env('API_URL') . "did";
        $errors = new MessageBag();
        try {
            $response = Helper::PostApi($url);
            if ($response->success) {
                $did = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("marketing_campaign.list", compact("errors", $errors));
        }

        /* close did */

        /* smtps list */

        $smtp_setting = [];
        $url = env('API_URL') . "smtps";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $smtp_setting = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("marketing_campaign.list", compact("errors", $errors));
        }

        /* close smtps */

        /* email templates list */
        $email_templates = [];
        $url = env('API_URL') . "email-templates";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $email_templates = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("marketing_campaign.list", compact("errors", $errors));
        }

        /* close email templates */

        /* list list */

        $list = [];
        $url = env('API_URL') . "list";
        try {
            $response = Helper::PostApi($url);
            if ($response->success) {
                $list = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("marketing_campaign.list", compact("errors", $errors));

        }

        /* close list */
        /* marketing campaign */

        $marketing_campaign = null;
        $url = env('API_URL') . "marketing-campaign/$id";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $marketing_campaign = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("marketing_campaign.list", compact("errors", $errors));
        }

        /* close marketing campaign*/
        /* sms templates list */

        $sms_templates = [];
        $url = env('API_URL') . "sms-templete";

        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $sms_templates = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("marketing_campaign.list", compact("errors", $errors));
        }

        /* close sms templates */

        $schedules = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "marketing-campaigns-schedule/$id";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $schedules = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("marketing_campaign_schedule.list", compact("errors", $errors));
        }

        $listHeaders = [];
        foreach ($schedules as $schedule) {
            $listHeaders[$schedule->list_id] = $this->findListHeader($schedule->list_id, "data");
        }

        return view("marketing_campaign_schedule.list", compact("schedules", "marketing_campaign", "list", "email_templates", "smtp_setting", "did", "sms_templates", "listHeaders"));
    }

    public function showNew(Request $request)
    {
        $url = env('API_URL') . 'marketing-campaigns';
        $campaign = Helper::GetApi($url, [], true);
        $campaign = $campaign['data'];

        $url = env('API_URL') . 'list';
        $list = Helper::PostApi($url, [], true);
        $list = $list->data;
        $url = env('API_URL') . 'email-templates';
        $email_templates = Helper::GetApi($url, [], true);
        $email_templates = $email_templates['data'];
        $url = env('API_URL') . 'smtps';
        $smtp_setting = Helper::GetApi($url, [], true);
        $smtp_setting = $smtp_setting['data'];

        return view("marketing_campaign_schedule.add", compact('campaign', 'list', 'email_templates', 'smtp_setting'));

    }

    public function show(Request $request, int $id)
    {
        $marketing_campaign_schedule = null;
        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "marketing-campaign-schedule/$id";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"]) {
                $marketing_campaign_schedule = $response["data"];
            } else {
                foreach ($response["errors"] as $key => $message) {
                    $errors->add($key, $message);
                }
                return view("marketing_campaign_schedule.edit")->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("marketing_campaign_schedule.edit")->withErrors($errors);
        }

        $smtp_setting = [];
        $email_templates = [];
        $did = [];
        $sms_templates = [];
        if ($marketing_campaign_schedule["send"] == 1) {        //email
            $url = env('API_URL') . "smtps";
            try {
                $response = Helper::GetApi($url);
                if ($response->success) {
                    $smtp_setting = $response->data;
                } else {
                    foreach ($response->errors as $key => $message) {
                        $errors->add($key, $message);
                    }
                }
            } catch (RequestException $ex) {
                $errors->add("error", $ex->getMessage());
                return view("marketing_campaign.list", compact("errors", $errors));
            }
            /* close smtps */

            /* email templates list */
            $url = env('API_URL') . "email-templates";
            try {
                $response = Helper::GetApi($url);
                if ($response->success) {
                    $email_templates = $response->data;
                } else {
                    foreach ($response->errors as $key => $message) {
                        $errors->add($key, $message);
                    }
                }
            } catch (RequestException $ex) {
                $errors->add("error", $ex->getMessage());
                return view("marketing_campaign.list", compact("errors", $errors));
            }
            /* close email templates */

        } elseif ($marketing_campaign_schedule["send"] == 2) {  //sms
            /* did list */
            try {
                $url = env('API_URL') . "did";
                $errors = new MessageBag();
                $body = array(
                    'id' => Session::get('id')
                );
                $response = Helper::PostApi($url, $body);
                if ($response->success) {
                    $did = $response->data;
                } else {
                    foreach ($response->errors as $key => $message) {
                        $errors->add($key, $message);
                    }
                }
            } catch (RequestException $ex) {
                $errors->add("error", $ex->getMessage());
                return view("marketing_campaign.list", compact("errors", $errors));
            }

            /* sms templates list */
            $url = env('API_URL') . "sms-templete";
            try {
                $response = Helper::GetApi($url);
                if ($response->success) {
                    $sms_templates = $response->data;
                } else {
                    foreach ($response->errors as $key => $message) {
                        $errors->add($key, $message);
                    }
                }
            } catch (RequestException $ex) {
                $errors->add("error", $ex->getMessage());
                return view("marketing_campaign.list", compact("errors", $errors));
            }

            /* close sms templates */
        }

        /* list list */
        $list = [];
        $url = env('API_URL') . "list";
        try {
            $response = Helper::PostApi($url);
            if ($response->success) {
                $list = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("marketing_campaign.list", compact("errors", $errors));
        }

        $marketing_campaigns = [];
        $url = env('API_URL') . "marketing-campaigns";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $marketing_campaigns = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("marketing_campaign.list", compact("errors", $errors));
        }
        /* close marketing campaign*/

        return view("marketing_campaign_schedule.edit", compact('marketing_campaign_schedule', 'marketing_campaigns', 'list', 'email_templates', 'smtp_setting', 'did', 'sms_templates'));
    }

    function addMarketingScheduleSMS(Request $request)
    {

        $this->validate($request, [
            'campaign_id' => 'required|integer',
            'list_id' => 'required|integer',
            'list_column_name' => 'required',
            'send' => 'required',
            'sms_template_id' => 'required|integer',
            'sms_setting_id' => 'required|integer',
            'sms_country_code' => 'required|integer',
            'run_time' => 'required',
            'created_by' => 'required'
        ]);

        $url = env('API_URL') . "marketing-campaign-schedule-sms";
        $response = Helper::RequestApi($url, "PUT", $this->getBuildBodySMS($request), "json");
        return response()->json($response);

    }

    function addMarketingSchedule(Request $request)
    {

        $this->validate($request, [
            'campaign_id' => 'required',
            'list_id' => 'required',
            'list_column_name' => 'required',
            'send' => 'required',
            'email_template_id' => 'required',
            'email_setting_id' => 'required',
            'run_time' => 'required',
            'created_by' => 'required'

        ]);

        $url = env('API_URL') . "marketing-campaign-schedule";
        $response = Helper::RequestApi($url, "PUT", $this->getBuildBody($request), "json");
        return response()->json($response);
    }

    private function getBuildBodySMS(Request $request)
    {
        $body = [
            "campaign_id" => trim($request->get("campaign_id")),
            "list_id" => trim($request->get("list_id")),
            "list_column_name" => trim($request->get("list_column_name")),
            "run_time" => trim($request->get("run_date") . ' ' . $request->get('run_time')),
            "send" => trim($request->get("send")),
            "sms_template_id" => trim($request->get("sms_template_id")),
            "sms_setting_id" => trim($request->get("sms_setting_id")),
            "sms_country_code" => trim($request->get("sms_country_code")),
            "created_by" => trim($request->get("created_by")),
        ];

        return $body;
    }

    private function getBuildBody(Request $request)
    {
        $body = [
            "campaign_id" => trim($request->get("campaign_id")),
            "list_id" => trim($request->get("list_id")),
            "list_column_name" => trim($request->get("list_column_name")),
            "run_time" => trim($request->get("run_date") . ' ' . $request->get('run_time')),
            "send" => trim($request->get("send")),
            "email_template_id" => trim($request->get("email_template_id")),
            "email_setting_id" => trim($request->get("email_setting_id")),
            "created_by" => trim($request->get("created_by")),
        ];

        return $body;
    }

    public function update(Request $request, int $id)
    {
        $schedule_id = $request->schedular_id;
        if ($request->send == 1) {
            $this->validate($request, [
                'campaign_id' => 'required',
                'list_id' => 'required',
                'email_template_id' => 'required',
                'email_setting_id' => 'required',
                'run_time' => 'required'
            ]);
        } else
            if ($request->send == 2) {
                $this->validate($request, [
                    'campaign_id' => 'required|integer',
                    'list_id' => 'required|integer',
                    'sms_setting_id' => 'required|integer',
                    'sms_template_id' => 'required|integer',
                    'sms_country_code' => 'required|integer',
                    'run_time' => 'required'
                ]);
            }

        $errors = new MessageBag();
        try {
            $url = env('API_URL') . "marketing-campaign-schedule/$schedule_id";
            if ($request->send == 1) {
                $response = Helper::PostApi($url, $this->getBuildBody($request));
            } else
                if ($request->send == 2) {
                    $response = Helper::PostApi($url, $this->getBuildBodySMS($request));
                }
            if (!$response->success) {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors($errors);
        }

        session()->flash("success", "Campaign Schedule updated");
        return redirect()->back();
    }

    public function deleteSchedule(Request $request)
    {
        $this->validate($request, [
            "scheduleId" => "required|int"
        ]);

        $scheduleId = $request->input("scheduleId");
        $body = [
            "scheduleId" => intval($scheduleId)
        ];

        $url = env('API_URL') . "delete-schedule";
        $response = Helper::PostApi($url, $body, "json");
        return response()->json($response);
    }

    public function abortSchedule(Request $request)
    {
        $this->validate($request, [
            "scheduleId" => "required|int"
        ]);

        $scheduleId = $request->input("scheduleId");
        $body = [
            "scheduleId" => intval($scheduleId)
        ];

        $url = env('API_URL') . "abort-schedule";
        $response = Helper::PostApi($url, $body, "json");
        return response()->json($response);
    }

    public function findListHeader(int $listid, $responseType="json")
    {
        $body = [
            "listid" => $listid
        ];
        $url = env('API_URL') . "find-listheader";
        $response = Helper::PostApi($url, $body, "json");
        if ($responseType=="json") {
            return response()->json($response);
        } else {
            return (array)$response->data;
        }
    }

    public function retryLog(Request $request, int $campaignId, int $scheduleId, int $logId)
    {
        $url = env('API_URL') . "marketing-campaign-schedule-run/{$logId}/retry";
        $response = Helper::PostApi($url, [], "json");
        return response()->json($response);
    }

    public function getLogs(Request $request, int $campaignId, int $scheduleId)
    {
        $schedule = null;
        $errors = new MessageBag();
        $url = env('API_URL') . "marketing-campaign-schedule/$scheduleId";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $schedule = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("marketing_campaign_schedule.list", compact("errors", $errors));
        }

        $url = env('API_URL') . "marketing-campaign-schedule/$scheduleId/logs?source=web";
        $runStatus = $request->get("run_status", null);
        if ($runStatus) {
            $url .= "&status=$runStatus";
        }
        $sendTo = $request->get("send_to", null);
        if ($sendTo) {
            $countryCode = $request->get("country_code", "");
            $url .= "&to=$countryCode$sendTo";
        }
        $page = $request->get("page", null);
        if ($page) {
            $url .= "&page=$page";
        }
        $records = Helper::GetApi($url);

        return view('marketing_campaign_schedule.logs')->with([
            "schedule" => $schedule,
            "records" => $records
        ]);
    }
}

