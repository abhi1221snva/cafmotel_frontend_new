<?php
namespace App\Classes;

use App\Helper\Helper;
use App\Http\Controllers\PusherController;

class ApiClass {

    function helloWorld(){
        return 'hello world';
    }

    function receieveSms($response){

        if(empty($_GET['type']))
        {
            $type = 'didforsale';
        }
        else
        {
            $type = $_GET['type'];
        }

        $conn = mysqli_connect("localhost","root","HG@v2RM8ERULC","master");
        //$conn = mysqli_connect("localhost","root","","master");
        

        // Check connection
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();
        }

        if($type == 'plivo')
        {
            $from = $_REQUEST["From"];
            $to   =   $_REQUEST["To"];
            $message = $_REQUEST["Text"];

            /*$from = '13018446999';
            $to = '16313362141';
            $message = 'Hello test Portal';*/

        }

        else
        if($type == 'didforsale')
        {
            //{"from":"917838626612","to":["19027063135"],"text":"Hello test"}
            if(isset($response->from))
            {
                $from =  $response->from;
            }
            else
            {
                $from =  '917022126123';
            }
            if(isset($response->to[0]))
            {
                $to =  $response->to[0];
            }
            else
            {
                $to =  '16318010953';
            }

            $message =  $response->text;
        }

        $sql = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM did  WHERE cli='".$to."'"));

