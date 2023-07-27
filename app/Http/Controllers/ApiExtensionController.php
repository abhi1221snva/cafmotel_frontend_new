<?php

namespace App\Http\Controllers\III_Ranks;

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\InheritApiController;

use Session;

class ApiExtensionController extends Controller
{

    function getGroup(Request $request)
    {
        $errors = new MessageBag();
        /* list list */
        $map = [];
        $url = env('API_URL') . "extension-group-map";
        try
        {
            $response = Helper::GetApi($url);
            if ($response->success)
            {
                $map = $response->data;
                //echo"<pre>";print_r($map);die;
            }
            else
            {
                foreach ( $response->errors as $key => $message )
                {
                    $errors->add($key, $message);
                }
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("extension.extension-group", compact("errors", $errors));
        }

        /* close list */
        $title = "Group List | " . env('APP_NAME');
        $url = env('API_URL') . 'extension-group';
        $response = Helper::GetApi($url);
        if ($response->success) {
            $group = $response->data;
            return view('extension.extension-group', compact('map','group', 'title'));
        } else {
            $request->session()->flash('message', "Failed to fetch extension group. ".$response->message);
            $group = [];
            return view('extension.extension-group', compact('map','group', 'title'));
        }
    }

    function storeExtensionGroup(Request $request)
    {
        if (!empty($request->id)) {
            $body = array(
                'title' => $request->title,
                'extensions' => $request->extensions

            );

            $url = env('API_URL') . "extension-group/".$request->id;
            try {
                $response = Helper::RequestApi($url, "PATCH", $body, "json");
                if ($response->success) {
                    return back()->withSuccess($response->message);
                } else {
                    $errors = $this->buildErrors($response);
                    return back()->withErrors($errors);
                }
            } catch (\Throwable $ex) {
                $errors = new MessageBag();
                $errors->add("exception", $ex->getMessage());
                return back()->withErrors($errors);
            }
        } else {
            $body = array(
                'title' => $request->title,
                'status' => $request->status,
                'extensions' => $request->extensions

            );
            $url = env('API_URL') . "extension-group";
            try {
                $response = Helper::RequestApi($url, "PUT", $body, "json");
               // echo "<pre>";print_r($response);die;
                if ($response->success) {
                    return back()->withSuccess($response->message);
                } else {
                    $errors = $this->buildErrors($response);
                    return back()->withErrors($errors);
                }
            } catch (\Throwable $ex) {
                $errors = new MessageBag();
                $errors->add("exception", $ex->getMessage());
                return back()->withErrors($errors);
            }
        }
    }

    function deleteExtensionGroup($group_id)
    {
        $url = env('API_URL') . "extension-group/$group_id";
        $response = Helper::RequestApi($url, "DELETE");
        if ($response->success) {
            session()->flash("success", $response->message);
        } else {
            session()->flash("message", $response->message);
        }
        return response()->json($response);
    }


    function mapExtensionGroup()
    {
        $url = env('API_URL') . "extension-group-map";
        $response = Helper::GetApi($url);
        if ($response->success) {
            session()->flash("success", $response->message);
        } else {
            session()->flash("message", $response->message);
        }
        return response()->json($response);
    }

    function addGroup($title,$extensions)
    {
        $extensionsArray = explode(',', $extensions); // Assuming the extension IDs are comma-separated

        $body = array(
            'title' => $title,
            'extensions'=>$extensionsArray,
        );
        
        $url = env('API_URL') . "extension-group";
        $response = Helper::RequestApi($url, "PUT", $body, "json");
        if ($response->success)
        {
            $url = env('API_URL') . 'extension-group';
            $response_group = Helper::GetApi($url);
            if ($response_group->success) {
                $group = $response_group->data;
            }
        }
        return $group;
    }

    /////////////////////////////////////////////////////////////

    function getExtension(Request $request)
    {

        $title = "Extensions List | " . env('APP_NAME');
        $inherit_list = new InheritApiController;
        $extension_list = $inherit_list->getExtensionList();
        // echo Session::get('tokenId'); exit;
        if (!is_array($extension_list)) {
            $extension_list = array();
        }
        if (empty($extension_list)) {
            if (empty(Session::get('tokenId'))) {
                return redirect('/');
            }
        }
        return view('extension.extension', compact('extension_list', 'title'));
    }


    function deleteExtension($extension_id)
    {
        $body = array(
            'extension_id' => $extension_id,
            'is_deleted' => '1'
        );
        $url = env('API_URL') . 'edit-extension';
        $response = Helper::PostApi($url, $body);
        if ($response->success) {
            session()->flash("success", $response->message);
        } else {
            session()->flash("message", $response->message);
        }
        return response()->json($response);
    }

    function editExtension($extension_id, Request $request)
    {
        $voip_configurations = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "voip-configurations";
        $response = Helper::GetApi($url);
        try
        {
            if($response->success)
            {
                $voip_configurations = $response->data;
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
            return view("extension.edit-ext")->withErrors($errors);
        }



        $title = "Edit Extension | " . env('APP_NAME');

        $url = env('API_URL') . "sms-setting";
            $response = Helper::GetApi($url);
            $sms = [];
            if ($response->success) {
                $sms = $response->data;
            } else {
                $sms = [];
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
                return view("extension.edit-ext")->withErrors($errors);
            }


        $inherit_list = new InheritApiController;
        $did_list = $inherit_list->getDidList();
        if (!is_array($did_list)) {
            $did_list = array(); // checking empty record
        }
        $ivr_list = $inherit_list->getIvr();
        if (!is_array($ivr_list)) {
            $ivr_list = array();
        }

        $user_extension_list = $inherit_list->getExtensionList();
        if (!is_array($user_extension_list)) {
            $user_extension_list = array();
        }

        $ring_group_list = $inherit_list->getRingGroupList();
        if (!is_array($ring_group_list)) {
            $ring_group_list = array();
        }

        /* conferencing list */
        $url = env('API_URL').'conferencing';
        try
        {
            $response = Helper::PostApi($url);
            if ($response->success) {
                $conferencing = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
        }

        $destTypeList = Config::get('desttype.dest_type');
        $assigned_package = '';
        $server_list = array();
        if ($request->isMethod('get')) {
            $url = env('API_URL') . 'extension-group';
            try {
                $response = Helper::GetApi($url);
                if ($response->success) {
                    $group = $response->data;
                } else {
                    session()->flash('error', $response->message);
                    return redirect('extension');
                }
            } catch (\Throwable $e) {
                session()->flash('error', $e->getMessage());
                return redirect('extension');
            }

            $url = env('API_URL') . "extension/$extension_id";
            try {
                $extension = Helper::GetApi($url);
                if ($extension->success) {
                    $extension_list = $extension->data;
                    $mapping = array();
                    foreach ( $extension_list->group as $map ) {
                        $mapping[] = $map->group_id;
                    }
                    if (isset($extension_list->serverList)) {
                        $server_list = $extension_list->serverList;
                    }
                    if (isset($extension_list->assignedPackage)) {
                        $assigned_package = $extension_list->assignedPackage;
                    }
                } else {
                    return redirect('extension')->with('message', $extension->message);
                }
            } catch (\Throwable $e) {
                return redirect('extension')->with('message', $e->getMessage());
            }

            return view('extension.edit-ext', compact('user_extension_list','conferencing','ring_group_list','ivr_list','destTypeList','did_list','group', 'extension_list', 'mapping', 'title', 'server_list', 'assigned_package','sms','voip_configurations'));

        } else if ($request->isMethod('post')) {
            $edit_disposition = array_unique($request->edit_disposition);
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'campaign_id' => $request->campaign_id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'caller_id' => $request->caller_id,
                'custom_caller_id' => $request->custom_caller_id,
                'time_based_calling' => $request->time_based_calling,
                'call_time_start' => '09:30',//$request->call_time_start,
                'call_time_end' => '09:30', //$request->call_time_end,
                'dial_mode' => $request->dial_mode,
                'asterisk_server_id' => $request->asterisk_server_id,
                'group_id' => $request->group_id,
                'max_lead_temp' => $request->max_lead_temp,
                'min_lead_temp' => $request->min_lead_temp,
                'send_report' => $request->send_report,
            );

            $url = env('API_URL') . 'edit-campaign';

            try {
                $addcampaign = Helper::PostApi($url, $body);

                if ($addcampaign->success == 'true') {
                    $campaignId = 1;

                    foreach ( $edit_disposition as $edit_dispocampaign ) {
                        $edit_disposition_array[] = $edit_dispocampaign;
                    }

                    $body = array(
                        'id' => Session::get('id'),
                        'token' => Session::get('tokenId'),
                        'campaign_id' => $campaignId,
                        'disposition_id' => $edit_disposition_array
                    );

                    $url = env('API_URL') . 'edit-campaign-disposition';
                    $campaign_disposition = Helper::PostApi($url, $body);

                    return back()->withSuccess($addcampaign->message);
                }

                if ($addcampaign->success == 'false') {
                    return back()->withSuccess($addcampaign->message);
                }
            } catch (\Throwable $e) {
                return back()->with('message', "Error code - (edit-campaign-disposition): Oops something went wrong :( Please contact your administrator.)");
            }
        }
    }


    function changePasswordAgent(Request $request)
    {

        if (!empty($request->xml)) {
            $inherit_list = new InheritApiController;
            $phoneArray = $inherit_list->getExtensionList();
            if (!is_array($phoneArray)) {
                $phoneArray = array();
            }

            if (empty($phoneArray)) {
                if (empty(Session::get('tokenId'))) {
                    return redirect('/');
                }
            }

            //echo "<pre>";print_r($extension_list);die;

            $filePath = 'phonebook.xml';
            $dom = new \DOMDocument('1.0', 'utf-8');
            $root = $dom->createElement('AddressBook');

            $pbgroup = $dom->createElement('pbgroup');
            $id = 1;
            $name = 'Blacklist';

            $id = $dom->createElement('id', $id);
            $name = $dom->createElement('name', $name);

            $pbgroup->appendChild($id);
            $pbgroup->appendChild($name);
            $root->appendChild($pbgroup);

            $pbgroup = $dom->createElement('pbgroup');
            $id_2 = 2;
            $name_2 = 'Whitelist';

            $id_2 = $dom->createElement('id', $id_2);
            $name_2 = $dom->createElement('name', $name_2);

            $pbgroup->appendChild($id_2);
            $pbgroup->appendChild($name_2);

            $root->appendChild($pbgroup);
            for ( $i = 0; $i < count($phoneArray); $i++ ) {
                $id = htmlspecialchars($phoneArray[$i]->id);
                $first_name = htmlspecialchars($phoneArray[$i]->first_name);
                $last_name = htmlspecialchars($phoneArray[$i]->last_name);
                $Frequent = 0;

                $phonenumber = htmlspecialchars($phoneArray[$i]->extension);
                $phonenumber = substr($phonenumber, -4);
                $accountindex = 0;

                $Group = 2;
                $Primary = 0;

                $contact = $dom->createElement('Contact');
                $id = $dom->createElement('id', $id);
                $first_name = $dom->createElement('FirstName', $first_name);
                $last_name = $dom->createElement('LastName', $last_name);
                $Frequent = $dom->createElement('Frequent', $Frequent);

                $Group = $dom->createElement('Group', $Group);
                $Primary = $dom->createElement('Primary', $Primary);
                $contact->appendChild($id);
                $contact->appendChild($first_name);
                $contact->appendChild($last_name);
                $contact->appendChild($Frequent);
                $contact->appendChild($Group);
                $contact->appendChild($Primary);

                $Phone = $dom->createElement('Phone');
                $Phone->setAttribute('type', 'Cell');
                $phonenumber = $dom->createElement('phonenumber', $phonenumber);
                $accountindex = $dom->createElement('accountindex', $accountindex);

                $Phone->appendChild($phonenumber);
                $Phone->appendChild($accountindex);

                $contact->appendChild($Phone);
                $root->appendChild($contact);
            }

            $dom->appendChild($root);
            $dom->save($filePath);
            header('Content-disposition: attachment; filename="phonebook.xml"');
            header('Content-type: "text/xml"; charset="utf8"');
            readfile('phonebook.xml');
        } else if ($request->ip_name == 'ip') {


            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'ext_id' => $request->ext_ip_id,
                'allowed_ip' => $request->allowed_ip,

            );


            //echo "<pre>";print_r($body);die;

            $url = env('API_URL') . 'update-allowed-ip';
            /*$ext_group = Helper::PostApi($url,$body);

           echo "<pre>";print_r($ext_group);die;*/

            try {
                $allowed_ip = Helper::PostApi($url, $body);
                /* echo "<pre>";print_r($ext_group);die;*/
                if ($allowed_ip->success == 'true') {
                    return back()->withSuccess($allowed_ip->message);

                }

                if ($allowed_ip->success == 'false') {
                    return back()->withSuccess($allowed_ip->message);

                    //return back()->withSuccess($ext_group->message);
                }
            } catch (BadResponseException   $e) {

                return back()->with('message', "Error code - (update-allowed-ip): Oops something went wrong :( Please contact your administrator.)");


            } catch (RequestException $ex) {
                return back()->with('message', "Error code - (update-allowed-ip): Oops something went wrong :( Please contact your administrator.)");
            }

        }

        else {


            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'ext_id' => $request->ext_id,
                'password' => $request->password,

            );

            $url = env('API_URL') . 'update-agent-password-by-admin';

            try {
                $change_password = Helper::PostApi($url, $body);
                //print_r($change_password); exit;
                /* echo "<pre>";print_r($ext_group);die;*/
                if ($change_password->success == 'true') {
                    return back()->withSuccess($change_password->message);

                }

                if ($change_password->success == 'false') {
                    return back()->withSuccess($change_password->message);

                    //return back()->withSuccess($ext_group->message);
                }
            } catch (BadResponseException   $e) {

                return back()->with('message', "Error code - (update-agent-password-by-admin): Oops something went wrong :( Please contact your administrator.)");


            } catch (RequestException $ex) {
                return back()->with('message', "Error code - (update-agent-password-by-admin): Oops something went wrong :( Please contact your administrator.)");
            }

        }
    }


