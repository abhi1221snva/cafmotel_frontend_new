<?php
namespace App\Http\Controllers;
use App\Helper\Helper;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Session;

class ApiListController extends Controller
{

    public function callLead(Request $request)
    {
        $this->validate($request, [
            "number" => "required|int",
            "extension" => "required|int"
        ]);

        $number = $request->number;
        $extension = $request->extension;

        $body = array(
            'number' => $number,
            'extension' => Session::get('extension')
        );

        $url = env('API_URL') . 'call-lead';
        $response = Helper::PostApi($url, $body, "json");
        return response()->json(trim($response));
    }

    public function liveCallActivity(Request $request)
    {
        $this->validate($request, [
            "number" => "required|int",
            "extension" => "required|int"
        ]);

        $number = $request->number;
        $extension = $request->extension;

        $body = array(
            'number' => $number,
            'extension' => Session::get('extension'),
            'parent_id' => Session::get('parentId')
        );

        $url = env('API_URL') . 'live-call-activity';
        $response = Helper::PostApi($url, $body, "json");
        return response()->json($response);
    }

    public function getListList()
    {
        $inherit_list = new InheritApiController;
        $campaign_list = $inherit_list->getCampaign();
        if (!is_array($campaign_list)) {
            $campaign_list = array();
        }
        $list_details = $inherit_list->getListList();
        if (!is_array($list_details)) {
            $list_details = array();
        }
        if (empty($list_details)) {
            if (empty(Session::get('tokenId'))) {
                return redirect('/');
            }
        }
        return view('lists.lists', compact('list_details', 'campaign_list'));
    }

