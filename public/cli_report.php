<?php

if(empty($_GET['client_id']))
{
        echo "client id required";die;
}

$client_id = $_GET['client_id'];
$database = "master";
$conn = mysqli_connect("localhost","root","HG@v2RM8ERULC",$database);

if (mysqli_connect_errno())
{
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
}

$trp = mysqli_query($conn, "SELECT * from did where parent_id=".$client_id);
$rows = array();
$i=0;
while($r = mysqli_fetch_assoc($trp))
{
        
        $cli = $r['cli'];
        $content = "Channel: SIP/Airespring1/#135196219859805718\nCallerId: $cli\nContext: callfile-detect\nExtension: s\nPriority: 1\n";
        $file_name = $cli;
        $file = fopen($file_name.".call", 'w');
        fwrite($file, $content);
        $rootPath = '/var/www/html/branch/frontend/public/';
        $convertedFilename = $rootPath . $file_name . ".call";
        $strAsteriskPath = "root@sip1.voiptella.com:/var/spool/asterisk/outgoing/";
        shell_exec("scp -P 10347 $convertedFilename $strAsteriskPath");
        
        $i++;
        
        if($i == 10 || $i == 20 || $i == 30 || $i == 40 || $i ==50 || $i == 60 || $i ==70 || $i ==80)
        {
                sleep(15);
        }

        echo $file_name . ".call".'<br>';
}



?>