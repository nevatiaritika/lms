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

<title>All Courses</title>

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
            function deactivateCourse(cid, cname) {
                var ans = confirm("Are you sure you want to deactivate course " + cname + "?\n\n WARNING: Deactivated courses are not available to assigned users");
                if (ans) {
                    window.location.href = "deactivatecourse.php?cid=" + cid + "&deactivate=1";
                }
            }
            function activateCourse(cid, cname) {
                var ans = confirm("Are you sure you want to activate course?\n\n" + cname);
                if (ans) {
                    window.location.href = "deactivatecourse.php?cid=" + cid + "&deactivate=0";
                }
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
		<div class="pull-right">
			<button type="button" class="btn btn-success"
				onclick="window.location = 'adminhome.php'">Admin Home</button>
			<button type="button" class="btn btn-primary"
				onclick="window.location = 'adminlogout.php'">Log Out</button>
		</div>
	</div>
	<div class="container">
		<h1>Course Dashboard</h1>
	</div>
	<br />

	<div class="container">
		<div class="row">
			<div class="col-xs-9">
                    <?php
																				date_default_timezone_set ( "Asia/Kolkata" );
																				$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																				$query = "Select cid,cname,description,mandatory from courses where active=1 order by STR_TO_DATE(created_ts, '%d-%m-%Y') desc";
																				$stmt = mysqli_prepare ( $db, $query );
																				mysqli_stmt_execute ( $stmt );
																				mysqli_stmt_store_result ( $stmt );
																				mysqli_stmt_bind_result ( $stmt, $cid, $cname, $description, $mandatory );
																				$num = mysqli_stmt_num_rows ( $stmt );
																				while ( mysqli_stmt_fetch ( $stmt ) ) {
																					?>

                        <div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title" style="display: inline;"><?php echo $cname; ?>&nbsp;&nbsp;<?php if ($mandatory == 1) { ?><span
								class="text-danger">[Mandatory]</span><?php } ?></h3>
						<span class="pull-right"> <a class="btn btn-xs btn-success"
							href="editcourse.php?cid=<?php echo $cid; ?>">Edit Course</a>
							&nbsp;&nbsp; <a class="btn btn-xs btn-warning"
							onclick='deactivateCourse(<?php echo $cid . ',"' . $cname . '"' ?>)'>Deactivate</a>
						</span>
					</div>
					<div class="panel-body">
						<p>
							<strong>Description:</strong>
						</p>
						<p class="expandable">
                                    <?php echo nl2br($description); ?>
                                </p>
					</div>
				</div>

                        <?php
																				}
																				mysqli_stmt_close ( $stmt );
																				?>
                    <h3 id="inactivecoursessection" class="text-danger">Inactive
					Courses</h3>
                    <?php
																				$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																				$query = "Select cid,cname,description,mandatory from courses where active=0 order by STR_TO_DATE(created_ts, '%d-%m-%Y') desc";
																				$stmt = mysqli_prepare ( $db, $query );
																				mysqli_stmt_execute ( $stmt );
																				mysqli_stmt_store_result ( $stmt );
																				mysqli_stmt_bind_result ( $stmt, $cid, $cname, $description, $mandatory );
																				$num = mysqli_stmt_num_rows ( $stmt );
																				while ( mysqli_stmt_fetch ( $stmt ) ) {
																					?>

                        <div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title" style="display: inline;"><?php echo $cname; ?>&nbsp;&nbsp;<?php if ($mandatory == 1) { ?><span
								class="text-danger">[Mandatory]</span><?php } ?></h3>
						<span class="pull-right"> <a class="btn btn-xs btn-success"
							href="editcourse.php?cid=<?php echo $cid; ?>">Edit Course</a>
							&nbsp;&nbsp; <a class="btn btn-xs btn-warning"
							onclick='activateCourse(<?php echo $cid . ',"' . $cname . '"' ?>)'>Activate</a>
						</span>
					</div>
					<div class="panel-body">
						<p>
							<strong>Description:</strong>
						</p>
						<p class="expandable">
                                    <?php echo nl2br($description); ?>
                                </p>
					</div>
				</div>

                        <?php
																				}
																				mysqli_stmt_close ( $stmt );
																				?>
                </div>
			<div class="col-xs-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Other Actions</h3>
					</div>
					<div class="panel-body">
						<p>Info: The active courses are listed below followed by the
							inactive courses.</p>
						<p>
							Click <a href="#inactivecoursessection">here</a> to jump to
							inactive courses.
						</p>
						<a class="btn btn-success" href='createcourse.php'>Create Course</a>
					</div>
				</div>
			</div>
		</div>

	</div>


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
</body>

</html>
