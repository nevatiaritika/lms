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
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

<title>Admin Home</title>

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
			<button type="button" class="btn btn-primary"
				onclick="window.location = 'adminlogout.php'">Log Out</button>
		</div>
	</div>
	<br />

	<div class="container">

		<!-- Jumbotron -->
		<div class="jumbotron">
			<h1>Welcome Admin!</h1>
			<p class="lead">This is your admin dashboard. From here you can
				manage users, courses, test, designations and everything else in the
				system.</p>

		</div>

		<!-- Example row of columns -->
		<div class="row">
			<div class="col-lg-4">
				<h2>Manage Courses</h2>
				<p>
					<a class="btn btn-success" href="allcourses.php" role="button">Go
						to courses</a>
				</p>
				<p>Create/Edit Courses</p>
				<p>Activate/Deactivate Courses</p>
				<p>Add Video Lectures</p>
				<p>Create/Edit Tests</p>
				<p>Assign/Reassign Courses to Users</p>
			</div>
			<div class="col-lg-4">
				<h2>Manage Users & Admins</h2>
				<p>
					<a class="btn btn-success" href="allusers.php" role="button">Go to
						users</a>
				</p>
				<p>Create/Edit Users</p>
				<p>Activate/Deactivate Users</p>
				<p>Create/Edit Admins</p>
				<p>Activate/Deactivate Admins</p>
				<p>Manage Departments & Designations</p>

			</div>
			<div class="col-lg-4">
				<h2>Manage Reports</h2>
				<p>
					<a class="btn btn-success" href="allreports_coursewise.php"
						role="button">Go to reports</a>
				</p>
				<p>Generate User-wise reports</p>
				<p>Generate Course-wise reports</p>
			</div>
		</div>



	</div>
	<!-- /container -->


	<!-- Bootstrap core JavaScript
        ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
</body>
</html>