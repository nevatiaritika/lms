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
if (! isset ( $_GET ['cid'] )) {
	?>

<script language="javascript">window.location = "allreports.php"</script>

<?php
	exit ();
}
$cid = $_GET ['cid'];
date_default_timezone_set ( "Asia/Kolkata" );
$startdate = "01-01-2014";
$datetime = new DateTime ( 'tomorrow' );
$enddate = $datetime->format ( 'd-m-Y' );
$dept = "All";
$post = "All";
if (isset ( $_GET ['startdate'] )) {
	$startdate = $_GET ['startdate'];
}
if (isset ( $_GET ['enddate'] )) {
	$enddate = $_GET ['enddate'];
}

if (isset ( $_GET ['dept'] )) {
	$dept = $_GET ['dept'];
}
if (isset ( $_GET ['post'] )) {
	$post = $_GET ['post'];
}
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );

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

<title>Course Report</title>

<!-- Bootstrap core CSS -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/navbar.css" rel="stylesheet">
<link href="datepicker/css/datepicker.css">

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
				onclick="window.location = 'adminlogout.php'">Log Out</button>
		</div>
		<div class="pull-left">
			<button type="button" class="btn btn-success"
				onclick="window.location = 'allreports_coursewise.php'">Back to
				Reports</button>
		</div>
	</div>

	<div class="container">
		<h3>Course Report</h3>
		<br />
            <?php
												$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
												$query = "Select cid,cname,description,mandatory, validity_duration from courses where cid=" . $cid;
												$stmt = mysqli_prepare ( $db, $query );
												mysqli_stmt_execute ( $stmt );
												mysqli_stmt_store_result ( $stmt );
												mysqli_stmt_bind_result ( $stmt, $cid, $cname, $description, $mandatory, $val );
												$num = mysqli_stmt_num_rows ( $stmt );
												while ( mysqli_stmt_fetch ( $stmt ) ) {
													?>

                <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo $cname; ?>&nbsp;&nbsp;<?php if ($mandatory == 1) { ?><span
						class="text-danger">[Mandatory]</span><?php } ?></h3>
			</div>
			<div class="panel-body">
				<p>
					<strong>Description:</strong>
				</p>
				<p class="expandable">
                            <?php echo $description; ?>
                        </p>

			</div>
		</div> <?php
												}
												mysqli_stmt_close ( $stmt );
												?>

        </div>

	<div class="container">
		<h3>Users who have taken this course:</h3>
		<div class="row">
			<div class="col-sm-2">
				<div class="form-group">
					<label for="startDate">Start Date</label> <input type="text"
						class="form-control datepicker" id="startDate"
						data-date-format="dd-mm-yyyy" name="startDate"
						value="<?php echo $startdate ?>">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<label for="endDate">End Date</label> <input type="text"
						class="form-control datepicker" id="endDate"
						data-date-format="dd-mm-yyyy" name="endDate"
						value="<?php echo $enddate ?>">
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="department">Department</label> <select
						class="form-control" id="department" name="department"
						onchange="getPosts()">
						<option>All</option>
                            <?php
																												$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																												$query = "Select deptname from departments";
																												$stmt = mysqli_prepare ( $db, $query );
																												mysqli_stmt_execute ( $stmt );
																												mysqli_stmt_store_result ( $stmt );
																												mysqli_stmt_bind_result ( $stmt, $deptname );
																												$num = mysqli_stmt_num_rows ( $stmt );
																												while ( mysqli_stmt_fetch ( $stmt ) ) {
																													?>
                                <option
							<?php
																													if ($dept == $deptname) {
																														echo 'selected=""';
																													}
																													?>><?php echo $deptname; ?></option>
                                    <?php
																												}
																												?>


                        </select>
				</div>

			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="post">Post</label> <select class="form-control"
						id="post" name="post">
						<option>All</option>
					</select>
				</div>
			</div>
			<div class="col-sm-2">
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
							<th>Employee ID</th>
							<th>Active</th>
							<th>Name</th>
							<th>Surname</th>
							<th>Dept</th>
							<th>Desg</th>
							<th>Status</th>
							<th>Score</th>
							<th>Out of</th>
							<th>Test Date</th>
							<th>Total time (HH:MM)</th>
							<th>Time Taken (HH:MM)</th>
						</tr>
					</thead>
					<tbodyExport>
                            <?php
																												$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																												
																												$query = "select uid from user_assign where STR_TO_DATE(dateassign,'%Y-%m-%d')>=STR_TO_DATE('$startdate','%d-%m-%Y') AND DATE_ADD(dateassign, INTERVAL $val DAY)<=STR_TO_DATE('$enddate','%d-%m-%Y') AND cid=$cid";
																												
																												if ($dept != 'All' && $post != 'All') {
																													$query = $query . " AND uid IN (SELECT uid FROM users WHERE deptid=(SELECT deptid FROM departments WHERE deptname='$dept') AND post = '$post')";
																												}
																												if ($dept != 'All' && $post == 'All') {
																													$query = $query . " AND uid IN (SELECT uid FROM users WHERE deptid=(SELECT deptid FROM departments WHERE deptname='$dept'))";
																												}
																												// $query = "call GetCourseReports($cid)";
																												
																												$query = $query . " ORDER BY emp_id";
																												$stmt = mysqli_prepare ( $db, $query );
																												mysqli_stmt_execute ( $stmt );
																												mysqli_stmt_store_result ( $stmt );
																												mysqli_stmt_bind_result ( $stmt, $uid );
																												$num = mysqli_stmt_num_rows ( $stmt );
																												while ( mysqli_stmt_fetch ( $stmt ) ) {
																													
																													$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																													
																													$query = "SELECT users.emp_id, users.active, users.fname, users.lname, departments.deptname, users.post FROM users LEFT JOIN departments ON users.deptid=departments.deptid WHERE users.uid = $uid";
																													$stmt1 = mysqli_prepare ( $db, $query );
																													mysqli_stmt_execute ( $stmt1 );
																													mysqli_stmt_store_result ( $stmt1 );
																													mysqli_stmt_bind_result ( $stmt1, $eid, $active, $fname, $lname, $deptname, $post );
																													while ( mysqli_stmt_fetch ( $stmt1 ) ) {
																														if ($active == 1) {
																															$row = "<tr><td>" . $eid . "</td><td>Yes</td><td>" . $fname . "</td><td>" . $lname . "</td><td>" . $deptname . "</td><td>" . $post . "</td>";
																														} else {
																															$row = "<tr><td>" . $eid . "</td><td>No</td><td>" . $fname . "</td><td>" . $lname . "</td><td>" . $deptname . "</td><td>" . $post . "</td>";
																														}
																														
																														$query = "SELECT attempts.timestamp_of_test,attempts.score,attempts.time_taken_hr, attempts.time_taken_min,tests.time_hr, tests.time_min, tests.score_out_of FROM attempts LEFT JOIN tests ON attempts.cid = tests.ctestid WHERE attempts.uid=$uid AND attempts.cid=$cid";
																														$stmt2 = mysqli_prepare ( $db, $query );
																														mysqli_stmt_execute ( $stmt2 );
																														mysqli_stmt_store_result ( $stmt2 );
																														mysqli_stmt_bind_result ( $stmt2, $timestamp_of_test, $score, $time_taken_hr, $time_taken_min, $time_hr, $time_min, $score_out_of );
																														$num = mysqli_stmt_num_rows ( $stmt2 );
																														if ($num == 0) {
																															$row = $row . "<td>Incomplete</td><td></td><td></td><td></td><td></td><td></td>";
																														} else {
																															while ( mysqli_stmt_fetch ( $stmt2 ) ) {
																																$row = $row . "<td>Complete</td><td>" . $score . "</td><td>" . $score_out_of . "</td><td>" . $timestamp_of_test . "</td><td>" . $time_taken_hr . ":" . $time_taken_min . "</td><td>" . $time_hr . ":" . $time_min . "</td>";
																															}
																														}
																														
																														echo $row . "</tr>";
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
	<script src="js/jquery.expander.min.js"></script>
	<script src="datepicker/js/bootstrap-datepicker.js"></script>

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

                            if ($("#departments").val() != "All") {
                                getPostsInitially();


                            }

                        });


        </script>
	<script>
            function filter() {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var department = $('#department').val();
                var post = $('#post').val();
                var url = "viewcoursereport.php?cid=<?php echo $_GET['cid'] ?>&";
                if (startDate != "") {
                    url += "startdate=" + startDate + "&";
                }
                if (endDate != "") {
                    url += "enddate=" + endDate + "&";
                }
                if (department != "") {
                    url += "dept=" + department + "&";
                }
                if (post != "") {
                    url += "post=" + post + "&";
                }
                window.location = url;
            }
            function exportfilter() {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var department = $('#department').val();
                var post = $('#post').val();
                var url = "getCourseReport.php?cid=<?php echo $_GET['cid'] ?>&";
                if (startDate != "") {
                    url += "startdate=" + startDate + "&";
                }
                if (endDate != "") {
                    url += "enddate=" + endDate + "&";
                }
                if (department != "") {
                    url += "dept=" + department + "&";
                }
                if (post != "") {
                    url += "post=" + post + "&";
                }
                window.location = url;
            }
        </script>

	<script>
            $('.datepicker').datepicker();

            function getPosts() {
                var dept = $('#department').val();
                if (dept == "All") {
                    $('#post').html("<option>All</option>");
                    return;
                }
                $('#post').html("<option>Loading....</option>");
                $.get("getPostsForDept.php", {"department": dept}, function(posts) {
                    posts = JSON.parse(posts);
                    $('#post').html("<option>All</option>");
                    for (var i = 0; i < posts.length; i++) {
                        $('#post').html($('#post').html() + "<option>" + posts[i] + "</option>");
                    }
                });
            }
            function getPostsInitially() {
                var dept = $('#department').val();
                if (dept == "All") {
                    $('#post').html("<option>All</option>");
                    return;
                }
                $('#post').html("<option>Loading....</option>");
                $.get("getPostsForDept.php", {"department": dept}, function(posts) {
                    posts = JSON.parse(posts);
                    $('#post').html("<option>All</option>");
                    for (var i = 0; i < posts.length; i++) {
                        $('#post').html($('#post').html() + "<option>" + posts[i] + "</option>");
                    }
                    <?php
																				if ($post != "All") {
																					?>
                            $("#post").val("<?php echo $post; ?>");
                            <?php
																				}
																				?>
                });
            }
        </script>
</body>

</html>
