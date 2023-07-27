<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use App\Http\Controllers\InheritApiController;


class ApiRingGroupController extends Controller
{

  function getRingGroup(){
    $url = env('API_URL').'ring-group';

    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
    );

   // echo "<pre>";print_r($body);die;
      $ring_group = Helper::PostApi($url,$body);
  //  echo "<pre>";print_r($dnc);die;


    try
    {


      if($ring_group->success == 'true'){
        $ring_group = $ring_group->data;

         $inherit_list = new InheritApiController;
    $extension_list =  $inherit_list->getExtensionList();

    if(!is_array($extension_list))
    {
      $extension_list =array();
    }

    if(empty($extension_list))
    {
      if(empty(Session::get('tokenId')))
      {
        return redirect('/');
      }
    }

    //echo "<pre>";print_r($extension_list);die;

        return view('ringgroup.ring_group',compact('ring_group','extension_list'));
      }

      if($ring_group->success == 'false'){

          if(empty(Session::get('tokenId'))){
                    return redirect('/');

            }

        //return redirect('/');
     // return view('donotcall.dnc');

      }
    }

    catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
      }

    catch (RequestException $ex){
       return back()->with('message',"Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
    }
  }

  function storeRingGroup(Request $request){



if(!empty($request->ring_id)){


   $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'title' => $request->title,
      'description' => $request->description,

      'extension'=> $request->extensions,
      'emails'=> $request->emails,

      'ring_id' => $request->ring_id,
      'ring_type'=> $request->ring_type,
      
    );


   

          // echo "<pre>";print_r($body);die;

     $url = env('API_URL').'edit-ring-group';
            // echo "<pre>";print_r($body);die;

    //  $add_dnc = Helper::PostApi($url,$body);
             // echo "<pre>";print_r($add_dnc);die;

     try
     {
      $update_ring = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
      if($update_ring->success == 'true'){
              // echo "<pre>";print_r($group);die;
              //return back()->withSuccess($result->message);
        return back()->withSuccess($update_ring->message);
      }

      if($update_ring->success == 'false'){
       // return redirect('/');
                return back()->withSuccess($update_ring->message);
      }
    }


     catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (edit-update_ring): Oops something went wrong :( Please contact your administrator.)");
      }

    catch (RequestException $ex) {
       return redirect('/');
    }



  }

  else{


    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'title' => $request->title,
      'description' => $request->description,
        'extension'=> $request->extensions,
      'emails'=> $request->emails,
      'ring_type'=> $request->ring_type,

    );

            // echo "<pre>";print_r($body);die;

    $url = env('API_URL').'add-ring-group';
             //echo "<pre>";print_r($body);die;

     

    try{
      $add_ring_group = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
      if($add_ring_group->success == 'true'){
              // echo "<pre>";print_r($group);die;
              //return back()->withSuccess($result->message);
        return back()->withSuccess($add_ring_group->message);
      }

      if($add_ring_group->success == 'false'){
        return back()->withSuccess($ext_group->message);
      }
    }

     catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (add-ring-group): Oops something went wrong :( Please contact your administrator.)");
      }


    catch (RequestException $ex) {
    return back()->with('message',"Error code - (add-ring-group): Oops something went wrong :( Please contact your administrator.)");
    }
  }

}

function editRingGroup($number){

  $body=array(
    'id' => Session::get('id'),
    'token' => Session::get('tokenId'),
    'ring_id' => $number,

  );

           // echo "<pre>";print_r($body);die;

  $url = env('API_URL').'ring-group';
            // echo "<pre>";print_r($body);die;

    // $ext_group = Helper::PostApi($url,$body);
    // echo "<pre>";print_r($ext_group);die;

  try
  {
    $ext_group = Helper::PostApi($url,$body);
    /* echo "<pre>";print_r($ext_group);die;*/
    if($ext_group->success == 'true'){

      $group = $ext_group->data;

      return $group;




    }

    if($ext_group->success == 'false'){
      return redirect('/');
                //return back()->withSuccess($ext_group->message);
    }
  }


  catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
      }

  catch (RequestException $ex) {
    return back()->with('message',"Error code - (dnc): Oops something went wrong :( Please contact your administrator.)");
  }


}


function deleteRingGroup($ring_id){
            $body=array(
              'id' => Session::get('id'),
              'token' => Session::get('tokenId'),
              'ring_id' => $ring_id,
            
            );

            // echo "<pre>";print_r($body);die;

            $url = env('API_URL').'delete-ring-group';

             /*$delete_dnc = Helper::PostApi($url,$body);
              echo "<pre>";print_r($delete_dnc);die;
             //echo "<pre>";print_r($body);die;*/

            try
            {
              $delete_ring = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
              if($delete_ring->success == 'true'){
              // echo "<pre>";print_r($group);die;
              //return back()->withSuccess($result->message);
                return back()->withSuccess($delete_ring->message);
              }

              if($delete_ring->success == 'false'){
                return redirect('/');
                //return back()->withSuccess($ext_group->message);
              }
            }


            catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (delete-ring-group): Oops something went wrong :( Please contact your administrator.)");
      }

            catch (RequestException $ex) {
              return back()->with('message',"Error code - (delete-dnc): Oops something went wrong :( Please contact your administrator.)");
            }
          }


          function uploadExcel(){
            $file = $request->file('list_file');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().'.'.$extension;
            $rootPath = '/var/www/html/api/upload/';
            //$rootPath = 'C:\xampp\htdocs\api\upload\/';
            $file->move($rootPath, $filename);

            $body=array(
              'id' => Session::get('id'),
              'token' => Session::get('tokenId'),
              'file' => $filename,
            );

      // echo "<pre>";print_r($body);die;

    $url = env('API_URL').'add-list';
   // $ext_group = Helper::PostApi($url,$body);

    // return redirect('/editList/6/5');
            // echo "<pre>";print_r($ext_group);die;

    try{
      $add_list = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
      if($add_list->success == 'true'){

        $list_id = $add_list->list_id;
        $campaign_id = $add_list->campaign_id;

              return redirect('/editList/'.$list_id.'/'.$campaign_id);
      }

      if($add_list->success == 'false'){
        return redirect('/');
                //return back()->withSuccess($ext_group->message);
      }
    }

    
            catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (add-dnc): Oops something went wrong :( Please contact your administrator.)");
      }

    catch (RequestException $ex) {
     return back()->with('message',"Error code - (add-dnc): Oops something went wrong :( Please contact your administrator.)");
    }
          }
}

