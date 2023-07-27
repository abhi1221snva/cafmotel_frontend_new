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





class AdminController extends Controller
{

  function index(){
    return view('admin.add-admin');
  }


  function addAdmin(Request $request){
    $body=array(
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email'=> $request->email,
        'mobile'=> $request->mobile,
        'password'=> Hash::make($request->password),
        'follow_me'=> $request->follow_me,
        'call_forward'=> $request->call_forward,
        'voicemail'=> $request->voicemail,
        'vm_pin'=> $request->vm_pin,
        'voicemail_send_to_email'=> $request->voicemail_send_to_email,
        'role'=> 1,
        'extension'=>$request->extension,
        'asterisk_server_id'=>1
      );


    //echo "<pre>";print_r($body);die;

    $users = new User();
    User::insert($body);
    return back()->withSuccess('Admin Added Successfully');

  }

  public function imageUploadPost(Request $request)
    {
      /*echo Session::get('id');die;

*/
 
  

        $update_user= User::where('id', Session::get('id'))->first();

       // echo "<pre>";print_r($update_user);die;
        $old_profile_pic = $update_user->profile_pic;


        if(!empty($old_profile_pic)){

        if ($request->hasFile('image')) {
            if(file_exists(public_path("profile-pic/$old_profile_pic"))){
                unlink(public_path("profile-pic/$old_profile_pic"));
            };
            }
        }
        //echo Auth::id();die;
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('profile-pic'), $imageName);

        //echo "<pre>";print_r($update_user);die;
        $update_user->profile_pic =$imageName;
        $update_user->save();

        /*return back()->withSuccess('User Updated Successfully');*/
        return back()->with('success','You have successfully change your profile image.')->with('image',$imageName);
    }


       function checkEmail(Request $request){
        $input['email'] = Input::get('email');
        $rules = array('email' => 'unique:users,email');
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            
           
            $update_user= User::where('email', $input['email'])->first();

            //echo "<pre>";print_r($update_user);die;
            //$update_user->email = $request['email'];
            $str = explode('@',$request['email']);
            $logo_pic = $update_user->logo;
            $name = $update_user->first_name.' '.$update_user->last_name; 
            $var = Str::random(32);
            $update_user->tokenId = $var;
            $update_user->save();
            //$ccEmail = env("MAIL_USERNAME");
            $ccEmail = 'mailme@rohitwanchoo.com';
            $link = url('')."/forgot-password?email=".$input['email']."&tokenId=".$update_user->tokenId;
            $toEmail = $input['email'];
            
            Mail::to($toEmail)->cc($ccEmail)->send(new FeedbackMail($link,$name,$logo_pic));
            echo "You recently requested to reset your password has been made, 
            please verify it by clicking the activation link that has been send to your email";
        }
        else 
        {
            echo 'That email address is already registered. You sure you don\'t have an account?';
           
        }
        die;

       
    }

    function forgotPassword(Request $request){
        $email = $request->email;
        $tokenId = $request->tokenId;

        $checkUser = User::where('email', $email)->where('tokenId',$tokenId)->first();

        if(!empty($checkUser)){
        return view('admin.forgot-password',compact('email','tokenId'));
        }
        else{
            return redirect('/');
        }
    }

    function resetPassword(Request $request){

      
       $password =  Hash::make($request->password);
       $email =  $request->email;
       $tokenId =  $request->tokenId;


        $updateUser = User::where('email', $email)->where('tokenId',$tokenId)->first();
        $updateUser->password = $password;
        $updateUser->tokenId = '';

        $updateUser->save();
        echo 'Your Password has been successfully changed.';




    }

    function sendMail(){
        
        Mail::send('emails.reply', $data, function($message){
        $message->from($data['email'] , $data['name']);
        $message->to('abc@asd.com' , 'team');
    });

    }

}

