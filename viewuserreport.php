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
if (! isset ( $_GET ['uid'] )) {
	?>

<script language="javascript">window.location = "allreports.php"</script>

<?php
	exit ();
}
$uid = $_GET ['uid'];
$uidcopy = $uid;
date_default_timezone_set ( "Asia/Kolkata" );
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$startdate = "01-01-2014";
$datetime = new DateTime ( 'tomorrow' );
$enddate = $datetime->format ( 'd-m-Y' );
if (isset ( $_GET ['startdate'] )) {
	$startdate = $_GET ['startdate'];
}
if (isset ( $_GET ['enddate'] )) {
	$enddate = $_GET ['enddate'];
}

if (isset ( $_GET ['export'] )) {
	
	// Original PHP code by Chirp Internet: www.chirp.com.au
	// Please acknowledge use of this code by including this header.
	function cleanData(&$str) {
		$str = preg_replace ( "/\t/", "\\t", $str );
		$str = preg_replace ( "/\r?\n/", "\\n", $str );
		if (strstr ( $str, '"' ))
			$str = '"' . str_replace ( '"', '""', $str ) . '"';
	}
	
	// filename for download
	$filename = "website_data_" . date ( 'Ymd' ) . ".xls";
	
	header ( "Content-Disposition: attachment; filename=\"$filename\"" );
	header ( "Content-Type: application/vnd.ms-excel" );
	
	echo "User Report:";
	echo "\n";
	
	$flag = false;
	
	$q = "Select users.uid, users.fname, users.lname, users.email, users.post, departments.deptname from users inner join departments where users.uid = " . $uid . " and users.deptid = departments.deptid ";
	$result = mysqli_query ( $db, $q ) or die ( 'Query failed!' );
	
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		if (! $flag) {
			// display field/column names as first row
			echo implode ( "\t", array_keys ( $row ) ) . "\r\n";
			$flag = true;
		}
		array_walk ( $row, 'cleanData' );
		echo implode ( "\t", array_values ( $row ) ) . "\r\n";
		echo "\n\n\n";
	}
	
	echo "Courses taken by this user:";
	echo "\n";
	
	$flag = false;
	
	$q = "Select courses.cid,courses.cname,courses.description,courses.mandatory,scores.score,scores.date_of_test from courses left join scores on courses.cid = scores.cid where courses.cid in (Select courserpost.cid from users inner join courserpost where users.post = courserpost.post and users.deptid = courserpost.deptid and uid = " . $uid . ")";
	$result = mysqli_query ( $db, $q ) or die ( 'Query failed!' );
	
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		if (! $flag) {
			// display field/column names as first row
			echo implode ( "\t", array_keys ( $row ) ) . "\r\n";
			$flag = true;
		}
		array_walk ( $row, 'cleanData' );
		echo implode ( "\t", array_values ( $row ) ) . "\r\n";
		echo "\n";
	}
	exit ();
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

<title>User Report</title>

