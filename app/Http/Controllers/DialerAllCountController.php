<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Session;
use PDF;


class DialerAllCountController extends Controller
{

    public function list(Request $request)
    {

        $clients = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "clients";
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $clients = $response->data;
            } else {
                $clients = [];
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("cdr_report.v1", compact("errors", $errors));
        }

        //echo  "<pre>";print_r($clients);die;


        $count_all = [];
        $errors = new MessageBag();

        try
        {
            $url = env('API_URL') . "dialer-all-count";
            if ($request->isMethod('POST'))
            {
                $body = array(
                    'level' => Session::get('level'),
                    'parentId' => Session::get('parentId'),
                    'id' => Session::get('id'),
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'client_id' => $request->client_id
                );
            }

            else
            {
                $current_date = date("Y-m-d"); 
                $str_date = date("Y-m-d");//, strtotime(" -1 day"));

                $body = array(
                    'level' => Session::get('level'),
                    'parentId' => Session::get('parentId'),
                    'id' => Session::get('id'),
                    'start_date' => $str_date,
                    'end_date' => $current_date,
                    'client_id' => Session::get('parentId')
                    
                );
            }
          // echo "<pre>";print_r($body);die;


            $response = Helper::PostApi($url,$body);
           // echo "<pre>";print_r($response);die;
            if ($response->success){
                $email_templates = $response->data;
            } else {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("cdr_report.v1", compact("errors", $errors));
        }

        if ($request->submit_download == '1') {
            $fileName = 'count-list-report-' . date('Y-m-d') . '.pdf';
            $pdf = PDF::loadView('cdr_report.count_list_pdf', compact("email_templates", "clients"))
                ->setPaper('a4', 'landscape');
            return $pdf->download($fileName);
        } else {
            return view("cdr_report.v1", compact("email_templates", "clients"));
        }
        
    }

    
}

