<?php

namespace App\Http\Controllers;

use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use File;

class ApiIvrMenuController extends Controller {

    /**
    * iVR menu page
    * @return type
    */
    function getIvrMenu($ivr_id = -1) {
        $inherit_list = new InheritApiController;        
        $arrDestType = $arrIvr = $arrIvrMenu = $ivr_menu_details = [];        
        $arrDtmf = [0 => 0,1 => 1,2 => 2,3 => 3,4 => 4,5 => 5,6 => 6,7 => 7,8 => 8,9 => 9,'*' => '*', 'TO' => 'TimeOut'];
        
        $dest_list = $inherit_list->getDestType();
        foreach($dest_list as $dest) {
            $arrDestType[$dest->dest_id] = $dest->dest_type; 
        }

        $url = env('API_URL') . 'ivr-menu';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        $ivr_menu = Helper::PostApi($url, $body);
        
        foreach($ivr_menu->data as $ivrMenu) {
            $temp = [];
            $temp['id'] = $ivrMenu->id;
            $temp['ivr_id'] = $ivrMenu->ivr_id;
            $temp['ivr_desc'] = $ivrMenu->ivr_desc;
            $temp['ivr_m_id'] = $ivrMenu->ivr_m_id;
            $temp['dtmf'] = $ivrMenu->dtmf;
            $temp['dest_type'] = $ivrMenu->dest_type;
            $temp['dest'] = $ivrMenu->dest;
            $temp['is_deleted'] = $ivrMenu->is_deleted;
            $arrIvrMenu[$ivrMenu->ivr_id][] = $temp;
            $ivr_has_menu = 1;
            if($ivrMenu->dtmf == '' && $ivrMenu->is_deleted != 1) {
                $ivr_has_menu = "0";
            }
            $arrIvr[$ivrMenu->ivr_id] = ['desc' => $ivrMenu->ivr_desc, 'ivr_has_menu' => $ivr_has_menu];
        }

        if($ivr_id) {
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'ivr_id' => $ivr_id,
            );
            $url = env('API_URL') . 'ivr-menu';
            $ivr_menu_details = Helper::PostApi($url, $body);
            $ivr_menu_details = isset($ivr_menu_details->data) ? $ivr_menu_details->data : '';
        }

        $conferenceOptions = $this->getConferencing();
        $ringGroupOptions = $this->getRingGroup();
        $extensionOptions = $this->getClientExtension();
        $countryCodeOptions = $inherit_list->getCountry();

        try {
            return view('configuration.ivr_menu', compact('arrIvrMenu', 'arrDestType', 'arrIvr', 'arrDtmf', 'ivr_menu_details',
                    'conferenceOptions', 'ringGroupOptions', 'extensionOptions', 'countryCodeOptions', 'ivr_id'));
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (ivr menu): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    /**
    * Delete ivr menu from lost
    * @param type $auto_id
    * @return type
    */
    function deleteIvrMenu($auto_id) {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'auto_id' => $auto_id,
        );
        $url = env('API_URL') . 'delete-ivr-menu';

        try {
            $response = Helper::PostApi($url, $body);
            
            if ($response->success) {
                return response()->json($response->message);
            } else {
                return response()->json($response->message, 500);
            }
        } catch (BadResponseException $e) {
            return response()->json("Something went wrong!!", 500);
        } catch (RequestException $ex) {
            return response()->json("Something went wrong!!", 500);
        }
    }
    
    /**
    * Used for add / edit ivr menu
    * @param type $ivr_id
    * @param Request $request
    * @return type
    */
    function editIvrMenu(Request $request) {
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }

