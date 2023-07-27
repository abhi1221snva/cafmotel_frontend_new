<?php

namespace App\Http\Controllers;

use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;


class ApiApiController extends Controller
{
    function getApiList()
    {


        $inherit_list = new InheritApiController;
        $api_list = $inherit_list->getApiList();
        if (!is_array($api_list)) {
            $api_list = array();
        }


        if (empty($api_list)) {
            if (empty(Session::get('tokenId'))) {
                return redirect('/');

            }

        }

        //echo "<pre>";print_r($api_list);die;


        return view('configuration.api', compact('api_list'));
    }

    function storeApi(Request $request)
    {
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }

        $inherit_list = new InheritApiController;
        $label_list = $inherit_list->getLabel();
        $disposition_list = $inherit_list->getDisposition();
        $campaign_list = $inherit_list->getCampaign();

        if ($request->isMethod('post')) {

            if (empty($request->para_label) && empty($request->para_constant) )
            {
                return back()->with('message', "Please Add Parmeters");
            }

            if (!empty($request->para_label)) {
                $para_label = $request->para_label;
                $label = $request->label;

                if (!empty(sizeof($para_label))) {
                    for ( $i = 0; $i < sizeof($para_label); $i++ ) {
                        $para_label_data[$i]['parameter'] = $para_label[$i];
                        $para_label_data[$i]['value'] = $label[$i];
                        $para_label_data[$i]['type'] = 'label';
                    }
                }
            }

            $k = $i;
            if (!empty($request->para_constant)) {
                $para_constant = $request->para_constant;
                $constant = $request->constant;

                if (!empty(sizeof($para_constant))) {
                    for ( $j = 0; $j < sizeof($para_constant); $j++ ) {
                        $para_label_data[$i]['parameter'] = $para_constant[$j];
                        $para_label_data[$i]['value'] = $constant[$j];
                        $para_label_data[$i]['type'] = 'constant';

                        //$ApiConstant->type  = 'constant';
                    }
                }
            }

            $url = env('API_URL') . 'add-api';
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'title' => $request->title,
                "url" => $request->url,
                "method" => $request->get("method"),
                "campaign_id" => $request->campaign_id,
                "disposition" => $request->disposition_id,
                "is_default" => $request->is_default,
                //"para_label" => $request->para_label,
                //"label" => $request->label,
                //"para_constant" => $request->para_constant,
                //"constant" => $request->constant,
                "parameter" => $para_label_data
            );

            //echo "<pre>";print_r($body);die;


            try {
                $add_api = Helper::PostApi($url, $body);

                //echo "<pre>";print_r($add_api);die;
                if ($add_api->success == 'true') {
                    // $api = $add_api->data;
                    // echo "<pre>";print_r($report);die;
                    //return back()->withSuccess($result->message);

                    return back()->withSuccess($add_api->message);
                    // return view('configuration.add-api',compact('campaign_list','disposition_list','label_list'));
                    // return view('configuration.add-api',compact('group','disposition_list'));
                }

                if ($add_api->success == 'false') {

                    return redirect('/');
                    //return back()->withSuccess($result->message);
                }
            } catch (BadResponseException   $e) {
                return back()->with('message', "Error code - (add-api): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                return back()->with('message', "Error code - (add-api): Oops something went wrong :( Please contact your administrator.)");
                //return back()->withSuccess($message);
            }
        } else {
            return view('configuration.add-api', compact('campaign_list', 'disposition_list', 'label_list'));
        }
    }

    function deleteApi($api_id)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),

            'api_id' => $api_id

        );