<!-- Bootstrap core CSS -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/navbar.css" rel="stylesheet">
<link href="datepicker/css/datepicker.css" rel="stylesheet">

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
			<button type="button" class="btn btn-success"
				onclick="window.location = 'adminhome.php'">Admin Home</button>
			<button type="button" class="btn btn-primary"
				onclick="window.location = 'userlogout.php'">Log Out</button>
		</div>
		<div class="pull-left">
			<button type="button" class="btn btn-success"
				onclick="window.location = 'allreports_userwise.php'">Back to
				Reports</button>
		</div>
	</div>

	<div class="container">
		<h3>User Report</h3>
		<br />

		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>Employee ID</th>
					<th>First Name</th>
					<th>last Name</th>
					<th>E-Mail</th>
					<th>Dept</th>
					<th>Post</th>
				</tr>
			</thead>
			<tbody>
                    <?php
																				$uid = $_GET ['uid'];
																				
																				$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																				$query = "Select users.emp_id, users.uid, users.fname, users.lname, users.email, users.post, departments.deptname from users inner join departments where users.uid = " . $uid . " and users.deptid = departments.deptid ";
																				$stmt = mysqli_prepare ( $db, $query );
																				mysqli_stmt_execute ( $stmt );
																				mysqli_stmt_store_result ( $stmt );
																				mysqli_stmt_bind_result ( $stmt, $eid, $uid, $fname, $lname, $email, $post, $deptname );
																				
																				while ( mysqli_stmt_fetch ( $stmt ) ) {
																					?>
                        <tr>
                        
					<td><?php echo $eid; ?></td>
					<td><?php echo $fname; ?></td>
					<td><?php echo $lname; ?></td>
					<td><?php echo $email; ?></td>
					<td><?php echo $deptname; ?></td>
					<td><?php echo $post; ?></td>
				</tr>
                        <?php
																				}
																				?>
                </tbody>
		</table>
	</div>

	<div class="container">
		<h3>Courses assigned to this user:</h3>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="startDate">Start Date</label> <input type="text"
						class="form-control datepicker" id="startDate"
						data-date-format="dd-mm-yyyy" name="startDate"
						value="<?php echo $startdate ?>">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="endDate">End Date</label> <input type="text"
						class="form-control datepicker" id="endDate"
						data-date-format="dd-mm-yyyy" name="endDate"
						value="<?php echo $enddate ?>">
				</div>
			</div>

			<div class="col-sm-4">
				<br />
				<button class="btn btn-primary" onclick="filter()">Filter</button>
				<button class="btn btn-primary" onclick="exportfilter()">
					<span class="glyphicon glyphicon-save"></span>
				</button>
			</div>
		</div>
	</div>
	<br />


	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>Course</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Active</th>
							<th>Mandatory</th>
							<th>Status</th>
							<th>Test Date</th>
							<th>Time Taken (HH:MM)</th>
							<th>Score</th>
							<th>Total Time (HH:MM)</th>
							<th>Out Of</th>

						</tr>
					</thead>
					<tbody>
                            <?php
																												$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																												$query = "SELECT cid, dateassign FROM user_assign WHERE uid=$uid AND STR_TO_DATE(dateassign,'%Y-%m-%d')>=STR_TO_DATE('$startdate','%d-%m-%Y') ORDER BY STR_TO_DATE(dateassign,'%Y-%m-%d') DESC";
																												
																												$stmt = mysqli_prepare ( $db, $query );
																												mysqli_stmt_execute ( $stmt );
																												mysqli_stmt_store_result ( $stmt );
																												mysqli_stmt_bind_result ( $stmt, $cid, $dateassign );
																												$num = mysqli_stmt_num_rows ( $stmt );
																												while ( mysqli_stmt_fetch ( $stmt ) ) {
																													
																													$query = "SELECT cname, active, mandatory,validity_duration FROM courses WHERE cid=$cid";
																													$stmt1 = mysqli_prepare ( $db, $query );
																													mysqli_stmt_execute ( $stmt1 );
																													mysqli_stmt_store_result ( $stmt1 );
																													mysqli_stmt_bind_result ( $stmt1, $cname, $active, $mandatory, $validity );
																													
																													while ( mysqli_stmt_fetch ( $stmt1 ) ) {
																														
																														$date1 = strtotime ( $dateassign. " +" . $validity . " days" );
																														$date2 = strtotime ( $enddate );
																														
																														if ($date1 <= $date2) 

																														{
																															if ($active == 1)
																																$active = "Yes";
																															else
																																$active = "No";
																															
																															if ($mandatory == 1)
																																$mandatory = "Yes";
																															else
																																$mandatory = "No";
																															$finishdt = strtotime ( date ( "d-m-Y", strtotime ( $dateassign ) ) . " +" . $validity . " day" );
																															$row = "<tr><td>" . $cname . "</td><td>" . date ( "d-m-Y", strtotime ( $dateassign )) . "</td><td>" . date ( "d-m-Y", $finishdt ) . "</td><td>" . $active . "</td><td>" . $mandatory . "</td>";
																															
																															$query = "SELECT timestamp_of_test, time_taken_hr, time_taken_min, score FROM attempts WHERE cid=$cid AND uid=$uid";
																															$stmt3 = mysqli_prepare ( $db, $query );
																															mysqli_stmt_execute ( $stmt3 );
																															mysqli_stmt_store_result ( $stmt3 );
																															mysqli_stmt_bind_result ( $stmt3, $timestamp_of_test, $time_taken_hr, $time_taken_min, $score );
																															$num = mysqli_stmt_num_rows ( $stmt3 );
																															if ($num == 0) {
																																$row = $row . "<td>Incomplete</td><td></td><td></td><td></td>";
																															} else {
																																while ( mysqli_stmt_fetch ( $stmt3 ) ) {
																																	$row = $row . "<td>Complete</td><td>" . $timestamp_of_test . "</td><td>" . $time_taken_hr . ":" . $time_taken_min . "</td><td>" . $score . "</td>";
																																}
																															}
																															
																															$query = "SELECT time_hr, time_min, score_out_of FROM tests WHERE ctestid=$cid";
																															$stmt4 = mysqli_prepare ( $db, $query );
																															mysqli_stmt_execute ( $stmt4 );
																															mysqli_stmt_store_result ( $stmt4 );
																															mysqli_stmt_bind_result ( $stmt4, $time_hr, $time_min, $score_out_of );
																															$num = mysqli_stmt_num_rows ( $stmt4 );
																															if ($num == 0) {
																																$row = $row . "<td></td><td></td>";
																															} else {
																																while ( mysqli_stmt_fetch ( $stmt4 ) ) {
																																	$row = $row . "<td>" . $time_hr . ":" . $time_min . "</td><td>" . $score_out_of . "</td>";
																																}
																															}
																															$row = $row . "</tr>";
																															echo $row;
																														}
																													}
																												}
																												?>
                        </tbody>
				</table>

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
	<script src="datepicker/js/bootstrap-datepicker.js"></script>
	<script>
                        function filter() {
                            var startDate = $('#startDate').val();
                            var endDate = $('#endDate').val();
                            var url = "viewuserreport.php?uid=<?php echo $_GET['uid'] ?>&";
                            if (startDate != "") {
                                url += "startdate=" + startDate + "&";
                            }
                            if (endDate != "") {
                                url += "enddate=" + endDate + "&";
                            }
                            window.location = url;
                        }
                        function exportfilter() {
                            var startDate = $('#startDate').val();
                            var endDate = $('#endDate').val();
                            var url = "getUserReport.php?uid=<?php echo $_GET['uid'] ?>&";
                            if (startDate != "") {
                                url += "startdate=" + startDate + "&";
                            }
                            if (endDate != "") {
                                url += "enddate=" + endDate + "&";
                            }
                            window.location = url;
                        }
        </script>

	<script>
            $('.datepicker').datepicker();

        </script>
</body>

</html>
