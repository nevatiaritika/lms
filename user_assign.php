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
<script language="javascript">window.location = "allcourses.php"</script>
<?php
	exit ();
}
$cid = $_GET ['cid'];
$dept = "";
if (isset ( $_GET ['dept'] )) {
	$dept = $_GET ['dept'];
}
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$query = "Select cid,cname,description,mandatory,validity_duration from courses where cid = '" . $cid . "'";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $cid, $cname, $description, $mandatory, $validity );
mysqli_stmt_fetch ( $stmt );
mysqli_stmt_close ( $stmt );
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

<title>Edit Course: <?php echo $cname; ?></title>

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
            function toggle(source) {
                checkboxes = document.getElementsByName('posts[]');
                for (var i = 0, n = checkboxes.length; i < n; i++) {
                    checkboxes[i].checked = source.checked;
                }
            }
            function changeList(val) {
                //Forward browser to new url
                window.location = 'user_assign.php?cid=<?php echo $cid ?>&dept=' + val;
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
		<div class="pull-left">
			<button type="button" class="btn btn-success"
				onclick="window.location = 'editcourse.php?cid=<?php echo $cid; ?>>'">Back
				to Course</button>
		</div>
	</div>
	<div class="container">
		<h3>Assign Course: <?php echo $cname; ?></h3>
		<br />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4>Department wise</h4>

					</div>
					<div class="panel-body">

						<form role="form" method="POST" action="departmentwise.php">
							<input type="hidden" name="cid" value="<?php echo $cid; ?>" /> <input
								type="hidden" name="coursename" value="<?php echo $cname; ?>" />
							<input type="checkbox" name="SelectAll" onClick="toggle(this)" />&nbsp;&nbsp;Select
							All

							<button type="submit" class="btn btn-primary pull-right"
								onclick=" ">&nbsp;&nbsp; Save &nbsp;&nbsp;</button>
							<hr />
                                <?php
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
																																$posts_selected = array ();
																																$query = "Select deptid,post from courserpost where cid = '" . $cid . "'";
																																$stmt = mysqli_prepare ( $db, $query );
																																mysqli_stmt_execute ( $stmt );
																																mysqli_stmt_store_result ( $stmt );
																																mysqli_stmt_bind_result ( $stmt, $di, $p );
																																while ( mysqli_stmt_fetch ( $stmt ) ) {
																																	array_push ( $posts_selected, $di . ";:;" . $p );
																																}
																																mysqli_stmt_close ( $stmt );
																																for($d = 0; $d < sizeof ( $deptids ); $d ++) {
																																	?>
                                    <div class="form-group">
								<h4><?php echo $deptnames[$d]; ?></h4>
                                        <?php
																																	$query = "Select post from posts where deptid=$deptids[$d]";
																																	$stmt = mysqli_prepare ( $db, $query );
																																	mysqli_stmt_execute ( $stmt );
																																	mysqli_stmt_store_result ( $stmt );
																																	mysqli_stmt_bind_result ( $stmt, $p );
																																	$num = mysqli_stmt_num_rows ( $stmt );
																																	if ($num == 0) {
																																		echo "<label><i>No Designations added</i><label>";
																																	} else {
																																		while ( mysqli_stmt_fetch ( $stmt ) ) {
																																			$dp = $deptids [$d] . ";:;" . $p;
																																			?>

                                                <label><input
									type="checkbox" name="posts[]" value="<?php echo $dp; ?>"
									<?php
																																			if (in_array ( $dp, $posts_selected )) {
																																				echo "checked=''";
																																			}
																																			?> />&nbsp;&nbsp;<?php echo $p; ?></label><br />  

                                                <?php
																																		}
																																	}
																																	?>

                                    </div>
                                    <?php
																																	mysqli_stmt_close ( $stmt );
																																}
																																?>

                            </form>
					</div>

				</div>

			</div>
			<div class="col-xs-9">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4>User wise</h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label for="userpost" class="col-sm-2 control-label">User Post</label>
							<div class="col-sm-10">
								<select class="form-control" name="userpost" id="userpost"
									onchange="changeList(this.value)">
									<option>Select the Department-Post</option>
                                        <?php
																																								$mySelection = $_GET ['dept'];
																																								/*
																																								 * if(isset($mySelection)){ $fpos = strpos($mySelection,";"); $lpos = strrpos($mySelection,";"); echo $fpos." ----------- ".$lpos; }
																																								 */
																																								for($n = 0; $n < sizeof ( $posts_value ); $n ++) {
																																									if ($mySelection == $posts_value [$n]) {
																																										?>
                                                <option
										value='<?php echo $posts_value[$n]; ?>' selected='selected'><?php echo $posts_text[$n]; ?></option>
                                                    <?php
																																									} else {
																																										?>
                                                <option
										value='<?php echo $posts_value[$n]; ?>'><?php echo $posts_text[$n]; ?></option>
                                                    <?php
																																									}
																																								}
																																								?>
                                    </select>

							</div>
						</div>
						<br /> <br />
						<div id="list">
							<table class="table table-hover table-bordered">
								<tr>
									<th>Employee ID</th>
									<th>Name</th>
									<th>Surname</th>
									<th>Email</th>
									<th>Designation</th>
									<th>Assign</th>
									<th>Assigned On (dd-mm-YYYY)</th>
								</tr>
                                    <?php
																																				if ($mySelection != "Select the Department-Post") {
																																					$fpos = strpos ( $mySelection, ";" );
																																					$lpos = strrpos ( $mySelection, ";" );
																																					$deptidd = substr ( $mySelection, 0, $fpos );
																																					$postt = substr ( $mySelection, ($lpos + 1) );
																																					$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
																																					$query = "Select emp_id, uid, fname, lname, email, post from users where deptid = '" . $deptidd . "' and post = '" . $postt . "' and active = 1 order by emp_id";
																																					$stmt = mysqli_prepare ( $db, $query );
																																					if (! $stmt) {
																																						die ( 'mysqli error: ' . mysqli_error ( $db ) );
																																					}
																																					mysqli_stmt_execute ( $stmt );
																																					mysqli_stmt_store_result ( $stmt );
																																					mysqli_stmt_bind_result ( $stmt, $eid, $uid, $fname, $lname, $email, $post );
																																					
																																					while ( mysqli_stmt_fetch ( $stmt ) ) {
																																						$query_ua = "Select uid, dateassign from user_assign where uid = '" . $uid . "' and cid = '" . $cid . "'";
																																						$stmt_ua = mysqli_prepare ( $db, $query_ua );
																																						if (! $stmt_ua) {
																																							die ( 'mysqli error: ' . mysqli_error ( $db ) );
																																						}
																																						mysqli_stmt_execute ( $stmt_ua );
																																						mysqli_stmt_store_result ( $stmt_ua );
																																						mysqli_stmt_bind_result ( $stmt_ua, $uid_ua, $dateassign_ua );
																																						while ( mysqli_stmt_fetch ( $stmt_ua ) ) {
																																						}
																																						?>
                                            <tr>
									<td><?php echo $eid; ?></td>
									<td><?php echo $fname; ?></td>
									<td><?php echo $lname; ?></td>
									<td><?php echo $email; ?></td>
									<td><?php echo $post; ?></td>
									<td><a class="btn btn-sm btn-success center-block"
										href="assignuser.php?uid=<?php echo $uid; ?>&cid=<?php echo $cid; ?>&dept=<?php echo $dept; ?>"><?php
																																						if ($uid == $uid_ua)
																																							echo "Reassign Course<td>".date("d-m-Y", strtotime($dateassign_ua))."</td>";
																																						else
																																							echo "Assign Course<td></td>";
																																						?></a></td>
									
								</tr>
                                            <?php
																																					}
																																				}
																																				?>
                                </table>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

</body>
</html>