//echo "<pre>";print_r($body);die;

        $url = env('API_URL') . 'delete-api';


        try {
            $delete_api = Helper::PostApi($url, $body);
            //echo "<pre>";print_r($ext_group);die;
            if ($delete_api->success == 'true') {
                // echo "<pre>";print_r($group);die;
                //return back()->withSuccess($result->message);
                return back()->withSuccess($delete_api->message);
            }

            if ($delete_api->success == 'false') {
                return redirect('/');
                //return back()->withSuccess($ext_group->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (delete-api): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (delete-api): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    function editApi($api_id, Request $request)
    {
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }
        $i = 0;
        $inherit_list = new InheritApiController;
        $label_list = $inherit_list->getLabel();
        $disposition_list = $inherit_list->getDisposition();
        $campaign_list = $inherit_list->getCampaign();

        if (!is_array($campaign_list)) {
            $campaign_list = array();
        }
        //echo "<pre>";print_r($campaign_list);die;
        if (!empty($api_id)) {
            $url = env('API_URL') . 'api-data';
            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'api_id' => $api_id
            );
            $api_detail_user = Helper::PostApi($url, $body);
            if (($api_detail_user->success)) {
                $api_data = $api_detail_user->data;
                $mapping = array();
                foreach ( $api_data->disposition as $map ) {
                    $mapping[] = $map->disposition_id;
                }
                return view('configuration.edit-api', compact('api_data', 'campaign_list', 'mapping', 'disposition_list', 'label_list'));
            } else {
                return redirect('/');
            }
            //return view('configuration.edit-api',compact('api_detail','mapping','campaign_list','disposition_list','label_list'));

        }


        // if(!empty($api_id)){
        //   $para_label = $request->para_label;
        //   $label = $request->label;
        //   if(!empty($request->para_label)){
        //     if(!empty(sizeof($para_label))){
        //       for($i = 0; $i < sizeof($para_label); $i++){
        //         $para_label_data[$i]['parameter']  = $para_label[$i];
        //         $para_label_data[$i]['value']  = $label[$i];
        //         $para_label_data[$i]['type']  = 'label';
        //       }
        //     }
        //   }
        //   $k = $i;
        //   if(!empty($request->para_constant)){
        //     $para_constant = $request->para_constant;
        //     $constant      = $request->constant;
        //     if(!empty(sizeof($para_constant))){
        //       for($j = 0; $j < sizeof($para_constant); $j++){
        //         $para_label_data[$i]['parameter']  = $para_constant[$j];
        //         $para_label_data[$i]['value']  = $constant[$j];
        //         $para_label_data[$i]['type']  = 'constant';
        //       }
        //     }
        //   }
        //   $url = env('API_URL').'edit-api';
        //   $body=array(
        //     'id' => Session::get('id'),
        //     'token' => Session::get('tokenId'),
        //     'title' => $request->title,
        //     'api_id' => $request->api_id,
        //     "url" => $request->url,
        //     "method" => $request->method,
        //     "campaign_id" => $request->campaign_id,
        //     "disposition" => $request->disposition_id,
        //     "parameter"=> $para_label_data
        //   );
        //   try
        //   {
        //     $add_api = Helper::PostApi($url,$body);
        //     if($add_api->success == 'true'){
        //       return back()->withSuccess($add_api->message);
        //     }
        //     if($add_api->success == 'false'){
        //       return redirect('/');
        //     }
        //   }
        //   catch (BadResponseException   $e) {
        //     return back()->with('message',"Error code - (edit-api): Oops something went wrong :( Please contact your administrator.)");
        //   }
        //   catch (RequestException $ex) {
        //     return back()->with('message',"Error code - (edit-api): Oops something went wrong :( Please contact your administrator.)");
        //   }
        // }else{
        //   $body=array(
        //     'id' => Session::get('id'),
        //     'token' => Session::get('tokenId'),
        //     'api_id' => $api_id,
        //   );
        //   $url = env('API_URL').'api';
        //   try{
        //     $api_detail = Helper::PostApi($url,$body);
        //     if($api_detail->success == 'true'){
        //       $api_data = $api_detail->data;
        //       $mapping=array();
        //       foreach($api_data->disposition as $map){
        //         $mapping[]  = $map->disposition_id;
        //       }
        //       return view('configuration.edit-api',compact('api_data','mapping','campaign_list','disposition_list','label_list'));
        //     }
        //     if($ext_group->success == 'false'){
        //       return redirect('/');
        //     }
        //   }
        //   catch (BadResponseException   $e) {
        //     return back()->with('message',"Error code - (api): Oops something went wrong :( Please contact your administrator.)");
        //   }
        //   catch (RequestException $ex) {
        //     return back()->with('message',"Error code - (api): Oops something went wrong :( Please contact your administrator.)");
        //   }
        // }
    }

    public function edit_save(Request $request)
    {
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }

        $para_label = $request->para_label;
        $label = $request->label;
        if (!empty($request->para_label)) {
            if (!empty(sizeof($para_label))) {
                for ( $i = 0; $i < sizeof($para_label); $i++ ) {
                    $para_label_data[$i]['parameter'] = $para_label[$i];
                    $para_label_data[$i]['value'] = $label[$i];
                    $para_label_data[$i]['type'] = 'label';
                }
            }
        }

        $k = $i;
        if (!empty($request->para_constant)) {
            $para_constant = $request->para_constant;
            $constant = $request->constant;
            if (!empty(sizeof($para_constant))) {
                for ( $j = 0; $j < sizeof($para_constant); $j++ ) {
                    $para_label_data[$i]['parameter'] = $para_constant[$j];
                    $para_label_data[$i]['value'] = $constant[$j];
                    $para_label_data[$i]['type'] = 'constant';
                    $i++;
                }
            }
        }

        $url = env('API_URL') . 'edit-api';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'title' => $request->title,
            'api_id' => $request->api_id,
            "url" => $request->url,
            "method" => $request->get("method"),
            "campaign_id" => $request->campaign_id,
            "disposition" => $request->disposition_id,
            "is_default" => $request->is_default,
            "parameter" => $para_label_data,
            "para_label" => $request->para_label,
            "para_constant" => $request->para_constant,

        );

        //echo "<pre>";print_r($body);die;

        try {
            $add_api = Helper::PostApi($url, $body);
            if ($add_api->success == 'true') {
                return back()->withSuccess($add_api->message);
            } else {
                return redirect('/');
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (edit-api): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (edit-api): Oops something went wrong :( Please contact your administrator.)");
        }
    }

    public function copyApi(Request $request)
    {
        if (empty(Session::get('tokenId'))) {
            return redirect('/');
        }
        $api_id = $request->id;
        $url = env('API_URL') . 'copy-api';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'api_id' => $api_id
        );
        //echo '<pre>'; echo $url; print_r($body); exit;
        try {
            $add_api = Helper::PostApi($url, $body);
            if ($add_api->success == 'true') {
                return back()->withSuccess($add_api->message);
            }
            if ($add_api->success == 'false') {
                return redirect('/');
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (edit-api): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (edit-api): Oops something went wrong :( Please contact your administrator.)");
        }
    }
}
