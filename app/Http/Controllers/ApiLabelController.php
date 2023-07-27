<?php

namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\InheritApiController;
use GuzzleHttp\Exception\BadResponseException;



class ApiLabelController extends Controller
{
  function getLabel()
  {
    $inherit_label = new InheritApiController;
    $label_list =  $inherit_label->getLabel();

     if(!is_array($label_list)){
      $label_list =array();
    }



    if(empty($label_list)){
        if(empty(Session::get('tokenId'))){
                    return redirect('/');

            }

    }
    return view('configuration.label',compact('label_list'));
  }

  function storeLabel(Request $request)
  {

   

    if(!empty($request->label_id))
      {
        $body=array(
          'id' => Session::get('id'),
          'token' => Session::get('tokenId'),
          'label_id' => $request->label_id,
          'title' => $request->title
        );

     //echo "<pre>";print_r($body);die;

        $url = env('API_URL').'edit-label';
        // echo "<pre>";print_r($body);die;
        try
        {
          $ext_group = Helper::PostApi($url,$body);
          //echo "<pre>";print_r($ext_group);die;
          if($ext_group->success == 'true')
          {
          // echo "<pre>";print_r($group);die;
          //return back()->withSuccess($result->message);
            return back()->withSuccess($ext_group->message);
          }

          if($ext_group->success == 'false')
          {
            return redirect('/');
            //return back()->withSuccess($ext_group->message);
          }
        }

         catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (edit-label): Oops something went wrong :( Please contact your administrator.)");
      }

        catch (RequestException $ex)
        {
          return back()->with('message',"Error code - (edit-label): Oops something went wrong :( Please contact your administrator.)");
        }
      }
      else
      {
        $body=array(
          'id' => Session::get('id'),
          'token' => Session::get('tokenId'),
          'title' => $request->title
          );

         // echo "<pre>";print_r($body);die;

        $url = env('API_URL').'add-label';
        // echo "<pre>";print_r($body);die;

        try
        {
          $add_label = Helper::PostApi($url,$body);
          //echo "<pre>";print_r($ext_group);die;
          if($add_label->success == 'true')
          {
          // echo "<pre>";print_r($group);die;
          //return back()->withSuccess($result->message);
            return back()->withSuccess($add_label->message);
          }

          if($add_label->success == 'false')
          {
            return redirect('/');
            //return back()->withSuccess($ext_group->message);
          }
        }

          catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (add-label): Oops something went wrong :( Please contact your administrator.)");
      }


        catch (RequestException $ex)
        {
         return back()->with('message',"Error code - (add-label): Oops something went wrong :( Please contact your administrator.)");
        }
      }
    }

function editLabel($label_id){

  $body=array(
    'id' => Session::get('id'),
    'token' => Session::get('tokenId'),
    'label_id' => $label_id,

  );

            // echo "<pre>";print_r($body);die;

  $url = env('API_URL').'label';
            // echo "<pre>";print_r($body);die;

    $ext_group = Helper::PostApi($url,$body);
   //echo "<pre>";print_r($ext_group);die;

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
        return back()->with('message',"Error code - (label): Oops something went wrong :( Please contact your administrator.)");
      }

  catch (RequestException $ex) {
   return back()->with('message',"Error code - (label): Oops something went wrong :( Please contact your administrator.)");
  }


}


function deleteLabel($label_id){
            $body=array(
              'id' => Session::get('id'),
              'token' => Session::get('tokenId'),
              'label_id' => $label_id,
              'is_deleted' => 1
            
            );
//echo "<pre>";print_r($body);

            $url = env('API_URL').'edit-label';
             //echo "<pre>";print_r($body);die;

          /*  $delete_dnc = Helper::PostApi($url,$body);
              echo "<pre>";print_r($delete_dnc);die;*/

            try
            {
              $delete_dnc = Helper::PostApi($url,$body);
              //echo "<pre>";print_r($ext_group);die;
              if($delete_dnc->success == 'true'){
              // echo "<pre>";print_r($group);die;
              //return back()->withSuccess($result->message);
                return back()->withSuccess($delete_dnc->message);
              }

              if($delete_dnc->success == 'false'){
                return redirect('/');
                //return back()->withSuccess($ext_group->message);
              }
            }


             catch (BadResponseException   $e) {
        return back()->with('message',"Error code - (edit-label): Oops something went wrong :( Please contact your administrator.)");
      }

            catch (RequestException $ex) {
               return back()->with('message',"Error code - (edit-label): Oops something went wrong :( Please contact your administrator.)");
            }
          }

  function getLiveExtension()
  {
    $inherit_label = new InheritApiController;
    $label_list =  $inherit_label->getLiveExtension();
    if(!is_array($label_list)){
      $label_list =array();
    }
    
    if(empty($label_list)){
      if(empty(Session::get('tokenId'))){
        return redirect('/');
      }
    }
    return view('configuration.extlive',compact('label_list'));
  }

  public function deleteExtLiv($id){
    
    $body=array(
      'id' => Session::get('id'),
      'token' => Session::get('tokenId'),
      'sip' => $id
    );
    $url = env('API_URL').'delete-ext-live';
    
    try
    {
      $delete_dnc = Helper::PostApi($url,$body);
      if($delete_dnc->success == 'true'){
        return back()->withSuccess($delete_dnc->message);
      }
      if($delete_dnc->success == 'false'){
        return redirect('/');
      }
    }
    catch (BadResponseException   $e) {
      return back()->with('message',"Error code - (delete-extension): Oops something went wrong :( Please contact your administrator.)");
    }

    catch (RequestException $ex) {
       return back()->with('message',"Error code - (delete-extension): Oops something went wrong :( Please contact your administrator.)");
    }
  }

 
    
}