    function checkAltExtension($alt_extension_name){
        $body = array(
            'alt_extension' => $alt_extension_name,
        );
         $url = env('API_URL') . "check-alt-extension";
        try {
             $response = Helper::PostApi($url, $body);

            if ($response->success) {
                return $response->success;
            } else {
                $errors = new MessageBag();
                $errors->add("error", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
        } catch (\Throwable $exception) {
            $errors = new MessageBag();
            $errors->add("error", $exception->getMessage());
            return $errors;
        }
    }


    function checkExtension($extension_name){
        $body = array(
            'extension' => $extension_name,
        );
         $url = env('API_URL') . "check-extension";
        try {
             $response = Helper::PostApi($url, $body);

            if ($response->success) {
                return $response->success;
            } else {
                $errors = new MessageBag();
                $errors->add("error", $response->message);
                foreach ( $response->errors as $key => $messages ) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
        } catch (\Throwable $exception) {
            $errors = new MessageBag();
            $errors->add("error", $exception->getMessage());
            return $errors;
        }
    }

    function updateEmail(Request $request)
    {
        $body = array(
            'email' => $request->email,
            'user_id'=>$request->user_id
        );

        $url = env('API_URL') . "update-email";
        try
        {
            $response = Helper::PostApi($url, $body);
            if ($response->success)
            {
                return $response->success;
            }
            else
            {
                $errors = new MessageBag();
                $errors->add("error", $response->message);
                foreach ( $response->errors as $key => $messages )
                {
                    if (is_array($messages))
                    {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    }
                    else
                    {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
        }
        catch (\Throwable $exception)
        {
            $errors = new MessageBag();
            $errors->add("error", $exception->getMessage());
            return $errors;
        }
    }

    function checkEmail($email){

        if(!empty($email))
        {

            $body = array(
                'email' => $email,
            );

            $url = env('API_URL') . "check-email";
            try
            {
                $response = Helper::PostApi($url, $body);
                //echo "<pre>";print_r($response);die;

                if($response->success){
                    return $response->success;
                }
                else
                {
                    $errors = new MessageBag();
                    $errors->add("error", $response->message);
                    foreach ( $response->errors as $key => $messages )
                    {
                        if (is_array($messages))
                        {
                            foreach ($messages as $index => $message)
                                $errors->add("$key.$index", $message);
                        }
                        else
                        {
                            $errors->add($key, $messages);
                        }
                    }
                    return $errors;
                }
            }
            catch (\Throwable $exception)
            {
                $errors = new MessageBag();
                $errors->add("error", $exception->getMessage());
                return $errors;
            }
        }
    }

    // Edit form post method
    function saveEditExtension(Request $request)
    {

         if(!empty($request->voip_configurations))
            {
                $voip_configurations = $request->voip_configurations;
            }
            else
            {
                $voip_configurations = 0;
            }

            $mobile = str_replace(array('(',')', '_', '-',' '), array(''), $request->mobile);

        if ($request->cli_setting == 1) {
            $cli_number = $request->cli;
            $cnam = $request->cnam;
        } else {
            $cli_number = '0';
            $cnam = '0';
        }

        if($request->receive_sms_on_email == 'on')
            {
                $receive_sms_on_email = 1;
            }
            else
            {
                $receive_sms_on_email = 0;
            }

            if($request->receive_sms_on_mobile == 'on')
            {
                $receive_sms_on_mobile = 1;
            }
            else
            {
                $receive_sms_on_mobile = 0;
            }
        $extension_type = $request->extension_type;


        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'extension_id' => $request->extension_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile' => $mobile,
            'country_code' => $request->countryCode,

            // 'email'=> $request->email,
            'follow_me' => $request->follow_me,
            'call_forward' => $request->call_forward,
            'voicemail' => $request->voicemail,
            'vm_pin' => $request->vm_pin,
            'voicemail_send_to_email' => $request->voicemail_send_to_email,
            'twinning' => $request->twinning,
            'group_id' => $request->group_id,
            'extension_type' => $extension_type ,
            'cli_setting' => $request->cli_setting,
            'cli' => $cli_number,
            'cnam' => $cnam,
            'asterisk_server_id' => $request->asterisk_server_id,
            'sms_setting_id' => $request->sms_setting_id,
            'receive_sms_on_email' => $receive_sms_on_email,
            'receive_sms_on_mobile' => $receive_sms_on_mobile,
            'ip_filtering' => $request->ip_filtering,
            'enable_2fa' => $request->enable_2fa,
            'voip_configuration_id' => $voip_configurations,
            'app_status' => $request->app_status


        );
        $url = env('API_URL') . 'edit-extension-save';
        //echo'<pre>'; echo $url; print_r($body); exit;
        // added validation
        $validator = Validator::make($request->all(), [
            'extension_id' => 'required',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'follow_me' => 'required|string|max:2',
            'call_forward' => 'required|string|max:2',
            'voicemail' => 'required|string|max:2',
            'vm_pin' => 'required|numeric|min:4',
            'voicemail_send_to_email' => 'required|string|max:1',
            'twinning' => 'required|string|max:1',
            'group_id' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()->withInput();
        }

        try {
            $extension_response = Helper::PostApi($url, $body);
            //echo "<pre>";print_r($extension_response);die;
            if (isset($extension_response->success) && $extension_response->success == 'true') {
                return redirect('/extension')->withSuccess($extension_response->message);
            } else if (isset($extension_response->success) && $extension_response->success == 'false') {
                return back()->with('message', $extension_response->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (Edit extension-admin): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (Edit extension-admin): Oops something went wrong :( Please contact your administrator.)");
        }

    }

    // Add Extension view
    public function index()
    {

        $voip_configurations = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "voip-configurations";
        $response = Helper::GetApi($url);
        try
        {
            if($response->success)
            {
                $voip_configurations = $response->data;
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
            return view("extension.add-ext")->withErrors($errors);
        }

        $url = env('API_URL') . "sms-setting";
            $response = Helper::GetApi($url);
            $sms = [];
            if ($response->success) {
                $sms = $response->data;
            } else {
                $sms = [];
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
                return view("extension.add-ext")->withErrors($errors);
            }
        $inherit_list = new InheritApiController;
        $did_list = $inherit_list->getDidList();
        if (!is_array($did_list)) {
            $did_list = array(); // checking empty record
        }

        $ivr_list = $inherit_list->getIvr();
        if (!is_array($ivr_list)) {
            $ivr_list = array();
        }

        $extension_list = $inherit_list->getExtensionList();
        if (!is_array($extension_list)) {
            $extension_list = array();
        }

        $ring_group_list = $inherit_list->getRingGroupList();
        if (!is_array($ring_group_list)) {
            $ring_group_list = array();
        }

        /* conferencing list */
        $url = env('API_URL').'conferencing';
        try
        {
            $response = Helper::PostApi($url);
            if ($response->success) {
                $conferencing = $response->data;
            }
            else
            {
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
        }

        $destTypeList = Config::get('desttype.dest_type');


        $availablePackages = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "user-packages";
        $urlClientPackages = env('API_URL') . "client-packages";


        /*//get user packages info
        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $userPackages = $response->data;
            } else {
                $userPackages = [];
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }

        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.user-packages", compact("errors", $errors));
        }*/

        //get packages availability
        try {
            $response = Helper::GetApi($urlClientPackages);
            if ($response->success) {
                $availablePackages = (array) $response->data;
            } else {
                $availablePackages = [];
                foreach ( $response->errors as $key => $message ) {
                    $errors->add($key, $message);
                }
            }
        } catch (RequestException $ex) {
            $errors->add("error", $ex->getMessage());
            return view("subscriptions.user-packages", compact("errors", $errors));
        }

        //echo "<pre>";print_r($availablePackages);die;
        $title = "Add Extension | " . env('APP_NAME');
        $client_url = env('API_URL') . 'client_ip_list';
        $client_body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        $client_ip = Helper::PostApi($client_url, $client_body);
        $client_data = $client_ip->data;

        $group = [];
        try {
            $url = env('API_URL') . 'extension-group';
            $response = Helper::GetApi($url);
            if ($response->success) {
                $group = $response->data;
            } else {
                foreach ($response->errors as $key => $message) {
                    $errors->add($key, $message);
                }
                return redirect()->back()->withErrors($errors);
            }
        } catch (\Throwable $e) {
            $errors->add("error", $e->getMessage());
            return redirect()->back()->withErrors($errors);
        }

        //echo "<pre>";print_r($sms);die;


        return view('extension.add-ext', compact('conferencing','ring_group_list','extension_list','destTypeList','ivr_list','did_list','title', 'client_data', 'group','availablePackages','sms','voip_configurations'));
    }

    // Save add Extension
    function storeExtension(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'follow_me' => 'required|string|max:2',
            'call_forward' => 'required|string|max:2',
            'extension' => 'required|string|min:4',
            'voicemail' => 'required|string|max:2',
            'vm_pin' => 'required|numeric|min:4',
            'voicemail_send_to_email' => 'required|string|max:1',
            'twinning' => 'required|string|max:1',
            'group_id' => 'required',
            'extension_type' => 'required',

        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator);
        }

            /*$mobile = str_replace(" ", '', $request->mobile);
            $mobile = str_replace("(", '', $mobile);
            $mobile = str_replace(")", '', $mobile);
            $mobile = str_replace("+", '', $mobile);*/

            $mobile = str_replace(array('(',')', '_', '-',' '), array(''), $request->mobile);

            if ($request->cli_setting == 1) {
                $cli_number = $request->cli;
                $cnam = $request->cnam;
            } else {
                $cli_number = '0';
                $cnam = '0';
            }

            if($request->receive_sms_on_email == 'on')
            {
                $receive_sms_on_email = 1;
            }
            else
            {
                $receive_sms_on_email = 0;
            }

            if($request->receive_sms_on_mobile == 'on')
            {
                $receive_sms_on_mobile = 1;
            }
            else
            {
                $receive_sms_on_mobile = 0;
            }



            if(!empty($request->voip_configurations))
            {
                $voip_configurations = $request->voip_configurations;
            }
            else
            {
                $voip_configurations = 0;
            }


            $body = array(
                #'id' => Session::get('id'),
                #'token' => Session::get('tokenId'),
                'extension_id' => $request->extension_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile' => $mobile,
                'country_code' => $request->countryCode,
                'email' => $request->email,
                'password' => $request->password,
                'extension' => intval($request->extension),
                'follow_me' => $request->follow_me,
                'call_forward' => $request->call_forward,
                'voicemail' => $request->voicemail,
                'vm_pin' => $request->vm_pin,
                'voicemail_send_to_email' => $request->voicemail_send_to_email,
                'twinning' => $request->twinning,
                'group_id' => $request->group_id,
                'asterisk_server_id' => $request->asterisk_server_id,
                'cli_setting' => $request->cli_setting,
                'cli' => $cli_number,
                'cnam' => $cnam,
                'extension_type'=>$request->extension_type,
                'package_id' => $request->package_id,
                'sms_setting_id'=>$request->sms_setting_id,
                'receive_sms_on_email' => $receive_sms_on_email,
                'receive_sms_on_mobile' => $receive_sms_on_mobile,
                'ip_filtering' => $request->ip_filtering,
                'enable_2fa' => $request->enable_2fa,
                'voip_configuration_id' => $request->voip_configurations,
                'app_status' => $request->app_status




        );

            //echo "<pre>";print_r($body);die;
        $url = env('API_URL') . 'new-extension-save';


        try {
            $extension_response = Helper::PostApi($url, $body);
            //echo "<pre>";print_r($extension_response);die;

            //return back()->withSuccess($extension_response->message);
            if (isset($extension_response->success) && $extension_response->success == 'true') {
                return redirect('extension')->withSuccess($extension_response->message);
            } else {
                Session::flash('error', $extension_response->message);
                return back()->with('message', "Error code - (Extension): " . $extension_response->message . ")");
            }
        } catch (ClientException $clientException) {
            $response = $clientException->getResponse();
            if ($response->getStatusCode() === 400) {
                $content = json_decode($response->getBody()->getContents(), true);
                $messageBag = new MessageBag($content["errors"]);
                return Redirect::back()->withInput($request->input())->withErrors($messageBag);
            }
            return Redirect::back()->withInput($request->input())->with('message', $clientException->getMessage());
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());
            return Redirect::back()->withInput($request->input())->with('message', "Error code - (Add extension-admin): " . $exception->getMessage());
        }
    }

    public function getAssignableRoles(int $id)
    {
        $url = env('API_URL') . "/user/$id/assignable-roles";
        $response = Helper::PostApi($url, null, null);
        if ($response->success) {
            return view('extension.assignable-role', ['roles' => (array)$response->data]);
        }
        throw new \Exception($response->message);
    }

    public function saveUserRoles(Request $request)
    {
        $this->validate($request, [
            "userId" => "required|int",
            "role" => "required|int"
        ]);
        $userId = $request->input("userId");
        $body = [
            "role" => intval($request->input("role"))
        ];
        $url = env('API_URL') . "/user/$userId/permission";
        $response = Helper::PostApi($url, $body, "json");
        return response()->json($response);
    }


    public function hangupConferences(Request $request)
    {

        $this->validate($request, [
            "extensionId" => "required|int"
        ]);
        $extensionId = $request->input("extensionId");
        $body = [
            "extension" => intval($extensionId)
        ];
        $url = env('API_URL') . "hangup-conferences";
        $response = Helper::PostApi($url, $body, "json");
        return response()->json($response);
    }
 
    
}


