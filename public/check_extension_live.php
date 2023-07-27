<?php
    header('Access-Control-Allow-Origin: *');

    if(empty($_GET['extension']) && empty($_GET['parent_id']))
    {
        echo "Extension and parent Id not find";die;
    }
    $extension =$_GET['extension'];
    $database = 'client_'.$_GET['parent_id'];
    $alt_extension = $_GET['alt_extension'];


    //$conn = mysqli_connect("localhost","root","",$database);
    $conn = mysqli_connect("localhost","root","HG@v2RM8ERULC",$database);
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    $trp = mysqli_query($conn, "SELECT * from extension_live where extension='".$extension."' OR extension='".$alt_extension."' limit 1");
    $rows = array();
    while($r = mysqli_fetch_assoc($trp))
    {
        $rows = $r;
    }
    print json_encode($rows); //convert php data to json data
?>