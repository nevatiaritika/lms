<?php
session_start ();
require 'connvars.php';
?>
<?php
// Check for admin here.
// If not take to login page
if (! (isset ( $_SESSION ['aid'] ) && $_SESSION ['aid'] != '')) {
	
	header ( "Location: adminlogin.php" );
}
?>
<?php

date_default_timezone_set ( "Asia/Kolkata" );
if (! isset ( $_GET ['aid'] )) {
	?>
<script language="javascript">window.location = "allusers.php"</script>
<?php
	exit ();
}
$aid = $_GET ['aid'];
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$query = "Select aid,fname,lname,email from admin where aid = '" . $aid . "'";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $uid, $fname, $lname, $email );
mysqli_stmt_fetch ( $stmt );
mysqli_stmt_close ( $stmt );
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

<title>Edit Admin: <?php echo $fname; ?></title>

<!-- Bootstrap core CSS -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/navbar.css" rel="stylesheet">

<!-- Just for debugging purposes. Don't actually copy this line! -->
<!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

</script>
</head>

<script type="text/javascript">

window.onload = function(){
	 setTimeout(function(){
	   alert("Session Timed Out! Please Login Again");
	   window.location = "adminlogout.php";
	 }, 10800000);
	};
</script>

<body>
	<div class="container">
		<div class="pull-right">
			<button type="button" class="btn btn-success"
				onclick="window.location = 'adminhome.php'">Admin Home</button>
			<button type="button" class="btn btn-primary"
				onclick="window.location = 'adminlogout.php'">Log Out</button>
		</div>
		<div class="pull-left">
			<button type="button" class="btn btn-success"
				onclick="window.location = 'allusers.php'">Back to All Users</button>
		</div>
	</div>
	<div class="container">
		<h3>Edit Admin: <?php echo $fname . " " . $lname; ?></h3>
		<br />
	</div>

	<div class="container">
		<form role="form" method="POST" action="saveadminedit.php">
			<input type="hidden" name="aid" value="<?php echo $aid; ?>" />
			<div class="container">
				<div class="row">
					<div class="col-xs-9">
						<div class="row">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Basic details of the Admin</h3>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<label for="userfname" class="col-sm-2 control-label">Admin
											First Name</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="userfname"
												id="userfname" value="<?php echo $fname; ?>"
												placeholder="First Name" />
										</div>
									</div>
									<br />
									<br />
									<div class="form-group">
										<label for="userlname" class="col-sm-2 control-label">Admin
											Last Name</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="userlname"
												id="userlname" value="<?php echo $lname; ?>"
												placeholder="Last Name" />
										</div>
									</div>
									<br />
									<br />
									<div class="form-group">
										<label for="useremail" class="col-sm-2 control-label">Admin
											Email</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="useremail"
												id="useremail" value="<?php echo $email; ?>"
												placeholder="Email" />
										</div>
									</div>
									<br />
									<br />
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Info</h3>
							</div>
							<div class="panel-body">
								<p>Info: Editing the email id here may make it impossible for
									user to log in.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<button type="submit" class="btn btn-success">Submit</button>
					&nbsp; <a class="btn btn-primary" href='allusers.php'>Cancel</a>
				</div>
			</div>
		</form>
	</div>

	<!-- /container -->


	<!-- Bootstrap core JavaScript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/jquery-2.0.3.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
