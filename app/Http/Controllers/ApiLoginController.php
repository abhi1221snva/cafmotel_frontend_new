<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;

use Illuminate\Support\MessageBag;

class ApiLoginController extends Controller
{
    public function index(Request $request)
    {
        Session::forget('userMenu');
        Session::forget('permissions');
        return view('login.login');
    }

    function apilogin(Request $request)
    {
        $username = $request->email;
        $password = $request->password;
        $url = env('API_URL') . 'authentication';

        $body = array(
            'email' => $username,
            'password' => $password,
            'userAgent' => $request->userAgent(),
            'clientIp' => $request->ip(),
            'device'   => 'desktop_app'
        );

        try {
            $result = Helper::PostApi($url, $body);
            // echo "<pre>";print_r($result);die;
            if ($result->success)
            {
                if($result->data->enable_2fa == 1)
                {
                    Session::put('userTokenAllSession', $result);
                    return redirect('/otp');
                }
                
                Session::put('tokenId', $result->data->token);
                Session::put('parentId', $result->data->parent_id);  //parent id is roleid
                Session::put('id', $result->data->id);
                Session::put('extension', $result->data->extension);
                Session::put('role', $result->data->role);
                Session::put('emailId', $result->data->email);
                Session::put('vm_drop', $result->data->vm_drop);
                Session::put('level', $result->data->level);
                Session::put('permissions', (array)$result->data->permissions);
                Session::put('showQuestion', TRUE);

                Session::put('display_name', $result->data->first_name.' '.$result->data->last_name);
                Session::put('private_identity', $result->data->alt_extension);
                Session::put('password', $result->data->secret);
                Session::put('realm', $result->data->domain);
                Session::put('public_identity', "sip:".$result->data->alt_extension."@".$result->data->domain);
                Session::put('websocket_server_url', "wss://".$result->data->domain.":8089/ws");
                Session::put('ice_servers', "{ url:'turn:".env('ICE_SERVER_USER')."@".$result->data->domain."', credential:'".env('ICE_SERVER_PASSWORD')."'}");
                Session::put('timezone_session', 0);
                return redirect('/dialer-new');

            } else {
                $message = $result->message;
                if (isset($result->errors)) {
                    foreach ($result->errors as $error) {
                        $message .= " $error";
                    }
                }
                return redirect()->route('login')->with('message', $message);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [
                "url" => $url,
                "email" => $username
            ]);
            return redirect()->route('login')->with('message', 'Error code - (authentication): Oops something went wrong :( Please contact your administrator.)');
        }
    }

    public function switchClient($clientId)
    {
        $url = env('API_URL')."/switch-client/$clientId";
        $response = Helper::PostApi($url, null, null);
        if ($response->success) {
            Session::put('parentId', $response->data->parent_id);
            $permissions = Session::get('permissions');
            $permission = $permissions[$response->data->role];
            Session::put('role', $permission->roleName);
        }
        return response()->json($response);
    }

    function dashboard()
    {
        return view('dashboard.dashboard');
    }

    function otp(Request $request)
    {
        $mobile = '';
        $sessions=Session::all();
        $result = $sessions['userTokenAllSession'];
        //echo "<pre>";print_r($result);die;

        $errors = new MessageBag();

        if ($request->isMethod('POST'))
        {
            try
            {
                $url = env('API_URL') . 'validate-otp';

                $body = array(
                    'type' =>'phone',
                    'otpId'=>$result->data->otpId,
                    'id' => $result->data->id,
                    'code' => $request->otp,
                );

                $response = Helper::PostApi($url,$body);
                //echo "<pre>";print_r($response);die;

                if ($response->success)
                {
                    Session::put('tokenId', $result->data->token);
                    Session::put('parentId', $result->data->parent_id);  //parent id is roleid
                    Session::put('id', $result->data->id);
                    Session::put('extension', $result->data->extension);
                    Session::put('role', $result->data->role);
                    Session::put('emailId', $result->data->email);
                    Session::put('vm_drop', $result->data->vm_drop);
                    Session::put('level', $result->data->level);
                    Session::put('permissions', (array)$result->data->permissions);
                    Session::put('showQuestion', TRUE);
                    Session::put('display_name', $result->data->first_name.' '.$result->data->last_name);
                    Session::put('private_identity', $result->data->alt_extension);
                    Session::put('password', $result->data->secret);
                    Session::put('realm', $result->data->domain);
                    Session::put('public_identity', "sip:".$result->data->alt_extension."@".$result->data->domain);
                    Session::put('websocket_server_url', "wss://".$result->data->domain.":8089/ws");
                    Session::put('ice_servers', "{ url:'turn:".env('ICE_SERVER_USER')."@".$result->data->domain."', credential:'".env('ICE_SERVER_PASSWORD')."'}");
                    Session::put('timezone_session', 0);

                    return redirect('/dialer-new');
                }
                else
                {
                    return redirect('/otp')->with('message', $response->message);
                }
            }

            catch (RequestException $ex)
            {
                $errors->add("error", $ex->getMessage());
                return redirect('/otp')->with('message', $message);
            }
        }

        //$mobile = substr($result->data->mobile,-4);

        return view('login.otp',compact("result", $result));
    }

    public function logout()
    {
        $url = env('API_URL')."/user-logout";
        $body = array('logout_all' => 1);
        try
        {
            $logout = Helper::PostApi($url,$body);
        }
        catch (\Exception $exception)
        {}
        Session::flush();
        return redirect('/');
    }


    public function getIp(Request $request)
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (strlen($ip) > 8 && (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)){
                        return $ip;
                    }
                }
            }
        }
        return $request->ip();
    }
}

