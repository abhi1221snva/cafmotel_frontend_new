<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\InheritApiController;
use Session;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\PusherController;

class ApiFaxController extends Controller {

    public function getFaxPdf(Request $request, int $id){
        //echo $id;die;
        $fax = null;
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "fax/$id";
            $response = Helper::PostApi($url);
            if ($response->success) {
                $fax = (array)$response->data;
                $faxurl = $fax['faxurl'];
            } else {
                $errors->add("message", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ( $messages as $index => $message )
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return view("fax.fax_pdf")->withErrors($errors);
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("fax.fax")->withErrors($errors);
        }
        return view("fax.fax_pdf")->with(["faxurl" => $faxurl
        ]);


    }

    /*function getFax() {
        $inherit_list = new InheritApiController;
        $fax_list = $inherit_list->getFaxList();
        if (!is_array($fax_list)) {
            $fax_list = array();
        }
        if (empty($fax_list)) {
            if (empty(Session::get('tokenId'))) {
                return redirect('/');
            }
        }
        //return view('fax.fax', compact('fax_list'));
        return view('fax.fax', [
            'fax_list' => $fax_list]);
    }*/

     public function getFax(Request $request)
    {
        $sentFax = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "fax";
        try {
            $response = Helper::PostApi($url);
            if ($response->success) {
                $sentFax = $response->data;
            } else {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("fax.fax", compact("errors", $errors));
        }
        return view("fax.fax", compact("sentFax", $sentFax));
    }

    function receiverFax(Request $request) {
        try {
            $url = env('API_URL') . 'receiver-fax';
            $body = [
                'faxurl' => $request->faxurl,
                'callid' => $request->callid,
                'dialednumber' => $request->dialednumber,
                'callerid' => $request->callerid,
                'faxstatus' => $request->faxstatus,
                'numofpages' => $request->numofpages,
                'received' => $request->received
            ];
            $response = Helper::PostApi($url, $body);
            /*****Send pusher notifcaiton to user*****/
            $pusherObj = new PusherController();
            $pusherObj->sendPusherNotifcation($request->callerid, $request->dialednumber, 'fax');
            /*****Send pusher notifcaiton to user*****/
            return response()->json($response);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => "Failed to save incoming fax",
                'count' => null
            ];
        }
    }

    function addFax() {
        $inherit_list = new InheritApiController;
        $group = $inherit_list->getUserFaxDidList();
        //print_r($group);exit;
        return view('fax.add_fax', compact('group'));
    }

    function saveFax(Request $request) {
        $this->validate($request, [
            'from_id' => 'required',
            'to_id' => 'required|min:10',
            'pdf_file' => 'required|max:10000|mimes:pdf' //a required, max 10000kb, doc or docx file
        ]);

        $file = $request->file('pdf_file');
        $fileExtension = $file->getClientOriginalExtension();
        $file_size = $file->getSize();
        $file_size_mb = number_format($file_size / 1048576, 2);

        // if (strtolower($fileExtension) === 'pdf' || $file_size_mb <= 10) {
        $filename = time() . '.' . $fileExtension;
        $faxFolderPath = base_path() . "/public/fax/";
        // upload fax url 
        $file->move($faxFolderPath, $filename);
        $fax_url = url('/fax/' . $filename);

        // Save fax in db
        try {
            $url = env('API_URL') . 'send-fax';
            $body = [
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'faxurl' => $fax_url,
                'callid' => $request->from_id,
                'dialednumber' => str_replace(array("(", ")", "-", " "), "", $request->to_id)
            ];
			//echo $url ; print_r($body);exit;
            $result = Helper::PostApi($url, $body);
            if ($result->success) {
                return back()->withSuccess($result->message);
            } else {
                return back()->withErrors($result->message);
            }
            return back()->withSuccess($result->message);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => "Failed to save send fax",
                'count' => null
            ];
        }
        //}
    }


    public function sendFaxGet(Request $request)
    {
        
       /* $this->validate($request, [
            'from_id' => 'required',
            'to_id' => 'required|min:10',
            'pdf_file' => 'required|max:10000|mimes:pdf' //a required, max 10000kb, doc or docx file
        ]);
*/

        //dd($request->all());

        ///echo "<pre>s";print_r($request->all());die;

        $file = $request->file('pdf_file');
        $fileExtension = $file->getClientOriginalExtension();
        $file_size = $file->getSize();
        $file_size_mb = number_format($file_size / 1048576, 2);

        // if (strtolower($fileExtension) === 'pdf' || $file_size_mb <= 10) {
        $filename = time() . '.' . $fileExtension;
        $faxFolderPath = base_path() . "/public/fax/";
        // upload fax url 
        $file->move($faxFolderPath, $filename);
        $fax_url = url('/fax/' . $filename);

        // Save fax in db
        try {
            $url = env('API_URL') . 'send-fax';
            $body = [
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'faxurl' => $fax_url,
                'callid' => $request->from_id,
                'dialednumber' => str_replace(array("(", ")", "-", " "), "", $request->to_id)
            ];
            //echo $url ; print_r($body);exit;
            $result = Helper::PostApi($url, $body);

           
       
            return response()->json($result);                  
           
            
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (send-sms): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (send-sms): Oops something went wrong :( Please contact your administrator.)");
        }
    }


    public function receiveFax(Request $request)
    {
        $receiveFax = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "receive-fax-list";
        try {
            $response = Helper::PostApi($url);
            if ($response->success) {
                $receiveFax = $response->data;
            } else {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("fax.receive_fax", compact("errors", $errors));
        }
        return view("fax.receive_fax", compact("receiveFax", $receiveFax));
    }

    /*public function receiveFax() {
        $url = env('API_URL') . 'receive-fax-list';
        $body = [
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'fax_type' => 0,
            'faxstatus' => 1
        ];
        $response = Helper::PostApi($url, $body);
        $user_data = [
            'fax_list' => $response->data, 'title_1' => 'Inbox'];
        return view('fax.receive_fax', compact('user_data'));
    }
    */

    public function sendingFailedFax() {
        $url = env('API_URL') . 'receive-fax-list';
        $body = [
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'fax_type' => 0,
            'faxstatus' => 0
        ];
        $response = Helper::PostApi($url, $body);
        $user_data = [
            'fax_list' => $response->data, 'title_1' => 'Sending Failed'];
        return view('fax.receive_fax', compact('user_data'));
    }
	
	public function receiverFaxRing(Request $request){		
        $hostname = env('FAX_BASE_PATH_URL');
		$responseText = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Response><fax action=\"$hostname\" method=\"post\" /></Response>";
		return response($responseText)->header("content-type", "text/xml");
	}

}
