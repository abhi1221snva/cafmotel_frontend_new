<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Mail\CronCallReportEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ApiCronSendReportController extends Controller
{
    public function sendCronEmailCalls(Request $request)
    {
        $action = $request->get("action", "view");
        $url = env('API_URL') . 'cron-email';
        $result_arr = Helper::GetApi($url);

        if ($action == "view") {
            return view('emails.croncallreportemail',compact('result_arr'));
        }

        $toEmail = session("emailId");
        $checkMail = Mail::to($toEmail)->send(new CronCallReportEmail($result_arr));
        echo "success";
    }

}


