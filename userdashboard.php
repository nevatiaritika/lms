<?php
session_start ();
require 'connvars.php';
?>
<?php

date_default_timezone_set ( "Asia/Kolkata" );
if (! isset ( $_SESSION ['uid'] )) {
	?>
<script language="javascript">window.location = "userlogin.php"</script>
<?php
} else {
	$uid = $_SESSION ['uid'];
}
date_default_timezone_set ( "Asia/Kolkata" );
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$query = "Select fname, deptid, post from users where uid = '" . $uid . "'";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $name, $deptid, $post );
$num = mysqli_stmt_num_rows ( $stmt );
mysqli_stmt_fetch ( $stmt );
mysqli_stmt_close ( $stmt );
$_SESSION ['uname'] = $name;
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

<title>Dashboard</title>

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
			<h4 style="display: inline;">Welcome, <?php echo $_SESSION['uname']; ?></h4>
			&nbsp;&nbsp;&nbsp;
			<button type="button" class="btn btn-primary"
				onclick="window.location = 'userlogout.php'">Log Out</button>
			<a class="btn btn-primary" onclick="open_popup();">Change Password</a>
		</div>
	</div>

	<div class="container">
		<h3>Here's a bunch of courses for you to take:</h3>
		<br />
	</div>

        <?php
								$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
								// $query = "Select cid,cname,description,mandatory, created_ts from courses where cid in (select cid from courserpost where post='" . $post . "' and deptid='" . $deptid . "') and active=1";
								$query = "select courses.cid, courses.cname, courses.description, courses.mandatory, courses.validity_duration, user_assign.new_old, user_assign.dateassign from courses, user_assign where courses.cid = user_assign.cid and user_assign.uid='$uid' and courses.active='1' order by STR_TO_DATE(user_assign.dateassign,'%d-%m-%y') desc";
								$stmt = mysqli_prepare ( $db, $query );
								mysqli_stmt_execute ( $stmt );
								mysqli_stmt_store_result ( $stmt );
								mysqli_stmt_bind_result ( $stmt, $cid, $cname, $description, $mandatory, $validity, $new, $dateassign );
								$num = mysqli_stmt_num_rows ( $stmt );
								while ( mysqli_stmt_fetch ( $stmt ) ) {
									?>
            <div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">

                        <?php
									$course_taken = false;
									$query = "Select timestamp_of_test from attempts where cid = $cid and uid= $uid";
									
									$stmt1 = mysqli_prepare ( $db, $query );
									mysqli_stmt_execute ( $stmt1 );
									mysqli_stmt_store_result ( $stmt1 );
									mysqli_stmt_bind_result ( $stmt1, $timestamp_of_test );
									$num1 = mysqli_stmt_num_rows ( $stmt1 );
									$incomplete = false;
									if ($num1 == 1) {
										while ( mysqli_stmt_fetch ( $stmt1 ) ) {
											echo '<div style="float: right;" >Completed on: <i>' . $timestamp_of_test . '</i></div>';
											$course_taken = true;
										}
									} else {
										$time1_d = date ( 'd', strtotime ( $dateassign ) );
										$time1_m = date ( 'm', strtotime ( $dateassign ) );
										$time1_Y = date ( 'Y', strtotime ( $dateassign ) );
										$time1 = mktime ( 0, 0, 0, $time1_m, $time1_d, $time1_Y );
										$time2 = mktime ( 0, 0, 0, date ( 'm' ), date ( 'd' ), date ( 'Y' ) );
										if (($time2 - $time1) > (intval ( $validity ) * 24 * 60 * 60)) {
											echo '<div style="float: right;" class="text-warning"><b>[ Incomplete ]</b> </div>';
											$incomplete = true;
										} else {
											if ($new == '1') {
												echo '<div style="float: right;" class="text-danger"><b>New !</b> </div>';
											}
										}
									}
									?>
                        <h3 class="panel-title"><?php echo $cname; ?>&nbsp;&nbsp<?php if ($mandatory == 1) { ?><span
						class="text-danger">[Mandatory]</span><?php } ?></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-9">
						<p>
							<strong>Description:</strong>
						</p>
						<p class="expandable">
                                    <?php echo nl2br($description); ?>
                                </p>
                                <?php
									if ($course_taken) {
										
										?>
                                	<a class="btn btn-success"
							href="userDisplayCourse.php?cid=<?php echo $cid; ?>">View Score</a>
  <?php
									
} else if (! $incomplete) {
										?>
                                    <a class="btn btn-success"
							href="userDisplayCourse.php?cid=<?php echo $cid; ?>">Take Course</a>
                                       <?php
									}
									
									?>

                            </div>
					<div class="col-sm-3">
                                <?php
									$deadline = date ( 'd-m-Y', strtotime ( $dateassign . " + " . $validity . " day" ) );
									?>
                                <strong>Start Date:</strong> <?php echo date ( 'd-m-Y', strtotime ( $dateassign)); ?><br />
						<strong>Deadline:</strong> <?php echo $deadline; ?><br />
					</div>
				</div>

			</div>
		</div>
	</div>
            <?php
								}
								mysqli_stmt_close ( $stmt );
								?>



        <!-- /container -->


	<!-- Bootstrap core JavaScript
        ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/jquery-2.0.3.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="js/jquery.expander.min.js"></script>
	<script>
                    $.expander.defaults.slicePoint = 120;

                    $(document).ready(function() {
                        // simple example, using all default options unless overridden globally
                        $('p.expandable').expander();

                        // *** OR ***

                        // override default options (also overrides global overrides)
                        $('div.expandable p').expander({
                            slicePoint: 80, // default is 100
                            expandPrefix: ' ', // default is '... '
                            expandText: '[...]', // default is 'read more'
                            userCollapseText: '[^]'  // default is 'read less'
                        });

                    });
        </script>
	<script type="text/javascript">
function open_popup()
{
	mywindow=window.open('changepassword.php','targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=400');
	mywindow.moveTo(2, 2);
	return false;
}

        </script>
</body>

</html>
