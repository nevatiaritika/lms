<?php
session_start ();
require 'connvars.php';
?>
<html>
<head>
<title>Password reset for Forgot Password</title>
</head>
<body>
	<?php
	date_default_timezone_set ( "Asia/Kolkata" );
	// Mail function here
	$to = $_GET ["emailforgot"];
	
	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
	mysqli_select_db ( $db, 'vpnlms' );
	
	$sql = "SELECT fname,lname,encrpass from admin WHERE email='$to' and active = 1";
	$result = mysqli_query ( $db, $sql );
	$num_rows = mysqli_num_rows ( $result );
	$row = mysqli_fetch_array ( $result );
	
	if ($num_rows >= 1) 	// check if email exists in database
	{
		$tok1 = md5 ( $to . $row ['encrpass'] );
		$tok2 = md5 ( $row ['fname'] . $row ['lname'] );
		$token = $tok1 . $tok2;
		$reseturl = $link . "resetpass.php?email=" . $to . "&token=" . $token;
		$subject = 'Reset Password for LMS';
		$message = "Hello " . $row ['fname'] . " " . $row ['lname'] . ",<br /><br />Welcome to LMS System. <br /><br />To reset your password <a href=" . $reseturl . ">Click Here</a> <br /><br />NOTE: This mail has been sent to you only on your request to change your password required for login to LMS.<br /> If you did not request to change your password, please contact the admin.<br /><br > Thank You.";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= "From: <noreply@acjs.co>\r\n";
		mail ( $to, $subject, stripslashes ( $message ), $headers );
		echo '<script language="javascript">window.location="adminlogin.php"</script>';
		exit ();
	} else {
		
		echo '<script> alert("The email id is not registered");</script>';
		echo '<script language="javascript">window.location="adminlogin.php"</script>';
	}
	
	?>
</body>
</html>
