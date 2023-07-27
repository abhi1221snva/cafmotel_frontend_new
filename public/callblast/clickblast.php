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

if (isset($_REQUEST['outboundnum'])) {
    $dialphonenumber = "91".$_REQUEST['outboundnum'];
} else {
    echo "Parameter outboundnum missing";
    exit();
}
if (isset($_REQUEST['parent_id'])) {
    $parent_id = $_REQUEST['parent_id'];
} else {
    echo "Parameter parent_id missing";
    exit();
}

if (isset($_REQUEST['event_type_id'])) {
    $event_type_id = $_REQUEST['event_type_id'];
} else {
    echo "Parameter event_type_id missing";
    exit();
}

if (isset($_REQUEST['event_type'])) {
    $event_type = $_REQUEST['event_type'];
} else {
    echo "Parameter event_type missing";
    exit();
}

/*echo $parent_id;
echo '<br>';
echo $dialphonenumber;
echo '<br>';
echo $event_type_id;
echo '<br>';
echo $event_type;
echo '<br>';die;*/



//$extension = '12014';
//$dialphonenumber ='9024412385';
$timeout = 60;

$sql = "SELECT asterisk_server_id,extension,host,user,secret FROM users left join asterisk_server on asterisk_server.id=asterisk_server_id WHERE users.parent_id='".$parent_id."' limit 1";
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
    fputs($socket, "Channel: SIP/1001768509/$dialphonenumber\r\n" );
    fputs($socket, "Exten: $dialphonenumber\r\n" );
    fputs($socket, "Context: default\r\n" ); // very important to change to your outbound context
    fputs($socket, "Priority: 1\r\n" );
    fputs($socket, "Async: yes\r\n\r\n" );
    $wrets=fgets($socket,128);
    echo $wrets;

	}
}

?>
