<?php
session_start ();
require 'connvars.php';
?>
<?php

date_default_timezone_set ( "Asia/Kolkata" );
if (isset ( $_POST ['password'] ) && isset ( $_POST ['repass'] )) {
	$check = true;
	$pwd = $_POST ['password'];
	// check password constraints
	if (strlen ( $pwd ) < 8) {
		$loginerror = "Password should be at least 8 characters long ";
		$check = false;
	}
	
	if (preg_match ( '/[^a-zA-Z0-9]+/', $pwd )) {
		$loginerror = "Password can contain only a-z, A-Z, 0-9";
		$check = false;
	}
	
	if ($_POST ['password'] != $_POST ['repass']) {
		$loginerror = "Passwords do not match";
		$check = false;
	}
	
	if ($_POST ['password'] == $_POST ['repass'] && $check == true) {
		$email = $_GET ["email"];
		$passset = md5 ( $_POST ['password'] );
		$resurl = "resetsucc.php?email=" . $email . "&pass=" . $passset;
		header ( 'Location: ' . $resurl );
	}
}
?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

<title>Reset you password</title>
<!-- Bootstrap core CSS -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/signin.css" rel="stylesheet">
</head>
<body>
	<?php
	$email = $_GET ["email"];
	$token = $_GET ["token"];
	
	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
	mysqli_select_db ( $db, 'vpnlms' );
	$sql = "SELECT fname,lname,encrpass from users WHERE email='$email'";
	$result = mysqli_query ( $db, $sql );
	$row = mysqli_fetch_array ( $result );
	$num_rows = mysqli_num_rows ( $result );
	$tok1 = md5 ( $email . $row ['encrpass'] );
	$tok2 = md5 ( $row ['fname'] . $row ['lname'] );
	$temptoken = $tok1 . $tok2;
	$url2 = "userlogin.php";
	if ($num_rows == 0) {
		if ($token == $temptoken) {
			?>
    <!--HTML for correct token starts here-->

	<div class="container">
		<form class="form-signin" role="form" method="post" action="">
			<h2 class="form-signin-heading">Reset Password</h2>
			<input name="password" id="password" type="password"
				class="form-control" placeholder="New Password" required autofocus>
			<input name="repass" type="password" class="form-control"
				placeholder="Retype Password" required>
            <?php
			if (isset ( $loginerror )) {
				?>
                    <p class="text-danger"><?php echo $loginerror?></p>
                    <?php
			}
			?>
            <button class="btn btn-lg btn-primary btn-block"
				type="submit">Reset</button>
		</form>

	</div>
    <?php
		} else {
			echo '<script language="javascript">window.location="userlogin.php"</script>';
		}
	} else {
		echo '<script> alert("Invalid User!");</script>';
		echo '<script language="javascript">window.location="userlogin.php"</script>';
	}
	?>
    <script src="js/jquery-2.0.3.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
