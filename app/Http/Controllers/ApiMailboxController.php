<?php

namespace App\Http\Controllers;

use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;

class ApiMailboxController extends Controller {

    function getMailbox(Request $request) {

        $inherit_list = new InheritApiController;


        $url = env('API_URL') . 'extension';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'parent_id' => Session::get('parentId'),
                //'role' => 2
        );
        $extension_list = $inherit_list->getExtensionList();
        if (!is_array($extension_list)) {
            $extension_list = array();
        }
        if ($request->isMethod('get')) {
            $urlpage = $request->page;
            if (!empty($urlpage)) {
                $lower_limit = $urlpage * 10;
                $url = env('API_URL') . 'mailbox';
                $body = array(
                    'id' => Session::get('id'),
                    'token' => Session::get('tokenId'),
                    'extesnion' => Session::get('extension'),
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'lower_limit' => $lower_limit,
                    'upper_limit' => 10,
                );

                //echo "<pre>";print_r($body);die;

                try {
                    $mailbox = Helper::PostApi($url, $body);
                    if ($mailbox->success == 'true') {
                        $record_count = $mailbox->record_count;

                        if ($record_count == '0') {
                            return back()->withSuccess($mailbox->message);
                        }
                        $report = $mailbox->data;
                        return view('mailbox.mailbox', compact('extension_list', 'report', 'record_count', 'lower_limit'));
                    }

                    if ($mailbox->success == 'false') {
                        // return redirect('/');

                        return back()->withSuccess($mailbox->message);
                    }
                } catch (BadResponseException $e) {
                    return back()->with('message', "Error code - (mailbox): Oops something went wrong :( Please contact your administrator.)");
                } catch (RequestException $ex) {
                    return back()->with('message', "Error code - (mailbox): Oops something went wrong :( Please contact your administrator.)");
                }
            } else {

                if (empty(Session::get('tokenId'))) {
                    return redirect('/');
                }


                return view('mailbox.mailbox', compact('extension_list'));
            }
        }

        if ($request->isMethod('post')) {

            //echo "s";die;
            $lower_limit = 0;
            $url = env('API_URL') . 'mailbox';
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                //'parent_id' => Session::get('tokenId'),
                //'extension' => $request->extension,
                'extesnion' => Session::get('extension'),
                //'number' => $request->number,
                //'type'   =>$request->type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'lower_limit' => $lower_limit,
                'upper_limit' => 10,
            );
            //echo "<pre>";print_r($body);die;

            /* $mailbox = Helper::PostApi($url,$body);

              echo "<pre>";print_r($mailbox);die; */


            try {

                $mailbox = Helper::PostApi($url, $body);

//                echo "<pre>";print_r($cdr_report);die;


                if ($mailbox->success == 'true') {

                    $record_count = $mailbox->record_count;

                    if ($record_count == '0') {
                        return back()->withSuccess($mailbox->message);
                    }

                    $report = $mailbox->data;

                    //return back()->withSuccess($result->message);
                    return view('mailbox.mailbox', compact('report', 'record_count', 'lower_limit', 'extension_list'));
                }

                if ($mailbox->success == 'false') {
                    //return redirect('/');

                    return back()->withSuccess($mailbox->message);
                }
            } catch (BadResponseException $e) {
                return back()->with('message', "Error code - (mailbox): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                $message = "Page Not Found";

                return redirect('/');

                // return back()->withSuccess($message);
            }
        }
    }

    public function statusMailBox($status, $id) {

        /* echo $status;
          echo '<br>';
          echo $id;die; */

        if ($status == 1) {
            $status = 0;
        }

        $url = env('API_URL') . 'edit-mailbox';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'mailbox_id' => $id,
            'status' => $status,
        );

        //echo "<pre>";print_r($body);die;


        $mailbox = Helper::PostApi($url, $body);

        //echo "<pre>";print_r($mailbox);die;
    }


    


    public function deleteAll(Request $request)
    {
        $this->validate($request, [
            "ids" => "required|string"
        ]);

        $ids = $request->ids;
        $body = [
            "mailbox_id" => $ids
        ];
        $url = env('API_URL') . "delete-mailbox";
        $response = Helper::PostApi($url, $body, "json");
        return response()->json($response);
    }

    function deleteMailbox($delete_id)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'mailbox_id' => $delete_id,
        );

        $url = env('API_URL') . 'delete-mailbox';
        $mailbox = Helper::PostApi($url, $body);
        try
        {
            $mailbox = Helper::PostApi($url, $body);
            if ($mailbox->success == 'true')
            {
                return back()->withSuccess($mailbox->message);
            }
            if ($mailbox->success == 'false')
            {
                return back()->withSuccess($mailbox->message);
            }
        }
        catch (BadResponseException $e)
        {
            return back()->with('message', "Error code - (delete-mailbox): Oops something went wrong :( Please contact your administrator.)");
        }
        catch (RequestException $ex)
        {
            return back()->with('message', "Error code - (delete-mailbox): Oops something went wrong :( Please contact your administrator.)");
        }
    }

}