        $inherit_list = new InheritApiController;
        $dest_list = $inherit_list->getDestType();
        $ivr_list = $inherit_list->getIvr();

        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'parameter' => $request->all(),
        );
        $url = env('API_URL') . 'edit-ivr-menu';

        try {
            $ivr_menu_edit = Helper::PostApi($url, $body);
            if ($ivr_menu_edit->success == 'true') {
                return redirect('/ivr-menu')->withSuccess($ivr_menu_edit->message);
            }

            if ($ivr_menu_edit->success == 'false') {
                return back()->withError($ivr_menu_edit->message);
            }
        } catch (BadResponseException $e) {
            return back()->with('message', "Error code - (api): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (api): Oops something went wrong :( Please contact your administrator.)");
        }
    }
    
    /**
    * Get conferencing
    * @return type
    */
    private function getConferencing() {
        $url = env('API_URL').'conferencing';

        $body = array (
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        $conferencing = Helper::PostApi($url,$body);
        
        if($conferencing->success == 'true') {
            return $conferencing->data;
        } else {
            return [];
        }
    }
    
    /**
    * Get Ring group
    * @return type
    */
    private function getRingGroup() {
        $url = env('API_URL').'ring-group';
        $body = array (
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );

        $ring_group = Helper::PostApi($url, $body);
        
        if($ring_group->success == 'true') {
            return $ring_group->data;
        } else {
            return [];
        }
    }
    
    /**
    * Get Client extension
    * @return type
    */
    private function getClientExtension() {
        $url = env('API_URL').'get-client-extension';
        $body = array (
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );

        $ext = Helper::PostApi($url, $body);
        if($ext->success == 'true') {
            return $ext->data;
        } else {
            return [];
        }
    }


function storeIvrMenu(Request $request) {

        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }



        $inherit_list = new InheritApiController;
        $dest_list = $inherit_list->getDestType();

        //echo "<pre>";print_r($dest_list);die;

        $ivr_list = $inherit_list->getIvr();

        // echo "<pre>";print_r($ivr_list);die;

        if (!is_array($ivr_list)) {
            $ivr_list = array();
        }

        if (empty($ivr_list)) {
            if (empty(Session::get('tokenId'))) {
                return redirect('/');
            }
        }


        if ($request->isMethod('post')) {


            //dd($request->all());die;

            if (!empty($request->para_dtmf)) {


                $para_dtmf = $request->para_dtmf;
                $dest_type = $request->dest_type;

                if (!empty(sizeof($para_dtmf))) {
                    for ($i = 0; $i < sizeof($para_dtmf); $i++) {
                        $para_dtmf_data[$i]['dtmf'] = $para_dtmf[$i];
                        $para_dtmf_data[$i]['dest_type'] = $dest_type[$i];
                        $para_dtmf_data[$i]['ivr_id'] = $request->row_type;
                        $para_dtmf_data[$i]['dest'] = $request->dest;
                    }
                }
            }

            //echo "<pre>";print_r($para_dtmf_data);die;


            $k = $i;

            /*
              if(!empty($request->para_constant)){
              $para_constant = $request->para_constant;
              $constant      = $request->constant;



              if(!empty(sizeof($para_constant))){
              for($j = 0; $j < sizeof($para_constant); $j++)
              {
              $para_label_data[$i]['parameter']  = $para_constant[$j];
              $para_label_data[$i]['value']  = $constant[$j];
              $para_label_data[$i]['type']  = 'constant';

              //$ApiConstant->type  = 'constant';
              }
              }
              } */

            //echo "<pre>";print_r($para_label_data);die;
            // die;
            $url = env('API_URL') . 'add-ivr-menu';
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                //'dest' => $request->dest,
                /* "url" => $request->url,
                  "method" => $request->method,
                  "campaign_id" => $request->campaign_id,
                  "disposition" => $request->disposition_id,
                  //"para_label" => $request->para_label,
                  //"label" => $request->label,
                  //"para_constant" => $request->para_constant,
                  //"constant" => $request->constant, */
                "parameter" => $para_dtmf_data
            );

            //echo "<pre>";print_r($body);die;






            try {
                $add_ivr_menu = Helper::PostApi($url, $body);

                //echo "<pre>";print_r($add_api);die;
                if ($add_ivr_menu->success == 'true') {
                    // $api = $add_api->data;
                    // echo "<pre>";print_r($report);die;
                    //return back()->withSuccess($result->message);

                    return back()->withSuccess($add_ivr_menu->message);
                    // return view('configuration.add-api',compact('campaign_list','disposition_list','label_list'));
                    // return view('configuration.add-api',compact('group','disposition_list'));
                }

                if ($add_ivr_menu->success == 'false') {

                    return redirect('/');
                    //return back()->withSuccess($result->message);
                }
            } catch (BadResponseException $e) {
                return back()->with('message', "Error code - (add-ivr-menu): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                return back()->with('message', "Error code - (add-ivr-menu): Oops something went wrong :( Please contact your administrator.)");
                //return back()->withSuccess($message);
            }





            //add-api
        } else {

            //echo "<pre>";print_r($dest_list);die;
            return view('configuration.add-ivr-menu', compact('ivr_list', 'dest_list'));
        }
    }

    function checkDestType($dest_id) {


        // for ivr
        if ($dest_id == 0) {
            $inherit_list = new InheritApiController;
            $ivr_list = $inherit_list->getIvr();
            return $ivr_list;
            //echo "<pre>";print_r($ivr_list);die;
        }
    }

}
