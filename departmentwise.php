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
if (! isset ( $_POST ['cid'] )) {
	?>
<script language="javascript">window.location = "allcourses.php"</script>
<?php
	exit ();
} else {
	$cid = $_POST ['cid'];
}
if (! isset ( $_POST ['coursename'] )) {
	echo '<script language="javascript">window.location="editcourse.php?cid=' . $cid . '
"</script>';
	exit ();
}
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
date_default_timezone_set ( "Asia/Kolkata" );
$date = date ( 'Y-m-d' ) . "";

/*
 * if coursepost is not there in post array delete the users from user_assign
 */

$query = "Select deptid, post from courserpost where cid = '" . $cid . "' ";
$stmt = mysqli_prepare ( $db, $query );
if (! $stmt) {
	die ( 'mysqli error: ' . mysqli_error ( $db ) );
}
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $deptid, $post );
if (! isset ( $_POST ['posts'] )) {
	$query = "DELETE from user_assign where cid= '" . $cid . "'";
	$stmt = mysqli_prepare ( $db, $query );
	mysqli_stmt_execute ( $stmt );
} else {
	if (isset ( $stmt )) {
		while ( mysqli_stmt_fetch ( $stmt ) ) {
			
			if (in_array ( $deptid . ";:;" . $post, $_POST ['posts'] )) {
				// do nothing
			} else {
				// delete users from user_assign by taking uid from users using deptid and post
				$query = "Select uid from users where deptid = '" . $deptid . "' and post = '" . $post . "' ";
				$stmt = mysqli_prepare ( $db, $query );
				if (! $stmt) {
					die ( 'mysqli error: ' . mysqli_error ( $db ) );
				}
				mysqli_stmt_execute ( $stmt );
				mysqli_stmt_store_result ( $stmt );
				mysqli_stmt_bind_result ( $stmt, $uid );
				while ( mysqli_stmt_fetch ( $stmt ) ) {
					$queryua = "DELETE from user_assign where uid= '" . $uid . "'";
					$stmntts = mysqli_prepare ( $db, $queryua );
					mysqli_stmt_execute ( $stmntts );
				}
			}
		}
	}
}

$query = "DELETE from courserpost where cid = '" . $cid . "'";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
$var = 0;
while ( isset ( $_POST ['posts'] [$var] ) ) {
	$fpos = strpos ( $_POST ['posts'] [$var], ";" );
	$lpos = strrpos ( $_POST ['posts'] [$var], ";" );
	$deptid = substr ( $_POST ['posts'] [$var], 0, $fpos );
	$post = substr ( $_POST ['posts'] [$var], ($lpos + 1) );
	// echo $deptid." ".$post." <br>";
	$qu = "INSERT into courserpost (cid, deptid, post) values ('" . $cid . "', '" . $deptid . "', '" . $post . "')";
	$stmnt = mysqli_prepare ( $db, $qu );
	if (! $stmnt) {
		die ( 'mysqli error: ' . mysqli_error ( $db ) );
	}
	mysqli_stmt_execute ( $stmnt );
	$var ++;
}

$var = 0;
while ( isset ( $_POST ['posts'] [$var] ) ) {
	$fpos = strpos ( $_POST ['posts'] [$var], ";" );
	$lpos = strrpos ( $_POST ['posts'] [$var], ";" );
	$deptid = substr ( $_POST ['posts'] [$var], 0, $fpos );
	$post = substr ( $_POST ['posts'] [$var], ($lpos + 1) );
	// echo $deptid." ".$post." <br>";
	
	$query = "Select uid from users where deptid = '" . $deptid . "' and post = '" . $post . "' and active = 1";
	$stmt = mysqli_prepare ( $db, $query );
	if (! $stmt) {
		die ( 'mysqli error: ' . mysqli_error ( $db ) );
	}
	mysqli_stmt_execute ( $stmt );
	mysqli_stmt_store_result ( $stmt );
	mysqli_stmt_bind_result ( $stmt, $uid );
	while ( mysqli_stmt_fetch ( $stmt ) ) {
		// to check whether the user exists or not
		$querytocheck = "Select uid from user_assign where uid = '" . $uid . "' and cid = '" . $cid . "' ";
		$stmttocheck = mysqli_prepare ( $db, $querytocheck );
		if (! $stmttocheck) {
			die ( 'mysqli error: ' . mysqli_error ( $db ) );
		}
		mysqli_stmt_execute ( $stmttocheck );
		mysqli_stmt_store_result ( $stmttocheck );
		mysqli_stmt_bind_result ( $stmttocheck, $uidtocheck );
		while ( mysqli_stmt_fetch ( $stmttocheck ) ) {
		}
		// echo $uid." ---> ".$uidtocheck;
		if (! $uid == $uidtocheck) {
			$query = "Select cname,description,validity_duration from courses where cid = '" . $cid . "'";
			$stmt1 = mysqli_prepare ( $db, $query );
			mysqli_stmt_execute ( $stmt1 );
			mysqli_stmt_store_result ( $stmt1 );
			mysqli_stmt_bind_result ( $stmt1, $cname, $coursedescription, $validity );
			mysqli_stmt_fetch ( $stmt1 );
			mysqli_stmt_close ( $stmt1 );
			
			$query = "Select emp_id, fname, lname, email from users where uid = $uid";
			$stmt2 = mysqli_prepare ( $db, $query );
			mysqli_stmt_execute ( $stmt2 );
			mysqli_stmt_store_result ( $stmt2 );
			mysqli_stmt_bind_result ( $stmt2, $eid, $fname, $lname, $email );
			mysqli_stmt_fetch ( $stmt2 );
			mysqli_stmt_close ( $stmt2 );
			
			$tday = date ( 'd-m-Y' );
			$deadline = strtotime ( date ( "d-m-Y", strtotime ( $tday ) ) . " +" . $validity . " day" );
			$deadline_dt = date ( 'd-m-Y', $deadline );
			
			$quer = "INSERT into user_assign (uid,cid, dateassign, new_old, emp_id) values ('$uid','$cid','$date', 1, $eid)";
			$stament = mysqli_prepare ( $db, $quer );
			mysqli_stmt_execute ( $stament );
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
			$headers .= "From: <noreply@acjs.co>\r\n";
			$subject = "A course has been assigned to you";
			$msg = "<html><body><br><br>Hello " . $fname . " " . $lname . ", <br /><br />A Course has been assigned to you by the system administrator. Please read the details of the course and take note of the deadline.<br /><br /><center><font size=+1 ><b>" . $cname . "</b></font></center><br /><br />Deadline: <b><u>" . $deadline_dt . "</u></b><br /><br />" . nl2br ( $coursedescription ) . "</body></html>";
			mail ( $email, $subject, stripslashes ( $msg ), $headers );
		}
	}
	$var ++;
}
?>
<script language="javascript">window.location = "user_assign.php?cid="+<?php echo $cid; ?></script>
