<?php
    header('Access-Control-Allow-Origin: *');

    if(empty($_GET['extension']) && empty($_GET['parent_id']))
    {
        echo "Extension and parent Id not find";die;
    }
    $extension =$_GET['extension'];
    $alt_extension =$_GET['alt_extension'];

    $database = 'master';

    //$conn = mysqli_connect("localhost","root","",$database);
    $conn = mysqli_connect("localhost","root","d^(@KdCSAQL0MfX",$database);
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    $trp = mysqli_query($conn, "SELECT * from inbound_call_popup where status='1'  and (extension=".$alt_extension." OR extension=".$extension.") and parent_id=".$_GET['parent_id']." order by id desc limit 1");
    $r = mysqli_fetch_assoc($trp);
    print json_encode($r); //convert php data to json data

    mysqli_close($conn);
?>