        if(!empty($sql))
        {
            $parent_id = $sql['parent_id'];
            $database = "client_".$parent_id;
            $user_id = $sql['user_id'];

            $conn1 = mysqli_connect("localhost","root","HG@v2RM8ERULC",$database);
            //$conn1 = mysqli_connect("localhost","root","",$database);
            //Android // ios
            $userToken = mysqli_fetch_assoc(mysqli_query($conn1,"SELECT * FROM user_token  WHERE userId='".$user_id."'"));

            //echo "<pre>";print_r($userToken);die;
            if(isset($userToken['deviceToken']) && isset($userToken['deviceType'])) {
                $deviceToken = $userToken['deviceToken'];
                $deviceType = $userToken['deviceType'];

                if(!empty($deviceToken))
                {
                    $title = "SMS Notification";
                    $body  = "SMS Received By ".$from;
                    $type  = "SMS";

                    $this->sendNotification($deviceToken,$title,$body,$type,$deviceType);
                }
            }

            //sending request to backed for billing
            $url = env('API_URL') . 'receive-sms';
            $body = array(
                'client_id' => $parent_id,
                'user_id' => $user_id,
                'from' => $from,
                'to' => $to,
                'message' => $message,
                'type' =>$type
            );

            $receiveSmsResponse = Helper::PostApi($url, $body);
            echo "<pre>";print_r($receiveSmsResponse);die;

            if($receiveSmsResponse->success){
                /*****Send pusher notifcaiton to user*****/
                $pusherObj = new PusherController();
                $pusherObj->sendPusherNotifcation($from, $to, 'text');
                /*****Send pusher notifcaiton to user*****/

                echo "sms received successfully";
            } else {
                echo "Failed to receive sms";
            }
        }
        else
        {
            echo "no entry";
        }
    }


    /*

    function receieveSms(){

    $conn = mysqli_connect("localhost","root","HG@v2RM8ERULC","master");
        //$conn = mysqli_connect("localhost","root","","master");
        // Check connection

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();
        }

        $response = json_decode(file_get_contents('php://input'));
        /*$from =  '917838626612';//
        $to =  '19027063135';//
        $message =  'Hello Abhishek';//

        $from =  $response['from'];
            $to =  $response['to'][0];
        $message =  $response['text'];

        $sql = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM did  WHERE cli='".$to."'"));
        if(!empty($sql))
        {
            $parent_id = $sql['parent_id'];
            $database = "client_".$parent_id;
            $user_id = $sql['user_id'];


            $conn1 = mysqli_connect("localhost","root","HG@v2RM8ERULC",$database);
            //$conn1 = mysqli_connect("localhost","root","",$database);

            mysqli_query($conn1,"insert into sms set extension='".$user_id."',number='".$from."',did='".$to."',message='".$message."',operator='didforsale',type='incoming'");
            echo "sms received successfully";
        }
        else
        {
            echo "no entry";
        }
    }*/



    public function voiceMailReceiver($response){
        //http://localhost:8090/voice-mail-receiveing?extension=12014&voicemailno=917838626612
        $conn = mysqli_connect("localhost","root","HG@v2RM8ERULC","master");
        //$conn = mysqli_connect("localhost","root","","master");
        // Check connection

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();
        }
        //echo $response['extension'];die;
        $sql = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users  WHERE extension='".$response['extension']."'"));

        if(!empty($sql))
        {


            $parent_id = $sql['parent_id'];
            $database = "client_".$parent_id;
            $user_id = $sql['id'];

            $conn1 = mysqli_connect("localhost","root","HG@v2RM8ERULC",$database);
            //$conn1 = mysqli_connect("localhost","root","",$database);
            //Android // ios
            $userToken = mysqli_fetch_assoc(mysqli_query($conn1,"SELECT * FROM user_token  WHERE userId='".$user_id."'"));

            //echo "<pre>";print_r($userToken);die;
            $deviceToken = $userToken['deviceToken'];
            $deviceType = $userToken['deviceType'];

            if(!empty($deviceToken))
            {
                $title = "Voicemail Notification";
                $body  = "Voicemail Received From ".$response['voicemailno'];
                $type  = "VOICEMAIL";

                $this->sendNotification($deviceToken,$title,$body,$type,$deviceType);
            }


            echo "Voicemail notification send successfully";
        }
        else
        {
            echo "no entry";
        }
    }


    public function sendNotification($deviceToken,$title,$body,$type,$deviceType)
    {
        //echo $deviceToken;die;
        $SERVER_API_KEY = env("SERVER_API_KEY");
        if($deviceType == 'Android')
        {
            $data = [
                "to" => trim($deviceToken),
                "data" =>[
                        "title" => $title, //$request->title,
                        "body"  => $body,
                        "type"  => $type,
                    ]
                ];
            }
            else
                if($deviceType == 'ios')
                {

                $data = [
                    "notification" =>[
                        "body"  => $body,
                        "title" => $title, //$request->title,
                        "type"  => $type,
                        "sound" =>"Default",
                        "content-available"=>1
                    ],

                    "to"=>trim($deviceToken),
                ];


                }

            $dataString = json_encode($data);
            //echo "<pre>";print_r($dataString);die;
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            try
            {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                $response = curl_exec($ch);
                return   $response;
                //return $this->successResponse("Notification Send Successfully", $data);
            }
            catch (\Exception $exception)
            {
                return false;

            }
        }

        function receieveLeadSource($response)
        {
            //$conn = mysqli_connect("localhost","root","HG@v2RM8ERULC","master");
            $conn = mysqli_connect("localhost","root","","master");

            if (mysqli_connect_errno())
            {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit();
            }

            $lead_source_token = $response->token;
            $leadSource = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM lead_source_api  WHERE
                api_key='".$lead_source_token."'"));

            if(!empty($leadSource))
            {
                $clientId = $leadSource['client_id'];
                $database = "client_".$clientId;

                //$conn1 = mysqli_connect("localhost","root","HG@v2RM8ERULC",$database);
                $conn1 = mysqli_connect("localhost","root","",$database);

                $sourceConfig = mysqli_fetch_assoc(mysqli_query($conn1,"SELECT * FROM lead_source_config  WHERE api_key='".$lead_source_token."'"));

                if(!empty($sourceConfig))
                {
                    $sql_listHeader = "SELECT * FROM list_header  WHERE list_id='".$sourceConfig['list_id']."'";
                    $result_listHeader = mysqli_query($conn1, $sql_listHeader);
                    $listHeader = mysqli_fetch_all($result_listHeader, MYSQLI_ASSOC);
                    foreach($listHeader as $header)
                    {
                        $header_name = str_replace(' ', '_', strtolower($header['header']));
                        $column_name = $header['column_name'];
                        if(!empty($response->$header_name))
                        {
                            $value = str_replace('$','',$response->$header_name);
                            if($header_name != $value)
                            {
                                $leads[$column_name] = $response->$header_name;
                            }
                        }
                    }

                    if(!empty($leads))
                    {
                        $leads['list_id'] = $sourceConfig['list_id'];

                        $query= "INSERT INTO list_data ( " . implode(', ',array_keys($leads)) . ")
                        VALUES ('" . implode ( "', '", array_values($leads) ) . "')";

                        $insert = mysqli_query($conn1, $query);
                        if(!$insert)
                        {
                            $response = array(
                                'status' => false,
                                'message' => 'An error occured...'
                            );
                        }

                        else
                        {
                            $response = array(
                                'status' => true,
                                'message' => 'Lead Data Inserted Successully',
                                'data' => $leads
                            );
                        }
                    }

                    else
                    {
                        $response = array(
                            'status' => false,
                            'message' => 'lead is empty not any value in array'
                        );
                    }
                }

                else
                {
                    $response = array(
                        'status' => false,
                        'message' => 'Failed to find Invalid lead source Id',
                    );
                }
            }
            else
            {
                $response = array(
                    'status' => false,
                    'message' => 'Failed to find Invalid TokenId',
                );
            }
            echo json_encode($response);
        }
    }
