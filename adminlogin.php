<?php
session_start ();
require 'connvars.php';
?>
<?php
date_default_timezone_set("Asia/Kolkata");
if (isset ( $_POST ['emailaddress'] ) && isset ( $_POST ['password'] )) {
	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
	$email = $_POST ['emailaddress'];
	$pass = $_POST ['password'];
	$query = "Select aid from admin where email = '" . $email . "' and active = 1"; 
	$stmt = mysqli_prepare ( $db, $query );
	mysqli_stmt_execute ( $stmt );
	mysqli_stmt_store_result ( $stmt );
	mysqli_stmt_bind_result ( $stmt, $aid );
	$num = mysqli_stmt_num_rows ( $stmt );
	if ($num == 1) {
		mysqli_stmt_fetch ( $stmt );
	}
	mysqli_stmt_close ( $stmt );
	
	if ($num != 1) {
		$loginerror = "Email is not registered!";
	} else {
		$query = "Select encrpass from admin where email = '" . $email . "'";
		$result = mysqli_query ( $db, $query );
		$row = mysqli_fetch_array ( $result );
		$actualpassword = $row ['encrpass'];
		if ($actualpassword == md5 ( $pass )) {
			$_SESSION ['aid'] = $aid;
			session_start();
			?>
<script language="javascript">window.location="adminhome.php"</script>
<?php
		} else {
			$loginerror = "Password incorrect!";
		}
	}
	
	mysqli_close ( $db );
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

<title>Admin Login</title>

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

<body>
	<div class="container">
		<form class="form-signin" role="form" method="post" action="">
			<h2 class="form-signin-heading">Admin Login</h2>
			<input name="emailaddress" id="emailadd" type="text"
				class="form-control" placeholder="Email address" required autofocus>
			<input name="password" type="password" class="form-control"
				placeholder="Password" required> <label class="checkbox"> <input
				type="checkbox" value="remember-me">Remember me
			</label>

			<?php
			
if (isset ( $loginerror )) {
				
				echo '<p class="text-danger">' . $loginerror . '</p>';
			}
			?>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign
				in</button>
			<button class="btn btn-lg btn-primary btn-block" type="button"
				onclick="forgotPass()">Forgot Password</button>
		</form>

	</div>
	<!-- /container -->

	<!-- Bootstrap core JavaScript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script type="text/javascript">
        function forgotPass()
        {
          
var emailForgot =prompt("An email will be sent on this email ID for new password:",document.getElementById("emailadd").value);

            if (emailForgot===""||!emailForgot) //send link
            {
               
            }
else
{
window.location.href = 'forgotsuccadmin.php?emailforgot='+emailForgot;
        }
}
    </script>
	<script src="js/jquery-2.0.3.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
