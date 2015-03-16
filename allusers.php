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

<title>All Users</title>

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
<script type="text/javascript">

window.onload = function(){
	 setTimeout(function(){
	   alert("Session Timed Out! Please Login Again");
	   window.location = "adminlogout.php";
	 }, 10800000);
	};
</script>
<script>
            function addPost(deptid) {
                var post = prompt("Enter new post: ");
				if (post !== null) {
					window.location.href = "addpost.php?post=" + post + "&deptid=" + deptid;
                }
            }
            function addDept(deptname) {
                var dept = prompt("Enter new department: ");
                if (dept !== null) {
                    window.location.href = "adddept.php?dept=" + dept;
                }
            }
            function deactivateUser(uid, name) {
                var ans = confirm("Are you sure you want to deactivate this user:\n\n" + name);
                if (ans) {
                    window.location = "deactivateuser.php?uid=" + uid + "&deactivate=1";
                }
            }
            function resetPass(uid, name) {
                var ans = confirm("Are you sure you want to reset password for this user:\n\n" + name);
                if (ans) {
                    window.location = "resetsyspass.php?uid=" + uid + "&deactivate=1";
                }
            }
            function deactivateadmin(aid, name) {
                var ans = confirm("Are you sure you want to deactivate this admin:\n\n" + name);
                if (ans) {
                    window.location = "deactivateadmin.php?aid=" + aid + "&deactivate=1";
                }
            }
            function activateadmin(aid, name) {
                var ans = confirm("Are you sure you want to activate this user:\n\n" + name);
                if (ans) {
                    window.location = "deactivateadmin.php?aid=" + aid + "&deactivate=0";
                }
            }
            function activateUser(uid, name) {
                var ans = confirm("Are you sure you want to activate this user:\n\n" + name);
                if (ans) {
                    window.location = "deactivateuser.php?uid=" + uid + "&deactivate=0";
                }
            }
            function deletePost(deptid, post) {
                var ans = confirm("Are you sure you want to delete the Designation:\n\n" + post);
                if (ans) {
                    window.location = "deletepost.php?post=" + post + "&deptid=" + deptid;
                }
            }
			function deleteDept(deptid, deptnames) {
                var ans = confirm("Are you sure you want to delete the Department:\n\n" + deptnames);
                if (ans) {
                    window.location = "deletedept.php?deptid=" + deptid;
                }
            }
        </script>
