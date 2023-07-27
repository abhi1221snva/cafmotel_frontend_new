<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Config;

use Session;


class ApiCampaignController extends Controller
{

    function getCampaign()
    {
        $inherit_list = new InheritApiController;
        $campaign_list = $inherit_list->getCampaign();
        if (!is_array($campaign_list)) {
            $campaign_list = array();
        }
        if (empty($campaign_list)) {
            if (empty(Session::get('tokenId'))) {
                return redirect('/');
            }
        }
        return view('campaign.campaign', compact('campaign_list'));
    }

    function getExtensionList()
    {
        $url = env('API_URL') . 'active-extension-group-list';
        try
        {
            $response = Helper::PostApi($url);
            return $response;
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
        }
    }


    function storeCampaign(Request $request)
    {

        #hubspot

        $crm_lists = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "crm-lists";
        $response = Helper::GetApi($url);

        //echo "<pre>";print_r($response);die;
        try
        {
            if($response->success)
            {
                $crm_lists = $response->data;
            }
            
            else
            {
                $crm_lists = [];
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("campaign.add-campaign", compact("errors", $errors));
        }

        //echo "<pre>";print_r($crm_lists);die;



        $hubspot_lists = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "hubspot-lists";
        $response = Helper::GetApi($url);

        //echo "<pre>";print_r($response);die;
        try
        {
            if($response->success)
            {
                $hubspot_lists = $response->data;
            }
            
            else
            {
                $hubspot_lists = [];
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("campaign.add-campaign", compact("errors", $errors));
        }

//        echo "<pre>";print_r($hubspot_lists);die;



        #end hubspot

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
            return view("campaign.add-campaign", compact("errors", $errors));
        }

        //echo "<pre>";print_r($voip_configurations);die;


        $voice_templete_list = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "voice-templete";
        try
        {
            $response = Helper::GetApi($url);

           // echo "<pre>";print_r($response);die;
            if($response->success)
            {
                $voice_templete_list = $response->data;
            }
            else
            {
                $voice_templete_list = [];
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }
        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("campaign.add-campaign", compact("errors", $errors));
        }

        $inherit_list = new InheritApiController;
        $api_list = $inherit_list->getApiList();
        if (!is_array($api_list)) {
            $api_list = array();
        }


        $campaign_type_list = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "campaign-type";
        try
        {
          $response = Helper::GetApi($url);
           // echo "<pre>";print_r($response);die;
          if($response->success)
          {
            $campaign_type_list = $response->data;
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
          return view("campaign.add-campaign", compact("errors", $errors));
        }

        //echo "<pre>";print_r($api_list);die;

        $audio_message = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "audio-message";
        try
        {
          $response = Helper::GetApi($url);
           // echo "<pre>";print_r($response);die;
          if($response->success)
          {
            $audio_message = $response->data;
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
          return view("campaign.add-campaign", compact("errors", $errors));
        }

        /* Phone Country list */
        $list = [];
        $url = env('API_URL').'country-list';
        try
        {
            $response = Helper::PostApi($url);
            //echo "<pre>";print_r($response);die;
            if ($response->success)
            {
                $phone_country = $response->data;
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
        }
        
        $inherit_list = new InheritApiController;

        $ivr_list = $inherit_list->getIvr();
        if (!is_array($ivr_list)) {
            $ivr_list = array();
        }

       // echo "<pre>";print_r($ivr_list);die;
        /*$extension_list = $inherit_list->getExtension();
        if (!is_array($extension_list)) {
            $extension_list = array();
        }*/
        $extension_list = $this->getExtensionList();

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
        if (!empty($request->title)) {

            $disposition = $request->disposition;
            foreach ( $disposition as $dispocampaign ) {
                $disposition_array[] = $dispocampaign;
            }

            if($request->dial_mode == 'predictive_dial')
            {
                $call_ratio = $request->call_ratio;
                $duration = $request->duration;

                if($request->amd == 1)
                {
                    $amd_drop_action = $request->amd_drop_action;
                    if($amd_drop_action == 2)
                    {
                        $voicedrop_option_user_id = $request->audio_message_amd;
                    }
                     else
                        if($amd_drop_action == 3)
                    {
                        $voicedrop_option_user_id = $request->voice_message_amd;
                    }
                    else
                    {
                        $voicedrop_option_user_id=0;
                    }
                }
                else
                {
                    $amd_drop_action = 0;
                    $voicedrop_option_user_id = 0;
                }

                if($request->no_agent_available_action == 1)
                {
                    $no_agent_available_action = $request->no_agent_available_action;
                    $no_agent_dropdown_action = 0;

                }

                else
                if($request->no_agent_available_action == 2)
                {
                    $no_agent_available_action = $request->no_agent_available_action;
                    $no_agent_dropdown_action = $request->voicedrop_no_agent_available_action;

                }

                else
                if($request->no_agent_available_action == 3)
                {
                    $no_agent_available_action = $request->no_agent_available_action;
                    $no_agent_dropdown_action = $request->inbound_ivr_no_agent_available_action;

                }

                $redirect_to = 0;
                $redirect_to_dropdown = 0;
            

            }

            else
                if($request->dial_mode == 'outbound_ai')
            {
                $call_ratio = $request->call_ratio;
                $duration = $request->duration;

                if($request->amd == 1)
                {
                    $amd_drop_action = $request->amd_drop_action;
                    if($amd_drop_action == 2)
                    {
                        $voicedrop_option_user_id = $request->audio_message_amd;
                    }
                     else
                        if($amd_drop_action == 3)
                    {
                        $voicedrop_option_user_id = $request->voice_message_amd;
                    }
                    else
                    {
                        $voicedrop_option_user_id=0;
                    }
                }
                else
                {
                    $amd_drop_action = 0;
                    $voicedrop_option_user_id = 0;
                    $no_agent_dropdown_action = 0;

                }

                    $no_agent_dropdown_action = 0;
                $no_agent_available_action = 0;



               

                $redirect_to = $request->redirect_to;

                if($redirect_to == '1')
                {

                    $redirect_to_dropdown = $request->outbound_ai_dropdown_audio_message;

                }
                   if($redirect_to == '2')
                {

                    $redirect_to_dropdown = $request->outbound_ai_dropdown_voice_message;

                }
                if($redirect_to == '3')
                {

                    $redirect_to_dropdown = $request->outbound_ai_dropdown_extension;

                }


                 if($redirect_to == '4')
                {

                    $redirect_to_dropdown = $request->outbound_ai_dropdown_ring_group;

                }
                if($redirect_to == '5')
                {

                    $redirect_to_dropdown = $request->outbound_ai_dropdown_ivr;

                }




            }

            else
            {
                $call_ratio = 1;
                $duration = 0;
                $amd_drop_action = 0;
                $voicedrop_option_user_id = 0;
                $redirect_to = 0;
                $no_agent_available_action = 0;
                $no_agent_dropdown_action = 0;
                $redirect_to_dropdown=0;

            }


            if(!empty($request->voip_configurations))
            {
                $voip_configurations = $request->voip_configurations;
            }
            else
            {
                $voip_configurations = 0;
            }


            /*if(!empty($request->crm_url))
            {
                $crm_url = $request->crm_url;
            }
            else
            {
                $crm_url='';
            }*/

            $crm_title_url = $request->input('crm_title_url', null);
            $hubspot_lists = $request->input('hubspot_lists', null);

            //echo "<pre>";print_r($hubspot_lists);die;



            
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'caller_id' => $request->caller_id,
                'custom_caller_id' => $request->custom_caller_id,
                'time_based_calling' => $request->time_based_calling,
                'call_time_start' => $request->call_time_start,
                'call_time_end' => $request->call_time_end,
                'dial_mode' => $request->dial_mode,
                'group_id' => $request->group_id,
                //'max_lead_temp'=> $request->max_lead_temp,
                //'min_lead_temp'=> $request->min_lead_temp,
                'group_id' => $request->group_id,
                'send_report' => $request->send_report,
                'disposition_id' => $disposition_array,
                'sms' => $request->sms,
                'email' => $request->email,
                'send_crm' => $request->send_crm,
                'hopper_mode' => $request->hopper_mode,
                'call_ratio' => $call_ratio,
                'duration' => $duration,
                'automated_duration' => $request->automated_duration,
                'amd' => $request->amd,
                'amd_drop_action' => $amd_drop_action,
                'voicedrop_option_user_id' => $voicedrop_option_user_id,
                'no_agent_available_action' => $no_agent_available_action,
                'no_agent_dropdown_action' => $no_agent_dropdown_action,
                'redirect_to' => $redirect_to,
                'redirect_to_dropdown' => $redirect_to_dropdown,
                'country_code'=>$request->country_code,
                'api_id' =>$request->api_id,
                'voip_configuration_id' =>$voip_configurations,
                'crm_title_url' =>$crm_title_url,
                'hubspot_lists' =>$hubspot_lists,

            );

            //echo "<pre>";print_r($body);die;

            $url = env('API_URL') . 'add-campaign';
            //echo "<pre>";echo $url; print_r($body);die;

            try {
                $addcampaign = Helper::PostApi($url, $body);
               //echo "<pre>";print_r($addcampaign);die;
                if ($addcampaign->success == 'true') {


                    /*$campaignId = $addcampaign->data->id;

                   foreach($disposition as $dispocampaign){
                     $disposition_array[] = $dispocampaign;
                   }

                   $body = array(
                     'id' => Session::get('id'),
                     'token' => Session::get('tokenId'),
                     'campaign_id'=>$campaignId,
                     'disposition_id'=>$disposition_array
                   );
                   $url = env('API_URL').'edit-campaign-disposition';

                   $campaign_disposition = Helper::PostApi($url,$body);*/
                    return redirect('/campaign')->withSuccess($addcampaign->message);
                }

                if ($addcampaign->success == 'false') {
                    return back()->withSuccess($addcampaign->message);
                }
            } catch (BadResponseException   $e) {
                return back()->with('message', "Error code - (add-campaign): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {

                return back()->with('message', "Error code - (add-campaign): Oops something went wrong :( Please contact your administrator.)");
            }
        } else {

            $inherit_list = new InheritApiController;
            $did_list = $inherit_list->getDidList();
            if(!is_array($did_list))
            {
                $did_list = array(); // checking empty record
            }

            $errors = new MessageBag();
            $disposition_list = [];
            $group = [];
            $url = env('API_URL') . 'disposition';
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
            );
            try {
                $disposition = Helper::PostApi($url, $body);
                if ($disposition->success) {
                    $disposition_list = $disposition->data;
                } else {
                    $errors->add("error", $disposition->message);
                    return redirect()->back()->withInput()->withErrors($errors);
                }
            } catch (\Throwable $e) {
                Log::warning("Failed to fetch dispositon in ApiCampaignController.storeCampaign", [
                    "message" => $e->getMessage(),
                    "line" => $e->getLine(),
                    "file" => $e->getFile(),
                    "code" => $e->getCode()
                ]);
                $errors->add("error", $e->getMessage());
                return redirect()->back()->withInput()->withErrors($errors);
            }

            try {
                $url = env('API_URL') . 'extension-group';
                $response = Helper::GetApi($url);
                if ($response->success) {
                    $group = $response->data;
                    return view('campaign.add-campaign', compact('extension_list','ring_group_list','conferencing','ivr_list','destTypeList','did_list','group', 'disposition_list','phone_country','audio_message','api_list','campaign_type_list','voice_templete_list','voip_configurations','hubspot_lists','crm_lists'));
                } else {
                    $errors->add("error", $response->message);
                    return redirect()->back()->withInput()->withErrors($errors);
                }
            } catch (\Throwable $e) {
                Log::error("Failed to fetch group in ApiCampaignController.storeCampaign", [
                    "message" => $e->getMessage(),
                    "line" => $e->getLine(),
                    "file" => $e->getFile(),
                    "code" => $e->getCode()
                ]);
                $errors->add("error", $e->getMessage());
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }
    }

    public function showEditCampaign(int $campaignId)
    {

        #hubspot

        $crm_lists = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "crm-lists";
        $response = Helper::GetApi($url);

        //echo "<pre>";print_r($response);die;
        try
        {
            if($response->success)
            {
                $crm_lists = $response->data;
            }
            
            else
            {
                $crm_lists = [];
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("campaign.edit-campaign", compact("errors", $errors));
        }

        //echo "<pre>";print_r($crm_lists);die;



        $hubspot_lists = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "hubspot-lists";
        $response = Helper::GetApi($url);

        //echo "<pre>";print_r($response);die;
        try
        {
            if($response->success)
            {
                $hubspot_lists = $response->data;
            }
            
            else
            {
                $hubspot_lists = [];
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }

        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("campaign.edit-campaign", compact("errors", $errors));
        }

//        echo "<pre>";print_r($hubspot_lists);die;



        #end hubspot

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
            return view("campaign.edit-campaign", compact("errors", $errors));
        }

        $campaign_type_list = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "campaign-type";
        try
        {
          $response = Helper::GetApi($url);
           // echo "<pre>";print_r($response);die;
          if($response->success)
          {
            $campaign_type_list = $response->data;
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
          return view("campaign.edit-campaign", compact("errors", $errors));
        }


        $audio_message = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "audio-message";
        try
        {
          $response = Helper::GetApi($url);
           // echo "<pre>";print_r($response);die;
          if($response->success)
          {
            $audio_message = $response->data;
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
          return view("audio-message.list", compact("errors", $errors));
        }

         /* Phone Country list */
        $list = [];
        $url = env('API_URL').'country-list';
        try
        {
            $response = Helper::PostApi($url);
            if ($response->success)
            {
                $phone_country = $response->data;
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
        }


        $errors = new MessageBag();
        $inherit_list = new InheritApiController;
                
        $ivr_list = $inherit_list->getIvr();
        if (!is_array($ivr_list)) {
            $ivr_list = array();
        }
        /*$extension_list = $inherit_list->getExtension();
        if (!is_array($extension_list)) {
            $extension_list = array();
        }*/

        $extension_list = $this->getExtensionList();


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

        $did_list = $inherit_list->getDidList();
        if(!is_array($did_list))
        {
            $did_list = array(); // checking empty record
        }
        $userDisposition = array();
        $campaign_list = [];
        $group = [];
        $disposition_list = [];
        $mapping = [];
        $userDisposition = [];
        try {
            $url = env('API_URL') . 'disposition';
            $response = Helper::PostApi($url);
            if ($response->success == 'true') {
                $disposition_list = $response->data;
                $urlDbc = env('API_URL') . 'disposition_by_campaignId';
                $bodyDbc = array(
                    'campaign_id' => $campaignId
                );
                $disposition_Dbc = Helper::PostApi($urlDbc, $bodyDbc);
                if (isset($disposition_Dbc->data)) {
                    foreach ( $disposition_Dbc->data as $key => $val ) {
                        $userDisposition[] = $val->id;
                    }
                }
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

        try {
            $url = env('API_URL') . 'campaign-by-id';
            $body = array(
                'campaign_id' => $campaignId
            );
            $response = Helper::PostApi($url, $body);
			
            //if ($response->success == 'true') {
			if(!empty($response)){	
                $campaign_list = $response;
            } else {
               // foreach ($response->errors as $key => $message) {
                 //   $errors->add($key, $message);
               // }
                return redirect()->back()->withErrors($errors);
            }
        } catch (\Throwable $e) {
            $errors->add("error", $e->getMessage());
            return redirect()->back()->withErrors($errors);
        }

        try {
            $url = env('API_URL') . 'extension-group';
            $response = Helper::GetApi($url);
            if ($response->success) {
                $group = $response->data;
                $body = array(
                    'campaign_id' => $campaignId
                );
                $url = env('API_URL') . 'campaign-disposition';
                $campaign_disposition = Helper::PostApi($url, $body);
                $mapping = array();
                foreach ( $campaign_disposition->data as $map ) {
                    $mapping[] = $map->disposition_id;
                }
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


        $no_of_campaign_list = [];
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'campaign_id' => $campaignId,
            'is_deleted' => '0'
        );

        $errors = new MessageBag();
        $url = env('API_URL') . "campaign-list";

        try
        {

                    $response = Helper::PostApi($url, $body);

           // echo "<pre>";print_r($response);die;
            if($response->success)
            {
                $no_of_campaign_list = $response->data;
            }
            else
            {
                $no_of_campaign_list = [];
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }
        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("campaign.edit-campaign", compact("errors", $errors));
        }


       


        $voice_templete_list = [];
        $errors = new MessageBag();
        $url = env('API_URL') . "voice-templete";
        try
        {
            $response = Helper::GetApi($url);

           // echo "<pre>";print_r($response);die;
            if($response->success)
            {
                $voice_templete_list = $response->data;
            }
            else
            {
                $voice_templete_list = [];
                foreach ($response->errors as $key => $message)
                {
                    $errors->add($key, $message);
                }
            }
        }
        catch(RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("campaign.add-campaign", compact("errors", $errors));
        }

        return view('campaign.edit-campaign', compact('extension_list','conferencing','ring_group_list','ivr_list','destTypeList','did_list','campaign_list', 'group', 'disposition_list', 'mapping', 'userDisposition','no_of_campaign_list','phone_country','audio_message','campaign_type_list','voice_templete_list','voip_configurations','hubspot_lists','crm_lists'));
    }

    function editCampaign(Request $request)
    {
        $edit_disposition = array_unique($request->edit_disposition);
        foreach ( $edit_disposition as $edit_dispocampaign ) {
            $edit_disposition_array[] = $edit_dispocampaign;
        }

        $call_time_start = explode(":", $request->call_time_start);

        $start_time = $call_time_start[0] . ':' . $call_time_start[1];

        $call_time_end = explode(":", $request->call_time_end);

        $end_time = $call_time_end[0] . ':' . $call_time_end[1];


        //if(!empty($request->title)){


        if($request->dial_mode == 'predictive_dial')
        {
            $call_ratio = $request->call_ratio;
            $duration = $request->duration;
            $amd = $request->amd;


            if($request->amd == 1)
                {
                    $amd_drop_action = $request->amd_drop_action;
                    if($amd_drop_action == 2)
                    {
                        $voicedrop_option_user_id = $request->audio_message_amd;
                    }
                     else
                        if($amd_drop_action == 3)
                    {
                        $voicedrop_option_user_id = $request->voice_message_amd;
                    }
                    else
                    {
                        $voicedrop_option_user_id=0;
                    }
                }



            else
            {
                $amd_drop_action = 0;
                $voicedrop_option_user_id = 0;
            }


            if($request->no_agent_available_action == 1)
                {
                    $no_agent_available_action = $request->no_agent_available_action;
                    $no_agent_dropdown_action = 0;

                }

                else
                if($request->no_agent_available_action == 2)
                {
                    $no_agent_available_action = $request->no_agent_available_action;
                    $no_agent_dropdown_action = $request->voicedrop_no_agent_available_action;

                }

                else
                if($request->no_agent_available_action == 3)
                {
                    $no_agent_available_action = $request->no_agent_available_action;
                    $no_agent_dropdown_action = $request->inbound_ivr_no_agent_available_action;

                }

            $redirect_to=0;

            $redirect_to_dropdown = 0;

        }

        else
            if($request->dial_mode == 'outbound_ai')
        {
            $call_ratio = $request->call_ratio;
            $duration = $request->duration;
            $amd = $request->amd;


            if($request->amd == 1)
                {
                    $amd_drop_action = $request->amd_drop_action;
                    if($amd_drop_action == 2)
                    {
                        $voicedrop_option_user_id = $request->audio_message_amd;
                    }
                     else
                        if($amd_drop_action == 3)
                    {
                        $voicedrop_option_user_id = $request->voice_message_amd;
                    }
                    else
                    {
                        $voicedrop_option_user_id=0;
                    }
                }



            else
            {
                $amd_drop_action = 0;
                $voicedrop_option_user_id = 0;
            }


            if($request->no_agent_available_action == 1)
                {
                    $no_agent_available_action = $request->no_agent_available_action;
                    $no_agent_dropdown_action = 0;

                }

                else
                if($request->no_agent_available_action == 2)
                {
                    $no_agent_available_action = $request->no_agent_available_action;
                    $no_agent_dropdown_action = $request->voicedrop_no_agent_available_action;

                }

                else
                if($request->no_agent_available_action == 3)
                {
                    $no_agent_available_action = $request->no_agent_available_action;
                    $no_agent_dropdown_action = $request->inbound_ivr_no_agent_available_action;

                }

                $redirect_to = $request->redirect_to;

                  $no_agent_dropdown_action = 0;
                $no_agent_available_action = 0;
            $redirect_to_dropdown = 0;
                



               

                 if($redirect_to == '1')
                {

                    $redirect_to_dropdown = $request->outbound_ai_dropdown_audio_message;

                }

                  if($redirect_to == '2')
                {

                    $redirect_to_dropdown = $request->outbound_ai_dropdown_voice_message;

                }

                if($redirect_to == '3')
                {

                    $redirect_to_dropdown = $request->outbound_ai_dropdown_extension;

                }


                 if($redirect_to == '4')
                {

                    $redirect_to_dropdown = $request->outbound_ai_dropdown_ring_group;

                }
                if($redirect_to == '5')
                {

                    $redirect_to_dropdown = $request->outbound_ai_dropdown_ivr;

                }


        }

      
        else
        {
            $call_ratio = 1;
            $duration = 0;
            $amd_drop_action = 0;
            $voicedrop_option_user_id = 0;
            $amd = 0;
            $redirect_to = 0;
                $no_agent_available_action = 0;
                $no_agent_dropdown_action = 0;
               $redirect_to_dropdown = 0;

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
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'campaign_id' => $request->campaign_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'caller_id' => $request->caller_id,
            'custom_caller_id' => $request->custom_caller_id,
            'time_based_calling' => $request->time_based_calling,
            'call_time_start' => $start_time,//'09:30',//$request->call_time_start,
            'call_time_end' => $end_time,//'09:30', //$request->call_time_end,
            'dial_mode' => $request->dial_mode,
            'group_id' => $request->group_id,
            'send_report' => $request->send_report,
            'disposition_id' => $edit_disposition_array,
            'sms' => $request->sms,
            'send_crm' => $request->send_crm,
            'email' => $request->email,
            'hopper_mode' => $request->hopper_mode,
            'call_ratio' => $call_ratio,
            'duration' => $duration,
            'automated_duration' => $request->automated_duration,
            'amd' => $amd,
            'amd_drop_action' => $amd_drop_action,
            'voicedrop_option_user_id' => $voicedrop_option_user_id,
            'no_agent_available_action' => $no_agent_available_action,
            'no_agent_dropdown_action' => $no_agent_dropdown_action,
            'redirect_to' => $redirect_to,
            'redirect_to_dropdown' => $redirect_to_dropdown,
            'country_code'=>$request->country_code,
            'voip_configuration_id' => $voip_configurations






        );
        $url = env('API_URL') . 'edit-campaign';

        //echo "<pre>";print_r($body);die;


        try {
            $addcampaign = Helper::PostApi($url, $body);
            if ($addcampaign->success == 'true') {
                return back()->withSuccess($addcampaign->message);
            }

            if ($addcampaign->success == 'false') {
                return back()->withSuccess($addcampaign->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (edit-campaign-disposition): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (edit-campaign-disposition): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public function getExtensionCount()
    {
        $this->validate($this->request, [
            'extension_id' => 'numeric',
            'id' => 'required|numeric'
        ]);
        $response = $this->model->extensionDetail($this->request);
        return response()->json($response);
    }

    function deleteCampaign($campaign_id)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'campaign_id' => $campaign_id,
            'is_deleted' => '1'
        );

        // echo "<pre>";print_r($body);die;

        $url = env('API_URL') . 'delete-campaign';
        // echo "<pre>";print_r($body);die;

        try {
            $ext_group = Helper::PostApi($url, $body);
            //echo "<pre>";print_r($ext_group);die;
            if ($ext_group->success == 'true') {
                // echo "<pre>";print_r($group);die;
                //return back()->withSuccess($result->message);
                return back()->withSuccess($ext_group->message);
            }

            if ($ext_group->success == 'false') {
                return redirect('/');
                //return back()->withSuccess($ext_group->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (edit-campaign): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (edit-campaign): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function getCampaignList($list = 0, $campaignId = 0)
    {
        //echo $campaignId;

        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'parent_id' => Session::get('parentId'),

            'campaign_id' => $campaignId,
            'is_deleted' => '0'
        );

        $url = env('API_URL') . 'campaign-list';

        try {
            $campaign_list = Helper::PostApi($url, $body);

            //echo "<pre>";print_r($campaign_list);die;
            if ($campaign_list->success == 'true') {

                $camp_list = $campaign_list->data;

                return view('campaign.campaign-list', compact('camp_list'));

            }

            if ($campaign_list->success == 'false') {
                //  return redirect('/');
                return back()->withSuccess($campaign_list->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (edit-campaign): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (edit-campaign): Oops something went wrong :( Please contact your administrator.)");
        }


    }


    function listDisposition($list_id = 0)
    {
        /* echo $list_id;*/

        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'list_id' => $list_id,
        );

        $url = env('API_URL') . 'list-disposition';
        /* $list_disposition = Helper::PostApi($url,$body);

         echo "<pre>";print_r($list_disposition);die;*/

        try {
            $disposition_list = Helper::PostApi($url, $body);
            if ($disposition_list->success == 'true') {

                return $dispo_list = $disposition_list->data;


            }

            if ($disposition_list->success == 'false') {
                //  return redirect('/');
                return 0;
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (edit-campaign): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (edit-campaign): Oops something went wrong :( Please contact your administrator.)");
        }


    }

    function recycleListDisposition(Request $request)
    {

        $disposition = $request->param['disposition'];
        foreach ( $disposition as $dis => $dispositionId ) {
            $userCount[] = $request->param["select_id_" . $dispositionId];
        }

        // echo "<pre>";print_r($userCount);die;


        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'list_id' => $request->param['id'],
            'campaign_id' => $request->param['cid'],
            'disposition' => $request->param['disposition'],
            'select_id' => $userCount
        );

        $url = env('API_URL') . 'delete-list-disposition';
        try {
            $disposition_list_delete = Helper::PostApi($url, $body);
            if ($disposition_list_delete->success == 'true') {

                return back()->withSuccess($disposition_list_delete->message);


            }

            if ($disposition_list->success == 'false') {
                return back()->withSuccess($disposition_list_delete->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (edit-campaign): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (edit-campaign): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public function copyCampaign(Request $request)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'c_id' => $request->id
        );
        $url = env('API_URL') . 'copy-campaign';

        try {
            $addcampaign = Helper::PostApi($url, $body);
            if ($addcampaign->success == 'true') {
                return redirect('/campaign')->withSuccess($addcampaign->message);
            }

            if ($addcampaign->success == 'false') {
                return back()->withSuccess($addcampaign->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (copy-campaign): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {

            return back()->with('message', "Error code - (copy-campaign): Oops something went wrong :( Please contact your administrator.)");
        }

    }

    public function reloadHopper(Request $request, int $campaign)
    {
        if (Session::get("level") < 7) {
            return response()->json(["message" => "You are not authorize to reload campaign"], 401);
        }
        try {
            $url = env('API_URL') . "add-lead-temp?campaign_id=$campaign&id=" . Session::get("parentId");
            Helper::GetApi($url, [], true);
            return response()->json(["message" => "Campaign reload request sent"]);
        } catch (RequestException $ex) {
            return response()->json(["message" => $ex->getMessage()], 500);
        }
    }

    public function getContactInAList()
    {
        
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
}


