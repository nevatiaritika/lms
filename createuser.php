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
if (isset ( $_POST ['userfname'] ) && isset ( $_POST ['useremail'] )) {
	$eid = $_POST ['employeeid'];
	$fname = $_POST ['userfname'];
	$lname = $_POST ['userlname'];
	$email = $_POST ['useremail'];
	
	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
	
	if ($_POST ['userpost'] == 'admin') {
		
		$query = "select email from admin where email = '$email'";
		$stmt = mysqli_prepare ( $db, $query );
		if (! $stmt) {
			die ( 'mysqli error: ' . mysqli_error ( $db ) );
		}
		mysqli_stmt_execute ( $stmt );
		mysqli_stmt_store_result ( $stmt );
		mysqli_stmt_bind_result ( $stmt, $check );
		mysqli_stmt_fetch ( $stmt );
		
		// Add code to add admin over here
		// $db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");
		if (! ($check == $email)) {
		$userpass = substr ( str_shuffle ( md5 ( $email ) ), 0, 8 ); // This is non encrypted password to be sent in email
		$encrpassword = md5 ( $userpass ); // The encrypted password to be stored in DB
		$query = "INSERT into admin (fname,lname,email,active,encrpass) values ('$fname','$lname','$email',1,'$encrpassword')";
		$stmt = mysqli_prepare ( $db, $query );
		mysqli_stmt_execute ( $stmt );
		mysqli_stmt_close ( $stmt );
		$subject = 'You have been added as an admin to LMS';
		$message = "Hello " . $fname . " " . $lname . ",<br /><br />Welcome to LMS.<br /><br /> Your login details are as follows: <br />Login id: " . $email . "<br />Password: " . $userpass . "<br /><br />Thank You.";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= "From: <noreply@acjs.co>" . "\r\n";
		mail ( $email, $subject, stripslashes ( $message ), $headers );
		}
		
		if ($check == $email) {
			?>
				<script language="javascript"> 
							alert("The admin with email id : <?php echo $email;?> already exists !");
							window.location="createuser.php";
						</script>
				<?php
					} else {
						?>
				<script language="javascript">
				        alert("New Admin Created");
				        window.location="allusers.php";
				        </script>
				<?php
					}
					exit ();
		
	} else {
		
		$managerfname = $_POST ['managerfname'];
		$managerlname = $_POST ['managerlname'];
		$manageremail = $_POST ['manageremail'];
		$hrmanagerfname = $_POST ['hrmanagerfname'];
		$hrmanagerlname = $_POST ['hrmanagerlname'];
		$hrmanageremail = $_POST ['hrmanageremail'];
		
		list ( $deptid, $post ) = explode ( ";:;", $_POST ['userpost'] );
		
		$query = "select email from users where email = '$email'";
		$stmt = mysqli_prepare ( $db, $query );
		if (! $stmt) {
			die ( 'mysqli error: ' . mysqli_error ( $db ) );
		}
		mysqli_stmt_execute ( $stmt );
		mysqli_stmt_store_result ( $stmt );
		mysqli_stmt_bind_result ( $stmt, $check );
		mysqli_stmt_fetch ( $stmt );
		
		if (! ($check == $email)) {
			$posts = array ();
			$userpass = substr ( str_shuffle ( md5 ( $email ) ), 0, 8 ); // This is non encrypted password to be sent in email
			$encrpassword = md5 ( $userpass ); // The encrypted password to be stored in DB
			$query = "INSERT into users (emp_id,fname,lname,email,deptid,post,encrpass,managerfname,managerlname, manageremail, hrmanagerfname, hrmanagerlname, hrmanageremail) values ('$eid','$fname','$lname','$email',$deptid,'$post','$encrpassword', '$managerfname', '$managerlname', '$manageremail', '$hrmanagerfname', '$hrmanagerlname', '$hrmanageremail')";
			// echo $query;
			$stmt = mysqli_prepare ( $db, $query );
			mysqli_stmt_execute ( $stmt );
			mysqli_stmt_close ( $stmt );
			
			$subject = 'You have been added as a user to LMS';
			$message = "Hello " . $fname . " " . $lname . ",<br /><br />Welcome to LMS.<br /><br /> Your login details are as follows: <br />Login id: " . $email . "<br />Password: " . $userpass . "<br /><br />Thank You.";
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
			$headers .= "From: <noreply@acjs.co>" . "\r\n";
			mail ( $email, $subject, stripslashes ( $message ), $headers );
			
			
		}
		
		if ($check == $email) {
			?>
		<script language="javascript"> 
					alert("The user with email id : <?php echo $email;?> already exists !");
					window.location="createuser.php";
				</script>
		<?php
			} else {
				?>
		<script language="javascript">
		        alert("New User Created");
		        window.location="allusers.php";
		        </script>
		<?php
			}
			exit ();
		}
}
		?>
	
	
<?php

$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );

$posts_value = array ();
$posts_text = array ();
$depts = array ();

$query = "Select deptid,deptname from departments";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $di, $dn );
while ( mysqli_stmt_fetch ( $stmt ) ) {
	$depts [$di] = $dn;
}
mysqli_stmt_close ( $stmt );