    public function storeList(Request $request)
    {
        $errors = new MessageBag();
        if (!empty($request->dnc)) {
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'number' => $request->number,
                'extension' => $request->extension,
                'comment' => $request->comment,
            );
            $url = env('API_URL') . 'edit-dnc';
            try {
                $add_dnc = Helper::PostApi($url, $body);
                if ($add_dnc->success == 'true') {
                    return back()->withSuccess($add_dnc->message);
                }

                if ($add_dnc->success == 'false') {
                    return back()->withSuccess($add_dnc->message);
                }
            } catch (\Throwable $e) {
                Log::error("Failed to edit DNC in ApiListController.storeList", [
                    "message" => $e->getMessage(),
                    "line" => $e->getLine(),
                    "file" => $e->getFile(),
                    "code" => $e->getCode(),
                ]);
                $errors->add("error", "Error code - (edit-dnc): Oops something went wrong (Please contact your administrator). " . $e->getMessage());
                return redirect()->back()->withInput()->withErrors($errors);
            }
        } else {
            $file = $request->file('list_file');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = Session::get('id') . time() . '.' . $extension;
            $rootPath = env("LIST_FILE_UPLOAD_PATH", "/var/www/html/api/upload/");
            $file->move($rootPath, $filename);
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'title' => $request->title,
                'campaign' => intval($request->campaign),
                'file' => $filename,
            );
            $url = env('API_URL') . 'add-list';
            try {
                $add_list = Helper::PostApi($url, $body);
                if ($add_list->success == 'true') {
                    $list_id = $add_list->list_id;
                    $campaign_id = $add_list->campaign_id;
                    return redirect('/editList/' . $list_id . '/' . $campaign_id);
                } else {
                    $errors->add("error", $add_list->message);
                    return redirect()->back()->withInput()->withErrors($errors);
                }
            } catch (\Throwable $e) {
                Log::error("Failed to add list in ApiListController.storeList", [
                    "message" => $e->getMessage(),
                    "line" => $e->getLine(),
                    "file" => $e->getFile(),
                    "code" => $e->getCode(),
                ]);
                $errors->add("error", "Error code - (add-list): Oops something went wrong (Please contact your administrator). " . $e->getMessage());
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }
    }

    public function editList($list_id, $campaign_id, Request $request)
    {

        $inherit_label = new InheritApiController;
        $label = $inherit_label->getLabel();

        $inherit_list = new InheritApiController;
        $campaign_list = $inherit_list->getCampaign();

        //  echo "<pre>";print_r($label_list);die;

        if ($request->isMethod('post')) {

            $is_dialing = $request->is_dialing;

            $size = sizeof($request->id);

            if (!empty($request->id)) {
                $id = $request->id;
                if (!empty(sizeof($id))) {
                    for ($i = 0; $i < $size; $i++) {
                        if (!empty($request->is_dialing)) {
                            $is_dialing = $request->is_dialing;
                            if ($is_dialing == $id[$i]) {
                                $edit_list[$i]['is_dialing'] = 1;
                            } else {
                                $edit_list[$i]['is_dialing'] = 0;
                            }
                            $edit_list[$i]['column_name'] = $request->column_name[$i];
                        }
                        $edit_list[$i]['id'] = $id[$i];
                    }
                }
            }
            if (!empty($request->is_search)) {
                $is_search = $request->is_search;
                for ($i = 0; $i < $size; $i++) {
                    if (empty($is_search[$i])) {
                        $edit_list[$i]['is_search'] = 0;
                    } else {
                        $edit_list[$i]['is_search'] = $is_search[$i];
                    }
                }
            }
            if (!empty($request->label_id)) {
                $label_id = $request->label_id;
                //echo sizeof($is_search);die;
                for ($i = 0; $i < $size; $i++) {

                    if (empty($label_id[$i])) {
                        $edit_list[$i]['label_id'] = 0;
                    } else {
                        $edit_list[$i]['label_id'] = $label_id[$i];
                    }
                }
            }
            if (!empty($request->is_visible)) {
                $is_visible = $request->is_visible;
                for ($i = 0; $i < $size; $i++) {
                    if (empty($is_visible[$i])) {
                        $edit_list[$i]['is_visible'] = 0;
                    } else {
                        $edit_list[$i]['is_visible'] = $is_visible[$i];
                    }
                }
            }
            if (!empty($request->is_editable)) {
                $is_editable = $request->is_editable;
                for ($i = 0; $i < $size; $i++) {
                    if (empty($is_editable[$i])) {
                        $edit_list[$i]['is_editable'] = 0;
                    } else {
                        $edit_list[$i]['is_editable'] = $is_editable[$i];
                    }
                }
            }

            if (!empty($request->alternate_phone)) {
                $alternate_phone = $request->alternate_phone;
                for ($i = 0; $i < $size; $i++) {
                    if (!empty($request->alternate_phone)) {
                        $alternate_phone = $request->alternate_phone;
                        if ($alternate_phone == $id[$i]) {
                            $edit_list[$i]['alternate_phone'] = 1;
                        } else {
                            $edit_list[$i]['alternate_phone'] = 0;
                        }
                    }
                }
            }

            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'title' => $request->title,
                'campaign_id' => $request->campaign_id,
                'list_id' => $request->list_id,
                'new_campaign_id' => $request->new_campaign_id,
                'list_header' => $edit_list,

            );
            $url = env('API_URL') . 'edit-list';
            try {
                $list_data = Helper::PostApi($url, $body);
                if ($list_data->success == 'true') {
                    return redirect('/editList/' . $request->list_id . '/' . $request->new_campaign_id)->withSuccess($list_data->message);
                }
                if ($list_data->success == 'false') {
                    return back()->withSuccess($list_data->message);
                }
            } catch (BadResponseException $e) {

                return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
            }
        } else if ($request->isMethod('get')) {
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'campaign_id' => $campaign_id,
                'list_id' => $list_id,
            );
            $url = env('API_URL') . 'list';
            try {
                $list_data = Helper::PostApi($url, $body);
                if ($list_data->success == 'true') {
                    $lists = $list_data->data;
                    return view('lists.configuration-list', compact('lists', 'label', 'campaign_list'));
                }
                if ($list_data->success == 'false') {
                    return redirect('/');
                }
            } catch (BadResponseException $e) {
                return back()->with('message', "Error code - (list): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                return back()->with('message', "Error code - (list): Oops something went wrong :( Please contact your administrator.)");
            }
        }
    }

    public function deleteListData($list_id = "", $campaign_id = "")
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'list_id' => $list_id,
            'campaign_id' => $campaign_id,
            'is_deleted' => 1,
        );

        $url = env('API_URL') . 'edit-list';
        //$recycle_list = Helper::PostApi($url,$body);
        //echo "<pre>";print_r($recycle_list);die;

        try {
            $delete_list = Helper::PostApi($url, $body);
            //echo "<pre>";print_r($recycle_list);die;
            if ($delete_list->success == 'true') {
                return $delete_list->message;
            }
            if ($delete_list->success == 'false') {
                //return redirect('/');
                return $delete_list->message;
            }
        } catch (BadResponseException $e) {

            return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public function searchListHeader($list_data, Request $request)
    {

        $list = explode(',', $list_data);
        //echo "<pre>";print_r($list);die;

        $url = env('API_URL') . 'list-header';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'list_data' => $list,

        );

        /* $lists_header = Helper::PostApi($url,$body);

        echo "<pre>";print_r($lists_header);die;*/
        try {
            $lists_header = Helper::PostApi($url, $body);
            if ($lists_header->success == 'true') {
                return $lists_header->data;
            }
            if ($lists_header->success == 'false') {
                return 0; //$lists_header->message;
            }
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (lists_header): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            $message = "Page Not Found";
            return back()->with('message', "Error code - (lists_header): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public function searchLeads()
    {

        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }

        $inherit_list = new InheritApiController;

        $getListHeader = array(); //$inherit_list->getListHeader();

        if (!is_array($getListHeader)) {
            $getListHeader = array();
        }

        $list_details = $inherit_list->getListList();

        if (!is_array($list_details)) {
            $list_details = array();
        }
        return view('lists.leads', compact('list_details', 'getListHeader'));
    }

    public function searchLeadColumn(Request $request)
    {

        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }

        $inherit_list = new InheritApiController;

        $list_details = $inherit_list->getListList();
        if (!is_array($list_details)) {
            $list_details = array();
        }

        $getListHeader = array(); //$inherit_list->getListHeader();

        //echo "<pre>";print_r($getListHeader);die;
        if (!is_array($getListHeader)) {
            $getListHeader = array();
        }

        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'list_data' => $request->list_id,
            'header_column' => $request->header_column,
            'header_value' => $request->header_value,
        );

        $url = env('API_URL') . 'search-leads';
        //$recycle_list = Helper::PostApi($url,$body);
        /*  $leads_list = Helper::PostApi($url,$body);
        echo "<pre>";print_r($leads_list);die;*/

        try {
            $leads_list = Helper::PostApi($url, $body);
            //echo "<pre>";print_r($recycle_list);die;
            if ($leads_list->success == 'true') {

                $leads = $leads_list->data;

                $url = env('API_URL') . 'list-header';
                $body = array(
                    'id' => Session::get('id'),
                    'token' => Session::get('tokenId'),
                    'list_data' => $request->list_id,

                );

                $lists_header = Helper::PostApi($url, $body);

                //echo "<pre>";print_r($lists_header);die;
                try {
                    $lists_header = Helper::PostApi($url, $body);
                    if ($lists_header->success == 'true') {
                        $lists_header_array = $lists_header->data;
                    }
                    if ($lists_header->success == 'false') {
                        return 0; //$lists_header->message;
                    }
                } catch (BadResponseException $e) {
                    return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
                } catch (RequestException $ex) {
                    return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
                }

                // return back()->withSuccess($leads_list->message);
                return view('lists.leads', compact('list_details', 'leads', 'getListHeader', 'lists_header_array'))->withSuccess($leads_list->message);
            }
            if ($leads_list->success == 'false') {
                return back()->withSuccess($leads_list->message);

                //return back()->withSuccess($ext_group->message);
            }
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
        }
    }


    public function updateCampaignList($campaign_id = "", $list_id = "", $status = "", $check_url="")
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'listId' => $list_id,
            'campaign_id' => $campaign_id,
            'status' => $status,
        );

        $url = env('API_URL') . 'status-update-campaign-list';
        try 
        {
            $delete_list = Helper::PostApi($url, $body);
            if ($delete_list->success == 'true')
            {
                echo json_encode(array('status' => "true", 'message' => 'List status changed successfully.'));
            }
            else
            {
                echo json_encode(array('status' => "false", 'message' => 'Something went wrong.'));
            }
        }
        catch (BadResponseException $e)
        {
            return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public function updateList($id = "", $status = "")
    {

        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'listId' => $id,
            'status' => $status,
        );
        $url = env('API_URL') . 'status-update-list';

        try {
            $delete_list = Helper::PostApi($url, $body);
            if ($delete_list->success == 'true') {
                //return $delete_list->message;
                return redirect('/list')->withSuccess($delete_list->message);
            }
            if ($delete_list->success == 'false') {
                return redirect('/list')->withSuccess($delete_list->message);
            }
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public function updateListStatus($id = "", $status = "")
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'listId' => $id,
            'status' => $status,
        );

        $url = env('API_URL') . 'status-update-list';
        try
        {
            $delete_list = Helper::PostApi($url, $body);
            if ($delete_list->success == 'true')
            {
                //return redirect('/list')->withSuccess($delete_list->message);
                echo json_encode(array('status' => "true", 'message' => $delete_list->message));
            }
            if ($delete_list->success == 'false') {
                //return redirect('/list')->withSuccess($delete_list->message);
                echo json_encode(array('status' => "false", 'message' => $delete_list->message));
            }
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    /**
    * Show Lead Activity Page
    * @param Request $request
    * @return type
    */
    public function showLeadActivityPage(Request $request) {
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }

        //smtp setting user

        $smtp = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "smtp-by-user-id";
        try {
            $response = Helper::PostApi($url);
            if ($response->success) {
                $smtp = $response->data;
            } else {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("lists.lead_activity", compact("errors", $errors));
        }

        $number = isset($request->phone_number) ? $request->phone_number : '';

        $body = array(
            'number' => $number,
            'extension' => Session::get('extension'),
            'parent_id' => Session::get('parentId')
        );

        $url = env('API_URL') . 'live-call-activity';
        $response = Helper::PostApi($url, $body, "json");

        $live_call = $response->data;

        //echo "<pre>";print_r($live_call);die;

        $inherit_disposition = new InheritApiController;
        $disposition_list =  $inherit_disposition->getDisposition();
        if(!is_array($disposition_list))
        {
            $disposition_list =array();
        }



        $updateData = $userData = $arrUser = $leadDataArr = $temp = $arrUserIdExtMap = array();
        $leadId = 0;
        $campaignId = 0;
        $url = env('API_URL') . 'get-cdr';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'phone_number' => $number,
            'extension' => Session::get('extension'),
            'alt_extension' => Session::get('private_identity')


        );

        try {
            $leadAct = Helper::PostApi($url, $body);
            $updateData = isset($leadAct->data->updateData) ? $leadAct->data->updateData : [];
            $userData = isset($leadAct->data->userData) ? $leadAct->data->userData : [];
            $leadData = isset($leadAct->data->leadData) ? $leadAct->data->leadData : [];

            //process updates loop for getting lead id
            foreach($updateData as $updates) {
                if($leadId == 0 && isset($updates->lead_id) && $updates->lead_id != null) { //get Lead Id
                    $leadId = $updates->lead_id;
                    //break;
                }

                if($campaignId == 0 && isset($updates->campaign_id) && $updates->campaign_id != null) { //get Campaign Id
                    $campaignId = $updates->campaign_id;
                    //break;
                }
                
                break;

            }

            //process user data loop
            foreach($userData as $u) {
                $temp['id'] = $u->id;
                $temp['first_name'] = $u->first_name;
                $temp['last_name'] = $u->last_name;
                $temp['email'] = $u->email;
                $temp['mobile'] = $u->mobile;
                $temp['extension'] = $u->extension;
                $arrUser[$u->extension] = $temp;
                $arrUserIdExtMap[$u->id] = $u->extension;
                $temp = [];
            }

            $temp = [];
            //process lead data loop
            foreach($leadData as $l) {
                $temp['id'] = $l->id;
                $temp['title'] = $l->title;
                $temp['value'] = $l->value;
                $temp['is_dialing'] = $l->is_dialing;
                $leadDataArr[$l->id] = $temp;
                $temp = [];
            }

            return view('lists.lead_activity', compact('disposition_list','leadDataArr', 'updateData', 'arrUser', 'arrUserIdExtMap', 'leadId','campaignId','smtp','live_call'));
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (lists_header): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            $message = "Page Not Found";
            return back()->with('message', "Error code - (lists_header): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    /**
    * Show Edit lead page. If lead id is 0 then create new entry in lead_data
    * @param type $id
    * @return type
    */
    public function showEditLeadDataPage($id, $number) {
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }

        $url = env('API_URL') . 'get-data-for-edit-lead-page';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'lead_id' => $id,
        );
        try {
            $result = Helper::PostApi($url, $body);
            $leadData = isset($result->data->leadData) ? $result->data->leadData : '';

            return view('lists.edit_lead', compact('leadData', 'id', 'number'));
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (list): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            $message = "Page Not Found";
            return back()->with('message', "Error code - (list): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    /**
    * Update / Create lead data
    * @param type $id
    * @param type $status
    * @return type
    */
    public function updateLeadData(Request $request) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'lead_id' => $request->lead_id,
            'number' => $request->number,
            'label_id' => $request->label_id,
            'label_value' => $request->label_value
        );
        $url = env('API_URL') . 'update-lead-data';

        try {
            $result = Helper::PostApi($url, $body);
            return redirect("/lead-activity?phone_number=".$request->number)->withSuccess($result->message);
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (edit-list): Oops something went wrong :( Please contact your administrator.)");
        }
    }


    public function changeDisposition(Request $request) {
        $body = array(
            
            'cdr_id' => $request->cdr_id,
            'disposition_id' => $request->disposition_id
        );
        $url = env('API_URL') . 'change-disposition';

        try {
            $result = Helper::PostApi($url, $body);
            return 1;
        } catch (BadResponseException $e) {
           return 0;
        }
    }

    public function getListContent($intListId, Request $request){
        $url = env('API_URL') . 'update-lead-data';

        try {
            $strUrl = env('API_URL') . 'list/'.$intListId.'/content';
            $response = Helper::GetApi($strUrl);
            if ($response->success) {
                $this->downloadSendHeaders($response->data->list_name. "_" . date("Y-m-d") . ".csv");
                echo $this->arrayToCsv($response->data->list_header, $response->data->list_data);
                die();
            } else {
                return redirect("/list")->withErrors($response->message);
            }
        } catch (\Throwable $ex) {
            return redirect("/list")->withSuccess($ex->getMessage());
        }
    }

    function arrayToCsv(array $listHeaders, array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, $listHeaders);
        foreach ($array as $row) {
            fputcsv($df, (array) $row);
        }
        fclose($df);
        return ob_get_clean();
    }

    function downloadSendHeaders($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }
}
