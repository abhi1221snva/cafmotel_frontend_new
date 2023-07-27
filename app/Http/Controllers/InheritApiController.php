<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Session;
use App\Helper\Helper;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;

class InheritApiController extends Controller
{
    function getDestType()
    {
        $url = env('API_URL') . 'dest-type';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        try {
            $destType = Helper::PostApi($url, $body);
            if ($destType->success == 'true') {
                return $destType = $destType->data;
            }
            if ($destType->success == 'false') {
                return view('configuration.ivr-menu');
                if (empty(Session::get('tokenId'))) {
                    return redirect('/');
                }
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (dest-type): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (dest-type): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getIvr()
    {
        $url = env('API_URL') . 'ivr';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );

        try {
            $ivr = Helper::PostApi($url, $body);
            if ($ivr->success == 'true') {
                return $ivr_list = $ivr->data;
            }
            if ($ivr->success == 'false') {
                return $ivr_list = $ivr->message;
                if (empty(Session::get('tokenId'))) {
                    return redirect('/');
                }
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (ivr): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (ivr): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getLabel()
    {
        $url = env('API_URL') . 'label';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'is_deleted' => '0',
        );

        try {
            $label = Helper::PostApi($url, $body);
            if ($label->success == 'true') {
                return $label_list = $label->data;
            } else {
                return [];
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (label): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (label): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getDisposition()
    {
        $url = env('API_URL') . 'disposition';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );

        try {
            $disposition = Helper::PostApi($url, $body);
            if ($disposition->success == 'true') {
                return $disposition_list = $disposition->data;
            }
            if ($disposition->success == 'false') {
                return redirect('/');
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (disposition): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (disposition): Oops something went wrong :( Please contact your administrator.)");
        }
    }


    function getMarketingCampaign()
    {
        $url = env('API_URL') . 'marketing-campaign';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        
        //echo "<pre>";print_r($body);die;

        // $marketing_campaign = Helper::PostApi($url,$body);

        // echo "<pre>";print_r($marketing_campaign);die;
        try {
            $marketing_campaign = Helper::PostApi($url, $body);
            if ($marketing_campaign->success == 'true') {
                return $marketing_campaign_list = $marketing_campaign->data;
            }
            if ($marketing_campaign->success == 'false') {
                return redirect('/');
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (marketing-campaign): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            $message = "Page Not Found";
            return redirect('/');
        }
    }

    function getCampaign()
    {
        $url = env('API_URL') . 'campaign';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        /*$campaign = Helper::PostApi($url,$body);

        echo "<pre>";print_r($campaign);die;*/
        try {
            $campaign = Helper::PostApi($url, $body);
            if ($campaign->success == 'true') {
                return $campaign_list = $campaign->data;
            }
            if ($campaign->success == 'false') {
                return redirect('/');
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (campaign): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            $message = "Page Not Found";
            return redirect('/');
        }
    }

    private function getData($url, array $body = [])
    {
        $response = Helper::PostApi($url, $body);
        if ($response->success == 'true') {
            return $response->data;
        } else {
            return $response->message;
        }
    }

    function getApiList()
    {
        $url = env('API_URL') . 'api-data';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        try {
            $api = Helper::PostApi($url, $body);
            if ($api->success == 'true') {
                return $api_list = $api->data;
            }
            if ($api->success == 'false') {
                return $api->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (api): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            $message = "Page Not Found";
            return redirect('/');
        }
    }

    function getRecycleRule()
    {
        $url = env('API_URL') . 'recycle-rule';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        try {
            $recycle = Helper::PostApi($url, $body);
            if ($recycle->success == 'true') {
                return $api_list = $recycle->data;
            }
            if ($recycle->success == 'false') {
                return $recycle->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (recycle-rule): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            $message = "Page Not Found";
            return redirect('/');
        }
    }

    function getExtension(string $orderBy = null, int $extension_id = null)
    {
        $url = env('API_URL') . 'extension';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'parent_id' => Session::get('parent_id'),
            'role' => Session::get('role'),
            'level'=>Session::get('level')
        );

        if ($orderBy) {
            $body["orderBy"] = $orderBy;
        }
        if ($extension_id) {
            $body["extension_id"] = $extension_id;
        }

        try {
            $extension = Helper::PostApi($url, $body);
            if ($extension->success == 'true') {
                return $extension_list = $extension->data;
            }
            if ($extension->success == 'false') {
                return $extension->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (extension): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (extension): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getExtensionList(string $orderBy = null, int $extension_id = null)
    {
        $url = env('API_URL') . 'extension-list';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'parent_id' => Session::get('parent_id'),
            'role' => Session::get('role'),
            'level'=>Session::get('level')
        );

        if ($orderBy) {
            $body["orderBy"] = $orderBy;
        }
        if ($extension_id) {
            $body["extension_id"] = $extension_id;
        }

        try {
            $extension = Helper::PostApi($url, $body);
            if ($extension->success == 'true') {
                return $extension_list = $extension->data;
            }
            if ($extension->success == 'false') {
                return $extension->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (extension): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (extension): Oops something went wrong :( Please contact your administrator.)");
        }
    }
    function getListHeader()
    {
        $url = env('API_URL') . 'list-header';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        try {
            $lists_header = Helper::PostApi($url, $body);
            if ($lists_header->success == 'true') {
                return $lists_header = $lists_header->data;
            }
            if ($lists_header->success == 'false') {
                return $lists_header->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (lists_header): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            $message = "Page Not Found";
            return back()->with('message', "Error code - (lists_header): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getListList()
    {
        $url = env('API_URL') . 'list';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        try {
            $lists = Helper::PostApi($url, $body);
            if ($lists->success == 'true') {
                return $list_list = $lists->data;
            }
            if ($lists->success == 'false') {
                return $lists->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (list): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            $message = "Page Not Found";
            return back()->with('message', "Error code - (list): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getSmsList()
    {
        $url = env('API_URL') . 'sms';
        try {

            $sms = Helper::PostApi($url);

            if ($sms->success) {
                return $sms->data;
            } else {
                return $sms->message;
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    function getSmsListNew()
    {
        $url = env('API_URL') . 'sms';
        try {

            $sms = Helper::PostApi($url);

            if ($sms->success) {
                return $user_data = $sms->data;
            } else {
                return $user_data = array() ;
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    function getListById()
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'email' => Session::get('emailId')
        );

        // echo "<pre>";print_r($body);die;

        $url = env('API_URL') . 'list-by-email';
        //$did_group = Helper::PostApi($url,$body);
        // echo "<pre>";print_r($did_group);die;
        // echo "<pre>";print_r($body);die

        try {
            $did_group = Helper::PostApi($url, $body);
            /* echo "<pre>";print_r($ext_group);die;*/
            if ($did_group->success == 'true') {
                $group = $did_group->data;
                return $group;
                // echo "<pre>";print_r($sms_list);die;
                //echo "<pre>";print_r($body);die;
                return view('sms.sms', compact('sms_list', 'group'));
            }

            if ($did_group->success == 'false') {
                $group = array();
                // return redirect('/');
                return $did_group->message;

                //return back()->withSuccess($ext_group->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (sms): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (sms): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getFaxList()
    {
        $url = env('API_URL') . 'fax';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );

        try {
            $fax = Helper::PostApi($url, $body);
            if ($fax->success == 'true') {
                return $fax_list = $fax->data;
            }
            if ($fax->success == 'false') {
                return $fax->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (fax): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (fax): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getDidList()
    {
        $url = env('API_URL') . 'did';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        // echo $url ; print_r($body); exit;
        try {
            $did = Helper::PostApi($url, $body);
            if ($did->success == 'true') {
                return $did_list = $did->data;
            }
            if ($did->success == 'false') {
                return $did->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (Did): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (Did): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getRingGroupList()
    {
        $url = env('API_URL') . 'ring-group';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        try {
            $ring_group = Helper::PostApi($url, $body);
            if ($ring_group->success == 'true') {
                return $ring_group_list = $ring_group->data;
            }
            if ($ring_group->success == 'false') {
                return $ring_group->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (Ring group): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (Ring group): Oops something went wrong :( Please contact your administrator.)");
        }
    }


    public static function headerMenu()
    {

        if (!empty(Session::has('userMenu'))) {
            return Session::get('userMenu');
        }

        $usermenus = [];
        $url = env('API_URL') . 'user-menu';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId')
        );

        try {
            $menus = Helper::PostApi($url, $body);
            if ($menus->success == 'true') {
                foreach ( $menus->data as $val ) {
                    if ($val->parent_key ) {
                        $usermenus[$val->parent_key]['child'][] = $val;
                    } else {
                        $usermenus[$val->key]['parent'] = $val;
                    }
                }
                Session::put('userMenu', $usermenus);
                return $usermenus;
            }
            if ($menus->success == 'false') {
                if (empty(Session::get('tokenId'))) {
                    return redirect('/');
                }
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (user-menu): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (user-menu): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public static function headerUserDetails()
    {
        $url = env('API_URL') . 'user-detail';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'parentId' => Session::get('parentId')
        );
        //echo "<pre>";print_r($body);die;
        /* $userdetails   = Helper::PostApi($url,$body);
         echo "<pre>";print_r($userdetails);die;*/
        try {
            $userdetails = Helper::PostApi($url, $body);
            if ($userdetails->success == 'true') {
                return $userdetails;
            }
            if ($userdetails->success == 'false') {
                if (empty(Session::get('tokenId'))) {
                    return redirect('/');
                }
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (user-detail): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (user-detail): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    // Fetch Did count for DashBoard

    function getDidListCount()
    {
        $url = env('API_URL') . 'did-count';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );

        try {
            $did = Helper::PostApi($url, $body);
            if ($did->success == 'true') {
                return $did_list = $did->data;
            }
            if ($did->success == 'false') {
                return $did->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (Did Count): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (Did Count): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    // Fetch Extension count for DashBoard

    function getUserCount()
    {
        $url = env('API_URL') . 'user-count';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'parentId' => Session::get('parentId'),

        );

        try {
            $userObj = Helper::PostApi($url, $body);
            if ('true' == $userObj->success) {
                return $user_count = $userObj->data;
            }
            if ($userObj->success == 'false') {
                return $userObj->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (User Count): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (User Count): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getCampaignsCount()
    {
        $url = env('API_URL') . 'campaigns-count';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );

        try {
            $campaignsObj = Helper::PostApi($url, $body);
            if ('true' == $campaignsObj->success) {
                return $campaigns_count = $campaignsObj->data;
            }
            if ($campaignsObj->success == 'false') {
                return $campaignsObj->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (Campaigns Count): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (Campaigns Count): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getLeadCount()
    {
        $url = env('API_URL') . 'lead-count';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );

        try {
            $campaignsObj = Helper::PostApi($url, $body);

            if ('true' == $campaignsObj->success) {
                return $campaigns_count = $campaignsObj->data;
            }
            if ($campaignsObj->success == 'false') {
                return $campaignsObj->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (Campaigns Count): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (Campaigns Count): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getLoggedInAgent($start_date, $end_date, $userId)
    {
        $url = env('API_URL') . 'cdr-call-agent-count';
        $body = [
            'startTime' => $start_date, #." 00:00:00",
            'endTime' => $end_date, #." 23:59:59",
            'userId'=>$userId
        ];
        $response = Helper::PostApi($url, $body);
        if ($response->success) {
            return $response->data;
        } else {
            return $response->errors;
        }
    }

    function getCdrCallCount($route, $type, $start_date, $end_date, $userId)
    {
        $url = env('API_URL') . 'cdr-call-count';
        $body = [
            'startTime' => $start_date, #." 00:00:00",
            'endTime' => $end_date, #." 23:59:59",
            'route' => $route,
            'type' => $type,
            'userId'=>$userId
        ];
        $response = Helper::PostApi($url, $body);
        if ($response->success) {
            return $response->data;
        } else {
            return $response->errors;
        }
    }


    function getDispositionWiseCalls($start_date, $end_date, $userId)
    {
        $url = env('API_URL') . 'disposition-wise-call';
        $body = [
            'startTime' => $start_date, #." 00:00:00",
            'endTime' => $end_date, #." 23:59:59",
            'userId'=>$userId
        ];
        $response = Helper::PostApi($url, $body);
        if ($response->success) {
            return $response->data;
        } else {
            return $response->errors;
        }
    }


    function getStatewiseCalls($start_date, $end_date, $userId)
    {
        $url = env('API_URL') . 'state-wise-call';
        $body = [
            'startTime' => $start_date, #." 00:00:00",
            'endTime' => $end_date, #." 23:59:59",
            'userId'=>$userId
        ];
        $response = Helper::PostApi($url, $body);
        if ($response->success) {
            return $response->data;
        } else {
            return $response->errors;
        }
    }

    function getVoiceMailCount($start_date, $end_date, $userId)
    {
        $url = env('API_URL') . 'voicemail-count';
        $body = [
            'startTime' => $start_date, #." 00:00:00",
            'endTime' => $end_date, #." 23:59:59",
            'userId'=>$userId
        ];
        $response = Helper::PostApi($url, $body);
        if ($response->success) {
            return $response->data;
        } else {
            return $response->errors;
        }
    }

    function getExtensionListDashboard($start_date,$end_date,$userId)
    {
        $url = env('API_URL') . 'extension-summary';
        $body = [
            'startTime' => $start_date, #." 00:00:00",
            'endTime' => $end_date, #." 23:59:59",
            'userId'=>$userId
        ];
        $extObj = Helper::PostApi($url, $body);
        if ($extObj->success) {
            return $extObj->data;
        } else {
            return $extObj->errors;
        }
    }

    public function fetchEmployeeDirectory()
    {
        $url = env('API_URL') . 'employee-directory';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId')
        );

        try {
            $extObj = Helper::PostApi($url, $body);

            if ('true' == $extObj->success) {
                return $extObjData = $extObj->data;
            }
            if ($extObj->success == 'false') {
                return $extObj->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (extension-dashboard Count): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (extension-dashboard Count): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function liveCalls()
    {

        $url = env('API_URL') . 'live-call';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );


        // $cdr_report = Helper::PostApi($url,$body);
        //echo "<pre>"; echo $url ; print_r($body); exit;

        try {
            $cdr_report = Helper::PostApi($url, $body);
            if ($cdr_report->success == 'true') {
                return $report = $cdr_report->data;
                // echo "<pre>";print_r($report);die;
                //return back()->withSuccess($result->message);
                //return view('cdr_report.live-list',compact('report'));
            }

            if ($cdr_report->success == 'false') {
                return redirect('/');
                //return back()->withSuccess($result->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (live-call): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (live-call): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public static function getNotification()
    {
        $url = env('API_URL') . 'unread-sms-count';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );

        //  echo "<prE>";print_r($body);die;

        /* $smsObj = Helper::PostApi($url,$body);

         echo "<prE>";print_r($smsObj);die;*/

        $total_count = array();

        try {
            $smsObj = Helper::PostApi($url, $body);
            if ($smsObj->success == 'true') {


                $url = env('API_URL') . 'unread-mailbox';
                $body = array(
                    'id' => Session::get('id'),
                    'token' => Session::get('tokenId'),
                    'extension' => Session::get('extension'),
                );

                //echo "<pre>";print_r($body);die;
                $mailboxObj = Helper::PostApi($url, $body);
                //echo "<prE>";print_r($mailboxObj->data);die;

                $total_count['mailbox'] = $mailboxObj->data->record_count;
                $total_count['sms_unread'] = $smsObj->data->countRow;
                $total_count['total_sms'] = $mailboxObj->data->record_count + $smsObj->data->countRow;


                return $total_count;
                // echo "<pre>";print_r($sms_count);die;

            }
            if ($smsObj->success == 'false') {
                return $sms_count = $smsObj->data;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (User Count): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (User Count): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    /**
     * Get notitfication for unread sms, text and voicemail count
     * Data shown on header bell icon
     * @return type
     */
    public static function getNotificationCounts() {
        $total_count = array();
        try {
            //get unread sms count
            $url = env('API_URL') . 'unread-sms-count';
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
            );
            $smsObj = Helper::PostApi($url, $body);

            //get unread voicemail count
//            $url = env('API_URL') . 'unread-mailbox';
//            $body = array(
//                'id' => Session::get('id'),
//                'token' => Session::get('tokenId'),
//                'extension' => Session::get('extension'),
//            );
//            $mailboxObj = Helper::PostApi($url, $body);

            //get unread fax count
//            $url = env('API_URL') . 'get-unread-fax-count';
//            $body = array(
//                'id' => Session::get('id'),
//                'token' => Session::get('tokenId'),
//            );
//            $faxObj = Helper::PostApi($url, $body);

            $total_count['unread_mailbox_count'] = isset($mailboxObj->data->record_count) ? $mailboxObj->data->record_count : 0;
            $total_count['unread_sms_count'] = isset($smsObj->data->countRow) ? $smsObj->data->countRow : 0;
            $total_count['unread_fax_count'] = isset($faxObj->data->unreadFaxCount) ? $faxObj->data->unreadFaxCount : 0;
            $total_count['total_unread_count']  = $total_count['unread_mailbox_count'] + $total_count['unread_sms_count'] + $total_count['unread_fax_count'];

            return $total_count;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    function getSmsTemplete()
    {
        $url = env('API_URL') . 'sms-templete';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            /*'parent_id' => Session::get('parent_id'),*/
            'role' => 2
        );


        // echo "<pre>";print_r($body);die;
        try {
            $sms_templete = Helper::PostApi($url, $body);
            if ($sms_templete->success == 'true') {
                return $sms_templete_list = $sms_templete->data;
            }
            if ($sms_templete->success == 'false') {
                return $sms_templete->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (sms-templete): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (sms-templete): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getVoicemailUnreadCount()
    {
        $url = env('API_URL') . 'unread-mailbox';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'extension' => Session::get('extension'),
        );

        //echo "<pre>";print_r($body);die;

        //echo "<prE>";print_r($mailboxObj->data);die;

        try {
            $mailboxObj = Helper::PostApi($url, $body);
            if ($mailboxObj->success == 'true') {

                $total_count['mailbox'] = $mailboxObj->data->record_count;


                return $total_count;

            }

        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (voicemail Count): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (unread-mailbox): Oops something went wrong :( Please contact your administrator.)");
        }


        // echo "<pre>";print_r($sms_count);die;
    }

    function sendMail($data)
    {


        $to = "abhi2112mca@gmail.com";
        $subject = "Total Lead Delete";
        $txt = "Total deleted leads " . $data;
        $headers = "From: abhi2112mca@gmail.com" . "\r\n" .
            "CC: abhi2112mca@gmail.com";

        mail($to, $subject, $txt, $headers);


    }

    function getState($country_id)
    {
        $url = env('API_URL') . 'state-list';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'country_id' => $country_id,
        );

        try {
            $statelist = Helper::PostApi($url, $body);

            // echo "<pre>";print_r($did); exit;
            if ($statelist->success == 'true') {
                return $state_list = $statelist->data;
            }
            if ($statelist->success == 'false') {
                return $statelist->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (state-list): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (state-list): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getCountry()
    {
        $url = env('API_URL') . 'country-list';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        //echo "<pre>";print_r($body); exit;

        /* $countrylist = Helper::PostApi($url,$body);

         echo "<pre>";print_r($countrylist); die;*/
        try {
            $countrylist = Helper::PostApi($url, $body);

            // echo "<pre>";print_r($did); exit;
            if ($countrylist->success == 'true') {
                return $country_list = $countrylist->data;
            }
            if ($countrylist->success == 'false') {
                return $countrylist->message;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (country-list): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (country-list): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getLiveExtension()
    {
        $url = env('API_URL') . 'extension_live';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'is_deleted' => '0',
        );
        try {
            $ext = Helper::PostApi($url, $body);
            if ($ext->success == 'true') {
                return $label_list = $ext->data;
            }
            if ($ext->success == 'false') {
                return view('configuration.extlive');
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (extension_live): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (extension_live): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getFaxDidList($did){
        $url = env('API_URL') . 'fax-did';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'did' => $did,
        );

        try {
            $response = Helper::PostApi($url, $body);
            if ($response) {
                return $response;
            } else {
                return false;
            }
        } catch (RequestException $ex) {
            return false;
        }
    }

    function getUserFaxDidList(){
            $url = env('API_URL') . 'fax-did-user';
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId')
            );

            try {
                $response = Helper::PostApi($url, $body);
                if ($response) {
                    return $response;
                } else {
                    return false;
                }
            } catch (RequestException $ex) {
                $errors->add("error", $ex->getMessage());
                return view("email-did.edit")->withErrors($errors);
            }

        }

    /**
    * Get did department list
    * @return boolean
    */
    function getDepartmentList()
        {
            $url = env('API_URL') . 'get-department-list';
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId')
            );

            try {
                $response = Helper::PostApi($url, $body);
                if ($response) {
                    return $response;
                } else {
                    return false;
                }
            } catch (RequestException $ex) {
                return false;
            }
        }

}