$query = "Select deptid,post from posts order by deptid";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $di, $p );
while ( mysqli_stmt_fetch ( $stmt ) ) {
	array_push ( $posts_value, $di . ";:;" . $p );
	array_push ( $posts_text, $depts [$di] . " - " . $p );
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

<title>Add User</title>

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
<script>
	
	function letternumber(e)
	{
		var key;
		var keychar;

		if (window.event)
		   key = window.event.keyCode;
		else if (e)
		   key = e.which;
		else
		   return true;
		keychar = String.fromCharCode(key);
		keychar = keychar.toLowerCase();

		// control keys
		if ((key==null) || (key==0) || (key==8) || 
			(key==9) || (key==13) || (key==27) )
		   return true;

		// alphas and numbers
		else if ((("0123456789").indexOf(keychar) > -1))
			return true;
		else
			return false;
	}
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
		<div class="pull-left">
			<button type="button" class="btn btn-success"
				onclick="window.location = 'allusers.php'">Back to All Users</button>
		</div>
		<div class="pull-right">
			<button type="button" class="btn btn-success"
				onclick="window.location = 'adminhome.php'">Admin Home</button>
			<button type="button" class="btn btn-primary"
				onclick="window.location = 'adminlogout.php'">Log Out</button>
		</div>
	</div>
	<div class="container">
		<h3>Add User</h3>
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
									<h3 class="panel-title">Basic details of the User</h3>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<label for="employeeid" class="col-sm-2 control-label">Employee
											ID</label>
										<div class="col-sm-10">
											<input type="text" min="0"
												onKeyPress="return letternumber(event)" class="form-control"
												name="employeeid" id="employeeid" value=""
												placeholder="Employee ID" required />
										</div>
									</div>
									<br /> <br />
									<div class="form-group">
										<label for="userfname" class="col-sm-2 control-label">First
											Name</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="userfname"
												id="userfname" value="" placeholder="First Name" required />
										</div>
									</div>
									<br /> <br />
									<div class="form-group">
										<label for="userlname" class="col-sm-2 control-label">Last
											Name</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="userlname"
												id="userlname" value="" placeholder="Last Name" />
										</div>
									</div>
									<br /> <br />
									<div class="form-group">
										<label for="useremail" class="col-sm-2 control-label">User
											Email</label>
										<div class="col-sm-10">
											<input type="email" class="form-control" name="useremail"
												id="useremail" value="" placeholder="Email" required />
										</div>
									</div>
									<br /> <br />
									<div class="form-group">
										<label for="userpost" class="col-sm-2 control-label">User Post</label>
										<div class="col-sm-10">
											<select class="form-control" name="userpost" id="userpost"
												onchange="checkpost()">
                                                    <?php
																																																				for($n = 0; $n < sizeof ( $posts_value ); $n ++) {
																																																					?>
                                                        <option
													value='<?php echo $posts_value[$n]; ?>'><?php echo $posts_text[$n]; ?></option>
                                                        <?php
																																																				}
																																																				?>
                                                    <option
													value='admin'>Admin</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row" id='manager-section'>
							<div class="col-md-6" style="padding-left: 0px">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">User's Manager</h3>
									</div>
									<div class="panel-body">
										<div class="form-group">
											<label for="managerfname" class="col-sm-4 control-label">First
												Name</label>
											<div class="col-sm-8">
												<input type="text" class="form-control" name="managerfname"
													id="managerfname" value=""
													placeholder="Manager's First Name" required />
											</div>
										</div>
										<br /> <br />
										<div class="form-group">
											<label for="managerlname" class="col-sm-4 control-label">Last
												Name</label>
											<div class="col-sm-8">
												<input type="text" class="form-control" name="managerlname"
													id="managerlname" value=""
													placeholder="Manager's Last Name" />
											</div>
										</div>
										<br /> <br />
										<div class="form-group">
											<label for="manageremail" class="col-sm-4 control-label">Email</label>
											<div class="col-sm-8">
												<input type="email" class="form-control" name="manageremail"
													id="manageremail" value="" placeholder="Manager's Email"
													required />
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6" style="padding-right: 0px">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">User's HR Manager</h3>
									</div>
									<div class="panel-body">
										<div class="form-group">
											<label for="hrmanagerfname" class="col-sm-4 control-label">First
												Name</label>
											<div class="col-sm-8">
												<input type="text" class="form-control"
													name="hrmanagerfname" id="hrmanagerfname" value=""
													placeholder="HR Manager's First Name" required />
											</div>
										</div>
										<br /> <br />
										<div class="form-group">
											<label for="hrmanagerlname" class="col-sm-4 control-label">Last
												Name</label>
											<div class="col-sm-8">
												<input type="text" class="form-control"
													name="hrmanagerlname" id="hrmanagerlname" value=""
													placeholder="HR Manager's Last Name" />
											</div>
										</div>
										<br /> <br />
										<div class="form-group">
											<label for="hrmanageremail" class="col-sm-4 control-label">Email</label>
											<div class="col-sm-8">
												<input type="email" class="form-control"
													name="hrmanageremail" id="hrmanageremail" value=""
													placeholder="HR Manager's Email" required />
											</div>
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
							<div class="panel-body">
								<p>Info: The password for the user will be generated
									automatically.</p>
								<p>It will be sent to the user by email.</p>
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
	<script>
                                                    function checkpost() {
                                                        if ($('#userpost').val() == "admin") {
                                                            $('#manager-section').slideUp();
															document.getElementById('managerfname').required = false;
															document.getElementById('manageremail').required = false;
															document.getElementById('hrmanagerfname').required = false;
															document.getElementById('hrmanageremail').required = false;
                                                        } else {
                                                            $('#manager-section').slideDown();
															document.getElementById('managerfname').required = true;
															document.getElementById('manageremail').required = true;
															document.getElementById('hrmanagerfname').required = true;
															document.getElementById('hrmanageremail').required = true;
														}
                                                    }
        </script>
</body>

</html>
