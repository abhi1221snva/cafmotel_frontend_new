<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\MessageBag;


class ApiExcludeNumberController extends Controller
{

    function getExcludeNumber(Request $request)
    {
        $errors = new MessageBag();
        $url = env('API_URL') . 'campaign';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
        );
        $campaign = Helper::PostApi($url, $body);
        //echo "<pre>";print_r($campaign);die;

        if ($campaign->success == 'true') {
            $campaign_list = $campaign->data;

        } else {
            session()->flash("message", "Failed to get campaigns. ". $campaign->message);
            return view('donotcall.exclude_no')->with(['campaign_list' => []]);
        }
        // Default upper limit
    $upper_limit = 10;
  
    // Get selected value from filter
    $show = $request->input('show', 10);
        if ($show == 25 || $show == 50 || $show == 100) {
      $upper_limit = $show;
    }
  
    $urlpage = $request->page;
    
    $page=0;
    $lower_limit=0;
  
    if (!empty($urlpage) && $urlpage > 1)
    {
      $urlpage = $urlpage - 1;
      $lower_limit = $urlpage * $upper_limit;
    }
  
    if ($request->isMethod('post')) {
      $lower_limit = 0;
      $page=1;
    }
    $searchTerm = $request->input('search');


        $url = env('API_URL') . 'exclude-number';
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'lower_limit' => $lower_limit,
            'upper_limit' => $upper_limit,
            'search' => $searchTerm,

          

        );

        $exclude = Helper::PostApi($url, $body);
        //echo "<pre>";print_r($exclude);die;
        try
        {
          $exclude_list = $exclude->data;
          $record_count = $exclude->record_count;
          if (!empty($exclude_list)) 
        {
            return view('donotcall.exclude_no', compact('exclude_list','campaign_list','lower_limit','page','record_count','show','searchTerm'))->withErrors($errors);;
        } else
        {
            if (!empty($searchTerm)) 
            {
                // Search term is not found in the table
                return redirect('exclude-from-list')->with('error', 'Search term not found');
            } else
             {
                // Table is empty
                return view('donotcall.exclude_no', compact('exclude_list','campaign_list','lower_limit','page','record_count','show','searchTerm'))->withErrors($errors); ;
            }
        }
     
        }
      
        catch (BadResponseException $e)
        {
          return back()->with('message',"Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
        }
      
        catch (RequestException $ex)
        {
          return back()->with('message',"Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
        }
      }

     
    

    function storeExcludeNumber(Request $request)
    {


        if (!empty($request->file('exclude_file'))) {

            $file = $request->file('exclude_file');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $rootPath = '/var/www/html/api/upload/';
            //$rootPath = 'D:\xampp\htdocs\api\upload\/';
            $file->move($rootPath, $filename);

            $body = array(
                'id' => Session::get('id'),
                'token' => Session::get('tokenId'),
                'file' => $filename,
            );

            // echo "<pre>";print_r($body);die;


            $url = env('API_URL') . 'upload-exclude-number';
            //   $dnc_upload = Helper::PostApi($url,$body);

            // return redirect('/editList/6/5');
            //echo "<pre>";print_r($ext_group);die;

            try {
                $exclude_upload = Helper::PostApi($url, $body);
                //echo "<pre>";print_r($ext_group);die;
                if ($exclude_upload->success == 'true') {

                    unlink($rootPath . $filename);


                    return back()->withSuccess($exclude_upload->message);

                }

                if ($exclude_upload->success == 'false') {
                    // return redirect('/');
                    return back()->withSuccess($exclude_upload->message);
                }
            } catch (BadResponseException   $e) {
                return back()->with('message', "Error code - (upload-exclude-number): Oops something went wrong :( Please contact your administrator.)");
            } catch (RequestException $ex) {
                return back()->with('message', "Error code - (upload-exclude-number): Oops something went wrong :( Please contact your administrator.)");
            }
        } else
            if (!empty($request->exclude)) {


                $body = array(
                    'id' => Session::get('id'),
                    'token' => Session::get('tokenId'),
                    'number' => $request->number,
                    'campaign_id' => $request->exclude,

                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'company_name' => $request->company_name
                );

                // echo "<pre>";print_r($body);die;

                $url = env('API_URL') . 'edit-exclude-number';
                /* $add_dnc = Helper::PostApi($url,$body);
                         echo "<pre>";print_r($add_dnc);die;
            */
                try {
                    $add_dnc = Helper::PostApi($url, $body);
                    //echo "<pre>";print_r($ext_group);die;
                    if ($add_dnc->success == 'true') {
                        // echo "<pre>";print_r($group);die;
                        //return back()->withSuccess($result->message);
                        return back()->withSuccess($add_dnc->message);
                    }

                    if ($add_dnc->success == 'false') {
                        //return redirect('/');
                        return back()->withSuccess($add_dnc->message);
                    }
                } catch (BadResponseException   $e) {
                    return back()->with('message', "Error code - (edit-exclude-number): Oops something went wrong :( Please contact your administrator.)");
                } catch (RequestException $ex) {
                    return back()->with('message', "Error code - (edit-exclude-number): Oops something went wrong :( Please contact your administrator.)");
                }


            } else {
                $body = array(
                    'id' => Session::get('id'),
                    'token' => Session::get('tokenId'),
                    'number' => $request->number,
                    'campaign_id' => $request->campaign_id,

                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'company_name' => $request->company_name
                );

                /*echo "<pre>";
                print_r($body);
                die;*/

                $url = env('API_URL') . 'add-exclude-number';
                //echo "<pre>";print_r($body);die;

                try {
                    $exclude_number = Helper::PostApi($url, $body);
                    //echo "<pre>";print_r($ext_group);die;
                    if ($exclude_number->success == 'true') {
                        // echo "<pre>";print_r($group);die;
                        //return back()->withSuccess($result->message);
                        return back()->withSuccess($exclude_number->message);
                    }

                    if ($exclude_number->success == 'false') {
                        return redirect('/');
                        //return back()->withSuccess($ext_group->message);
                    }
                } catch (BadResponseException   $e) {
                    return back()->with('message', "Error code - (add-exclude-number): Oops something went wrong :( Please contact your administrator.)");
                } catch (RequestException $ex) {
                    return back()->with('message', "Error code - (add-exclude-number): Oops something went wrong :( Please contact your administrator.)");
                }
            }

    }

    function editExcludeNumber($number)
    {

        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'number' => $number,

        );

        // echo "<pre>";print_r($body);die;

        $url = env('API_URL') . 'exclude-number';
        // echo "<pre>";print_r($body);die;

        try {
            $ext_group = Helper::PostApi($url, $body);
            //echo "<pre>";print_r($ext_group);die;
            if ($ext_group->success == 'true') {

                $group = $ext_group->data;

                return $group;


            }

            if ($ext_group->success == 'false') {
                return redirect('/');
                //return back()->withSuccess($ext_group->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (exclude-number): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (exclude-number): Oops something went wrong :( Please contact your administrator.)");
        }


    }


    function deleteExcludeNo($number, $campaign_id)
    {
        $body = array(
            'id' => Session::get('id'),
            'token' => Session::get('tokenId'),
            'number' => $number,
            'campaign_id' => $campaign_id

        );

        // echo "<pre>";print_r($body);die;

        $url = env('API_URL') . 'delete-exclude-number';
        //echo "<pre>";print_r($body);die;

        try {
            $delete_dnc = Helper::PostApi($url, $body);
            //echo "<pre>";print_r($ext_group);die;
            if ($delete_dnc->success == 'true') {
                // echo "<pre>";print_r($group);die;
                //return back()->withSuccess($result->message);
                return back()->withSuccess($delete_dnc->message);
            }

            if ($delete_dnc->success == 'false') {
                return redirect('/');
                //return back()->withSuccess($ext_group->message);
            }
        } catch (BadResponseException   $e) {
            return back()->with('message', "Error code - (delete-exclude-number): Oops something went wrong :( Please contact your administrator.)");
        } catch (RequestException $ex) {
            return back()->with('message', "Error code - (delete-exclude-number): Oops something went wrong :( Please contact your administrator.)");
        }
    }
}

