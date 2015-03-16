<?php
session_start ();
require 'connvars.php';
?>
<html>
<head>
<title>Reset Successful</title>
</head>
<body>
	<?php
	date_default_timezone_set("Asia/Kolkata");
	$uid = $_GET ["uid"];
	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
	mysqli_select_db ( $db, 'vpnlms' );
	$length = 8;
	$password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	$encrpass = md5($password);
	$sql = "UPDATE users SET encrpass='$encrpass' WHERE uid='$uid'";
	$result = mysqli_query ( $db, $sql );
	
	if (! $result) {
		echo "some error here updating";
	}
	
	echo '<script language="javascript">alert("Password reset successfully"); window.location="allusers.php"</script>';
	exit ();
	?>
</body>
</html>
