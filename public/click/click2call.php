<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mysqli = new mysqli("localhost","root","HG@v2RM8ERULC","master");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

if (isset($_REQUEST['internalnum'])) {
    $extension = $_REQUEST['internalnum'];
} else {
    echo "Parameter internalnum missing";
    exit();
}
if (isset($_REQUEST['outboundnum'])) {
    $dialphonenumber = "71".$_REQUEST['outboundnum'];
} else {
    echo "Parameter outboundnum missing";
    exit();
}

//$extension = '12014';
//$dialphonenumber ='9024412385';
$timeout = 60;

$sql = "SELECT asterisk_server_id,extension,host,user,secret FROM users left join asterisk_server on asterisk_server.id=asterisk_server_id WHERE users.extension='".$extension."' OR users.alt_extension='".$extension."'";
$rs = $mysqli->query($sql);
$row = $rs->fetch_assoc();

//var_dump($row); exit;

if(!empty($row['asterisk_server_id'])){
	$host = $row['host'];
	$user = $row['user'];
	$secret = $row['secret'];

	if(!empty($host) && !empty($user) && !empty($secret)){
	//echo 'loop'; exit;

	$asterisk_ip = $host;
  $socket = fsockopen($asterisk_ip,"5038", $errno, $errstr, $timeout);
    fputs($socket, "Action: Login\r\n");
    fputs($socket, "UserName: {$user}\r\n");
    fputs($socket, "Secret: {$secret}\r\n\r\n");

    $wrets=fgets($socket,128);

    echo $wrets;

    fputs($socket, "Action: Originate\r\n" );
    fputs($socket, "Channel: SIP/$extension\r\n" );
    fputs($socket, "Exten: $dialphonenumber\r\n" );
    fputs($socket, "Context: default-c2c\r\n" ); // very important to change to your outbound context
    fputs($socket, "Priority: 1\r\n" );
    fputs($socket, "Async: yes\r\n\r\n" );
    $wrets=fgets($socket,128);
    echo $wrets;

	}
}

?>
