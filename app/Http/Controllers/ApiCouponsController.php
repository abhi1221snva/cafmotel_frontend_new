<?php
namespace App\Http\Controllers;
use App\Helper\Helper;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Session;

class ApiCouponsController extends Controller
{
    /**
    * List coupons
    * @return type
    */
    public function index()
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId')
        );
        $url = env('API_URL') . 'coupons-list';
        try {
            $response = Helper::GetApi($url, $body);
            $coupons_list = [];
            if($response->success == 'true')
            {
                $coupons_list = $response->data;
            }
        } catch (\Throwable $e) {
            Log::error("Failed to get coupons list in ApiCouponsController.getCouponsList", [
                "message" => $e->getMessage(),
                "line" => $e->getLine(),
                "file" => $e->getFile(),
                "code" => $e->getCode(),
            ]);
            $errors->add("error", "Error code - (add-list): Oops something went wrong (Please contact your administrator). " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($errors);
        }
        return view('coupon.index', ['coupons_list' => $coupons_list]);
    }
    
    /**
    * List coupons
    * @return type
    */
    public function detail($id = 0)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'coupon_id' => $id,
        );
        $url = env('API_URL') . 'coupon-detail';
        try {
            $coupon = Helper::PostApi($url, $body);
            $success = false;
            $msg = "No Record Found";
            $data = [];
            if($coupon->success == true)
            {
                $success = true;
                $msg = "Coupon Found";
                $data = $coupon->data;
            }
            
            return array(
                'success'=> $success,
                'message'=> $msg,
                'data'=> $data,
            );
            
        } catch (\Throwable $e) {
            Log::error("Failed to get coupons list in ApiCouponsController.getCouponsDetail", [
                "message" => $e->getMessage(),
                "line" => $e->getLine(),
                "file" => $e->getFile(),
                "code" => $e->getCode(),
            ]);
            $errors->add("error", "Error code - (add-list): Oops something went wrong (Please contact your administrator). " . $e->getMessage());
            return array(
                'success'=> 'false',
                'message'=> $errors,
            );
        }
        
    }
    
    /**
    * Edit coupons
    * @return type
    */
    public function edit(Request $request)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'coupon_id' => $request->coupon_id,
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'currency_code' => $request->currency_code,
            'amount' => $request->amount,
            'start_at' => $request->start_at,
            'expire_at' => $request->expire_at,
            'status' => $request->status,
        );
        $url = env('API_URL') . 'coupon-edit';
        try {
            $response = Helper::PostApi($url, $body);
            if($response->success == 'true')
            {
                return array(
                    'success'=> $response->success,
                    'message'=> $response->message,
                );
            }
            else
            {
                return array(
                    'success'=> $response->success,
                    'message'=> $response->errors,
                );
            }            
        } catch (\Throwable $e) {
            Log::error("Failed to get coupons list in ApiCouponsController.EditCoupon", [
                "message" => $e->getMessage(),
                "line" => $e->getLine(),
                "file" => $e->getFile(),
                "code" => $e->getCode(),
            ]);
            $errors->add("error", "Error code - (add-list): Oops something went wrong (Please contact your administrator). " . $e->getMessage());
            return array(
                'success'=> 'false',
                'message'=> $errors,
            );
        }
    }
    
    
/**************************************************/
    
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
                Log::error("Failed to edit DNC in ApiListController.Edit", [
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

}
