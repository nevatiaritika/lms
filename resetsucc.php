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
	$email = $_GET ["email"];
	$password = $_GET ["pass"];
	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
	mysqli_select_db ( $db, 'vpnlms' );
	$sql = "UPDATE users SET encrpass='$password' WHERE email='$email'";
	$result = mysqli_query ( $db, $sql );
	
	if (! $result) {
		echo "some error here updating";
	}
	
	$subject = 'Password for LMS was reset successfully';
	$message = "Welcome to LMS System. <br /><br />Your password for LMS was reset successfully.<br /><br > Thank You.";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers .= "From: <noreply@acjs.co>\r\n";
	mail ( $email, $subject, stripslashes ( $message ), $headers );
	echo '<script language="javascript">alert("Password reset successfully"); window.location="userlogin.php"</script>';
	exit ();
	?>
</body>
</html>