</head>

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
		<h1>Manage Users</h1>
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
							<th>Edit User</th>
							<th>Deactivate</th>
							<th> Reset Password </th>
						</tr>
					</thead>
					<tbody>
                            <?php
																												$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																												$query = "Select users.emp_id, users.uid, users.fname, users.lname, users.email, users.post, departments.deptname from users inner join departments where users.deptid = departments.deptid and active = 1 order by users.emp_id";
																												$stmt = mysqli_prepare ( $db, $query );
																												mysqli_stmt_execute ( $stmt );
																												mysqli_stmt_store_result ( $stmt );
																												mysqli_stmt_bind_result ( $stmt, $emp_id, $uid, $fname, $lname, $email, $post, $deptname );
																												
																												while ( mysqli_stmt_fetch ( $stmt ) ) {
																													?>
                                <tr>
							<td><?php echo $emp_id; ?></td>
							<td><?php echo $fname; ?></td>
							<td><?php echo $lname; ?></td>
							<td><?php echo $deptname; ?></td>
							<td><?php echo $post; ?></td>
							<td><a class="btn btn-sm btn-success center-block"
								href="edituser.php?uid=<?php echo $uid; ?>">Edit</a></td>
							<td><a class="btn btn-sm btn-danger center-block"
								onclick='deactivateUser(<?php echo $uid . ',"' . ($fname . " " . $lname) . '"'; ?>)'>Deactivate</a></td>
							<td><a class="btn btn-sm btn-success center-block"
								onclick='resetPass(<?php echo $uid . ',"' . ($fname . " " . $lname) . '"'; ?>)'>Reset</a></td>
						</tr>
                                <?php
																												}
																												?>
                        </tbody>
				</table>
				<h3 id="deactivatedusers" class="text-danger">Deactivated users</h3>
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>Employee ID</th>
							<th>Name</th>
							<th>Surname</th>
							<th>Department</th>
							<th>Designation</th>
							<th>Edit User</th>
							<th>Deactivate</th>
						</tr>
					</thead>
					<tbody>
                            <?php
																												$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																												$query = "Select users.emp_id, users.uid, users.fname, users.lname, users.email, users.post, departments.deptname from users inner join departments where users.deptid = departments.deptid and active = 0 order by emp_id";
																												$stmt = mysqli_prepare ( $db, $query );
																												mysqli_stmt_execute ( $stmt );
																												mysqli_stmt_store_result ( $stmt );
																												mysqli_stmt_bind_result ( $stmt, $emp_id, $uid, $fname, $lname, $email, $post, $deptname );
																												
																												while ( mysqli_stmt_fetch ( $stmt ) ) {
																													?>
                                <tr>
							<td><?php echo $emp_id; ?></td>
							<td><?php echo $fname; ?></td>
							<td><?php echo $lname; ?></td>
							<td><?php echo $deptname; ?></td>
							<td><?php echo $post; ?></td>
							<td><a class="btn btn-sm btn-success center-block"
								href="edituser.php?uid=<?php echo $uid; ?>">Edit</a></td>
							<td><a class="btn btn-sm btn-danger center-block"
								onclick='activateUser(<?php echo $uid . ',"' . ($fname . " " . $lname) . '"'; ?>)'>Activate</a></td>
						</tr>
                                <?php
																												}
																												?>
                        </tbody>
				</table>

				<h3 id="activeadmin" class="text-danger">Admins</h3>
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>Name</th>
							<th>Surname</th>
							<th>Email</th>
							<th>Edit User</th>
							<th>Deactivate</th>
						</tr>
					</thead>
					<tbody>
                            <?php
																												$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																												$query = "Select aid, fname, lname, email from admin WHERE active=1";
																												$stmt = mysqli_prepare ( $db, $query );
																												mysqli_stmt_execute ( $stmt );
																												mysqli_stmt_store_result ( $stmt );
																												mysqli_stmt_bind_result ( $stmt, $aid, $fname, $lname, $email );
																												
																												while ( mysqli_stmt_fetch ( $stmt ) ) {
																													?>
                                <tr>
							<td><?php echo $fname; ?></td>
							<td><?php echo $lname; ?></td>
							<td><?php echo $email; ?></td>
							<td><a class="btn btn-sm btn-success center-block"
								href="editadmin.php?aid=<?php echo $aid; ?>">Edit</a></td>
							<td><a class="btn btn-sm btn-danger center-block"
								onclick='deactivateadmin(<?php echo $aid . ',"' . ($fname . " " . $lname) . '"'; ?>)'>Deactivate</a></td>
						</tr>
                                <?php
																												}
																												?>
                        </tbody>
				</table>

				<h3 id="deactiveadmin" class="text-danger">Deactivated Admins</h3>
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>Name</th>
							<th>Surname</th>
							<th>Email</th>
							<th>Edit User</th>
							<th>Deactivate</th>
						</tr>
					</thead>
					<tbody>
                            <?php
																												$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																												$query = "Select aid, fname, lname, email from admin WHERE active=0";
																												$stmt = mysqli_prepare ( $db, $query );
																												mysqli_stmt_execute ( $stmt );
																												mysqli_stmt_store_result ( $stmt );
																												mysqli_stmt_bind_result ( $stmt, $aid, $fname, $lname, $email );
																												
																												while ( mysqli_stmt_fetch ( $stmt ) ) {
																													?>
                                <tr>
							<td><?php echo $fname; ?></td>
							<td><?php echo $lname; ?></td>
							<td><?php echo $email; ?></td>
							<td><a class="btn btn-sm btn-success center-block"
								href="editadmin.php?aid=<?php echo $aid; ?>">Edit</a></td>
							<td><a class="btn btn-sm btn-danger center-block"
								onclick='activateadmin(<?php echo $aid . ',"' . ($fname . " " . $lname) . '"'; ?>)'>Activate</a></td>
						</tr>
                                <?php
																												}
																												?>
                        </tbody>
				</table>

			</div>
			<div class="col-xs-3">
				<div class="row">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Other Actions</h3>
						</div>
						<div class="panel-body">
							<p>Info: All active users are listed to the left. Inactive users
								are listed below list of active users.</p>
							<p>
								Click <a href="#deactivatedusers">here</a> to jump to list of
								inactive users.
							</p>
							<p>
								Click <a href="#activeadmin">here</a> to jump to list of active
								admins.
							</p>
							<p>
								Click <a href="#deactiveadmin">here</a> to jump to list of
								inactive admins.
							</p>

							<a class="btn btn-success" href='createuser.php'>Add User</a>
							&nbsp; <a class="btn btn-success" onclick='addDept()'>Add
								Department</a>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Existing Departments</h3>
						</div>
						<div class="panel-body">
                                <?php
																																$deptids = array ();
																																$deptnames = array ();
																																$query = "Select deptid, deptname from departments";
																																$stmt = mysqli_prepare ( $db, $query );
																																mysqli_stmt_execute ( $stmt );
																																mysqli_stmt_store_result ( $stmt );
																																mysqli_stmt_bind_result ( $stmt, $deptid, $deptname );
																																
																																while ( mysqli_stmt_fetch ( $stmt ) ) {
																																	array_push ( $deptids, $deptid );
																																	array_push ( $deptnames, $deptname );
																																}
																																?>
                                <?php
																																for($n = 0; $n < sizeof ( $deptids ); $n ++) {
																																	
																																	?>
                                <p>
							
							
							<table class='table'>
								<tr>
									<td><?php echo $deptnames[$n];?></td>
									<td><a
										onclick='deleteDept(<?php echo $deptids[$n]?>,"<?php echo $deptnames[$n]; ?>")'><span
											class='glyphicon glyphicon-trash ' style='cursor: pointer;'></span></a></td>
								</tr>
							</table>
							</p>
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>Designation</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
                                        <?php
																																	$query = "Select post from posts where deptid=$deptids[$n]";
																																	$stmt = mysqli_prepare ( $db, $query );
																																	mysqli_stmt_execute ( $stmt );
																																	mysqli_stmt_store_result ( $stmt );
																																	mysqli_stmt_bind_result ( $stmt, $post );
																																	
																																	while ( mysqli_stmt_fetch ( $stmt ) ) {
																																		?>
                                            <tr>
										<td><?php echo $post; ?></td>
										<td><a
											onclick='deletePost(<?php echo $deptids[$n];?>,"<?php echo $post; ?>")'><span
												class="glyphicon glyphicon-trash center-block"
												style="cursor: pointer;"></span></a></td>
									</tr>
                                            <?php
																																	}
																																	?>
                                            <tr>
										<td><em>Add Designation</em></td>
										<td><a onclick='addPost(<?php echo $deptids[$n];?>)'><span
												class="glyphicon glyphicon-plus center-block"
												style="cursor: pointer;"></span></a></td>
									</tr>
								</tbody>
							</table
                                <?php
																																}
																																?>
                            
						
						
						
						
						
						
						
						
						
						</div>
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
</body>

</html>