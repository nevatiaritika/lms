<?php
session_start ();
require 'connvars.php';
?>
<?php
date_default_timezone_set("Asia/Kolkata");
if (! isset ( $_SESSION ['uid'] )) {
	?>
<script language="javascript">window.location="userlogin.php"</script>
<?php
} else {
	$uid = $_SESSION ['uid'];
}
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$query = "Select fname, deptid, post from users where uid = '" . $uid . "'";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $name, $deptid, $post );
$num = mysqli_stmt_num_rows ( $stmt );
mysqli_stmt_fetch ( $stmt );
mysqli_stmt_close ( $stmt );
?>

<?php
$loginerror = "";
if (isset ( $_POST ['currpassword'] ) && isset ( $_POST ['newpassword'] ) && isset ( $_POST ['repassword'] )) {
	$curpass = $_POST ['currpassword'];
	$newpass = $_POST ['newpassword'];
	$repass = $_POST ['repassword'];
	$uid = $_SESSION ['uid'];
	$query = "Select encrpass from users where uid = '" . $uid . "'";
	$result = mysqli_query ( $db, $query );
	$row = mysqli_fetch_array ( $result );
	$actualpassword = $row ['encrpass'];
	if ($actualpassword == md5 ( $curpass )) {
		if (strlen ( $newpass ) < 8) {
			$loginerror = "Password should be at least 8 characters long ";
		}
		
		if (preg_match ( '/[^a-zA-Z0-9]+/', $newpass )) {
			$loginerror = $loginerror . "<br/ >Password can contain only a-z, A-Z, 0-9";
		}
		
		if ($loginerror == "") {
			if ($repass == $newpass) {
				$query = "Update users set encrpass='" . md5 ( $newpass ) . "' where uid='" . $uid . "'";
				$stmt = mysqli_prepare ( $db, $query );
				mysqli_stmt_execute ( $stmt );
				
				$query = "Select email from users where uid = '" . $uid . "'";
				$result = mysqli_query ( $db, $query );
				$row = mysqli_fetch_array ( $result );
				$email = $row ['email'];
				
				$subject = 'Password for LMS was reset successfully';
				$message = "Welcome to LMS System. <br /><br />Your password for LMS was reset successfully.<br /><br > Thank You.";
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
				$headers .= "From: <noreply@acjs.co>\r\n";
				mail ( $email, $subject, stripslashes ( $message ), $headers );
				
				?>
<script type="text/javascript">
    			alert("Password Reset Successful");
    			window.close();
  				</script>
<?php
			} else {
				$loginerror = "Passwords donot match!";
			}
		}
	} else {
		$loginerror = "Current Password incorrect!";
	}
	
	mysqli_close ( $db );
}
?>
<html lang="en">

<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

        <title>Change Password</title>

        <!-- Bootstrap core CSS -->
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/signin.css" rel="stylesheet">

        <!-- Just for debugging purposes. Don't actually copy this line! -->
        <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
              <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
              <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
            <![endif]-->
    </head>
<script type="text/javascript">

window.onload = function(){
	 setTimeout(function(){
	   alert("Session Timed Out! Please Login Again");
	   window.location = "userlogout.php";
	 }, 10800000);
	};
</script>
<body>
	<div class="container">
		<div class="pull-left">
			<h4 style="display: inline;">Welcome, <?php echo $_SESSION['uname']; ?></h4>

		</div>
		<div class="pull-right">
			<button type="button" class="btn btn-success"
				onclick="window.close();">Close Window</button>
		</div>
		<form class="form-signin" role="form" method="post" action="">
			<h2 class="form-signin-heading">Change Password</h2>
			<input name="currpassword" id="currpassword" type="password"
				class="form-control" placeholder="Current Password" required
				autofocus> <input name="newpassword" id="newpassword"
				type="password" class="form-control" placeholder="New Password"
				required> <input name="repassword" id="repassword" type="password"
				class="form-control" placeholder="Retype new Password" required>
			<?php
			if (isset ( $loginerror )) {
				?>
                    <p class="text-danger"><?php echo $loginerror?></p>
                    <?php
			}
			?>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Change
				Password</button>
		</form>
	</div>

	<script src="js/jquery-2.0.3.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>