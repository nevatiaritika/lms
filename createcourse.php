<?php
session_start ();
require 'connvars.php';
?>
<script type="text/javascript">

window.onload = function(){
	 setTimeout(function(){
	   alert("Session Timed Out! Please Login Again");
	   window.location = "adminlogout.php";
	 }, 10800000);
	};
</script>
<?php
// Check for admin here.
// If not take to login page
if (! (isset ( $_SESSION ['aid'] ) && $_SESSION ['aid'] != '')) {
	
	header ( "Location: adminlogin.php" );
}
?>
<?php

date_default_timezone_set ( "Asia/Kolkata" );
if (isset ( $_POST ['coursename'] )) {
	$coursename = $_POST ['coursename'];
	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
	
	$query = "select cname from courses where cname = '$coursename'";
	$stmt = mysqli_prepare ( $db, $query );
	if (! $stmt) {
		die ( 'mysqli error: ' . mysqli_error ( $db ) );
	}
	mysqli_stmt_execute ( $stmt );
	mysqli_stmt_store_result ( $stmt );
	mysqli_stmt_bind_result ( $stmt, $check );
	mysqli_stmt_fetch ( $stmt );
	
	if (! ($check == $coursename)) {
		$cname = $_POST ['coursename'];
		$cdescription = mysqli_real_escape_string($db,$_POST ['coursedescription']);
		$validity = $_POST ['validity'];
		if ($cname == "") {
			echo '<script language="javascript">window.location="editcourse.php?cid=' . $cid . '"</script>';
			exit ();
		}
		$created_ts = date ( "d-m-Y" );
		
		if (! isset ( $_POST ['coursemandatory'] )) {
			$query = "INSERT into courses (cname,description,active, created_ts,mandatory, validity_duration) values ('$cname','$cdescription', 0, '$created_ts', 0, $validity)";
		} else {
			$query = "INSERT into courses (cname,description,active, created_ts,mandatory,validity_duration) values ('$cname','$cdescription',0, '$created_ts', 1, $validity)";
		}
		
		$stmt = mysqli_prepare ( $db, $query );
		mysqli_stmt_execute ( $stmt );
		mysqli_stmt_close ( $stmt );
		
		$query = "Select cid from courses where cname='$cname' and description='$cdescription'";
		$stmt = mysqli_prepare ( $db, $query );
		mysqli_stmt_execute ( $stmt );
		mysqli_stmt_store_result ( $stmt );
		mysqli_stmt_bind_result ( $stmt, $cid );
		mysqli_stmt_fetch ( $stmt );
		mysqli_stmt_close ( $stmt );
		
		// echo $cid;
		if (isset ( $_POST ['posts'] )) {
			$posts = $_POST ['posts'];
			$query = "DELETE from courserpost where cid=$cid";
			$stmt = mysqli_prepare ( $db, $query );
			mysqli_stmt_execute ( $stmt );
			for($n = 0; $n < sizeof ( $posts ); $n ++) {
				list ( $deptid, $post ) = explode ( ";:;", $posts [$n] );
				$query = "INSERT into courserpost (cid,deptid,post) values ($cid,$deptid,'$post')";
				$stmt = mysqli_prepare ( $db, $query );
				mysqli_stmt_execute ( $stmt );
			}
		}
	}
	if ($check == $coursename) {
		?>
<script> 
			alert("The course : <?php echo $coursename;?> already exists !");
			window.location="createcourse.php";
		</script>
<?php
	} else
		echo '<script language="javascript">window.location="editcourse.php?cid=' . $cid . '"</script>';
	exit ();
}
?>
<?php

$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$posts = array ();
$deptids = array ();
$deptnames = array ();
$query = "Select deptid,deptname from departments";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $di, $dn );
while ( mysqli_stmt_fetch ( $stmt ) ) {
	array_push ( $deptids, $di );
	array_push ( $deptnames, $dn );
}
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

<title>Add Course</title>

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
				onclick="window.location = 'allcourses.php'">Back to Course
				Dashboard</button>
		</div>
	</div>
	<div class="container">
		<h3>Add Course:</h3>
		<br />
	</div>

	<div class="container">
		<form role="form" method="POST" action="">
			<div class="container">
				<div class="row">
					<div class="col-xs-9">
						<div class="row">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Basic details of the course</h3>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<label for="coursename" class="col-sm-2 control-label">Course
											Name</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="coursename"
												id="coursename" value="" required="" />
										</div>
										<label for="coursemandatory" class="col-sm-2 control-label">Mandatory</label>
										<div class="col-sm-1">
											<input type="checkbox" class="" name="coursemandatory"
												id="coursemandatory" value="1" />
										</div>
									</div>
									<br /> <br />
									<div class="form-group">
										<label for="coursedescription" class="col-sm-2 control-label">Description</label>
										<div class="col-sm-10">
											<textarea class="form-control" rows="4"
												name="coursedescription" id="coursedescription"></textarea>
										</div>
									</div>
									<br /> <br /> <br /> <br /> <br>
									<div class="form-group">
										<label for="validity" class="col-sm-2 control-label">Validity</label>
										<div class="col-sm-3">
											<input type="number" class="form-control" name="validity"
												id="validity" value="" required=""
												placeholder="Number of Days" />
										</div>
									</div>
									<br /> <br />
									<div class="form-group">
										<label for="coursedescription1" class="col-sm-2 control-label"></label>
										<div class="col-sm-10">
											<button type="submit" class="btn btn-success">Save and
												Proceed to add videos</button>
											<a class="btn btn-primary" href='allcourses.php'>Cancel</a>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Info</h3>
							</div>
							<div class="panel-body">Info: A new course will be inactive when
								created. It can be activated from the Course Dashboard.</div>
						</div>
					</div>
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
