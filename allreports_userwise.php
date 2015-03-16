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

<title>All Reports</title>

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
				onclick="window.location = 'adminlogout.php'">Log Out</button>
		</div>
	</div>

	<div class="container">
		<h1>Reports Dashboard</h1>
	</div>
	<br />


	<div class="container">
		<ul class="nav nav-tabs nav-justified">
			<li><a href="allreports_coursewise.php">Course Wise</a></li>
			<li class="active"><a href="allreports_userwise.php">User wise</a></li>
		</ul>
		<h3>User Wise:</h3>
	</div>
	<br />


	<div class="container">
		<div class="row">
			<div class="col-xs-9">

				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>Employee ID</th>
							<th>Name</th>
							<th>Surname</th>
							<th>Department</th>
							<th>Designation</th>
							<th>View Report</th>
						</tr>
					</thead>
					<tbody>
                            <?php
																												date_default_timezone_set ( "Asia/Kolkata" );
																												date_default_timezone_set ( "Asia/Kolkata" );
																												$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																												$query = "Select users.emp_id, users.uid, users.fname, users.lname, users.email, users.post, departments.deptname from users inner join departments where users.deptid = departments.deptid and active = 1 order by users.emp_id";
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
							<td><?php echo $deptname; ?></td>
							<td><?php echo $post; ?></td>
							<td><a class="btn btn-sm btn-success center-block"
								href="viewuserreport.php?uid=<?php echo $uid; ?>">View Courses
									Assigned</a></td>
						</tr>
                                <?php
																												}
																												?>
                        </tbody>
				</table>
				<h3 id="deactiveuserssection" class="text-danger">Deactivated users</h3>
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>Employee ID</th>
							<th>Name</th>
							<th>Surname</th>
							<th>Department</th>
							<th>Designation</th>
							<th>View Report</th>
						</tr>
					</thead>
					<tbody>
                            <?php
																												$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																												$query = "Select users.emp_id, users.uid, users.fname, users.lname, users.email, users.post, departments.deptname from users inner join departments where users.deptid = departments.deptid and active = 0 order by users.emp_id";
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
							<td><?php echo $deptname; ?></td>
							<td><?php echo $post; ?></td>
							<td><a class="btn btn-sm btn-success center-block"
								href="viewuserreport.php?uid=<?php echo $uid; ?>">View Courses
									Taken</a></td>
						</tr>
                                <?php
																												}
																												?>
                        </tbody>
				</table>
			</div>
                <?php
																$startdate = "01-01-2014";
																$datetime = new DateTime ( 'tomorrow' );
																$enddate = $datetime->format ( 'd-m-Y' );
																?>
                <div class="col-xs-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Other Actions</h3>
					</div>
					<div class="panel-body">
						<p>Info: The activated users are listed below followed by the
							deactivated users.</p>
						<p>
							Click <a href="#deactiveuserssection">here</a> to jump to
							deactivated users.
						</p>
						<hr />
						<p style="text-decoration: underline">
							<strong>Download report for all users</strong>
						</p>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="startDate">Start Date</label> <input type="text"
										class="form-control datepicker" id="startDate"
										data-date-format="dd-mm-yyyy" name="startDate"
										value="<?php echo $startdate ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="endDate">End Date</label> <input type="text"
										class="form-control datepicker" id="endDate"
										data-date-format="dd-mm-yyyy" name="endDate"
										value="<?php echo $enddate ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
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
                                                <option><?php echo $deptname; ?></option>
                                                <?php
																																												}
																																												?>


                                        </select>
								</div>

							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="post">Post</label> <select class="form-control"
										id="post" name="post">
										<option>All</option>
									</select>
								</div>
							</div>
						</div>

						<button class="btn btn-primary" onclick="exportfilter()">Download
						</button>
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
	<script src="datepicker/js/bootstrap-datepicker.js"></script>

	<script>
                                function exportfilter() {
                                    var startDate = $('#startDate').val();
                                    var endDate = $('#endDate').val();
                                    var department = $('#department').val();
                                    var post = $('#post').val();
                                    var url = "allusersreport.php?";
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
        </script>

</body>

</html>
