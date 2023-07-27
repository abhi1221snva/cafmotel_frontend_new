<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use  Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackMail;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;


class ForgotPasswordController extends Controller
{
    function index(Request $request,$token)
    {
        $errors = new MessageBag();
        try
        {
            $url = env('API_URL') . "check-forgot-password-link/$token";
            $response = Helper::GetApi($url, [], true);
            if ($response["success"])
            {
                $password_link = $response["data"];
            }
            else
            {
                return redirect('/')->withErrors($response['message']);
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
             return redirect('/')->withErrors($response['message']);
        }
        return view('users.forgot-password');
    }

    function changePassword(Request $request,$token)
    {
        $validator =  Validator::make($request->all(),[
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails())
        {
            return back()->withErrors($validator)->withInput();
        }

        $url = env('API_URL').'reset-password';
        $body=array('token'=>$token,'new_password' => $request->password);
        try
        {
            $result = Helper::PostApi($url,$body);
            if($result->success == 'true')
            {
                return redirect('/')->withSuccess($result->message);
            }

            if($result->success == 'false')
            {
                return back()->withSuccess($result->message);
            }
        }
        catch (RequestException $ex)
        {
            $errors->add("error", $ex->getMessage());
            return view("users.forgot-password", compact("errors", $errors));
        }
    }
}

