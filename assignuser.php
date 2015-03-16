<?php
session_start ();
require 'connvars.php';
?>
<?php
if (! (isset ( $_SESSION ['aid'] ) && $_SESSION ['aid'] != '')) {
	
	header ( "Location: adminlogin.php" );
}
?>
	<?php
	date_default_timezone_set("Asia/Kolkata");
	$cid = $_GET ['cid'];
	$uid = $_GET ['uid'];
	$url_dept = $_GET ['dept'];
	$dt = date ( 'Y-m-d' );
	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
	
	$query = "Select cname,description,validity_duration from courses where cid = '" . $cid . "'";
	$stmt1 = mysqli_prepare ( $db, $query );
	mysqli_stmt_execute ( $stmt1 );
	mysqli_stmt_store_result ( $stmt1 );
	mysqli_stmt_bind_result ( $stmt1, $cname, $coursedescription, $validity );
	mysqli_stmt_fetch ( $stmt1 );
	mysqli_stmt_close ( $stmt1 );
	
	$query = "Select fname, lname, email, emp_id from users where uid = $uid";
	$stmt2 = mysqli_prepare ( $db, $query );
	mysqli_stmt_execute ( $stmt2 );
	mysqli_stmt_store_result ( $stmt2 );
	mysqli_stmt_bind_result ( $stmt2, $fname, $lname, $email, $eid );
	mysqli_stmt_fetch ( $stmt2 );
	mysqli_stmt_close ( $stmt2 );
	
	$tday = date ( "d-m-Y" );
	$deadline = strtotime ( date ( "d-m-Y", strtotime ( $tday ) ) . " +" . $validity . " day" );
	$deadline_dt = date ( 'd-m-Y', $deadline );
	$query = "SELECT * FROM user_assign WHERE uid=$uid AND cid =$cid ";
	$stmt = mysqli_prepare ( $db, $query );
	mysqli_stmt_execute ( $stmt );
	mysqli_stmt_store_result ( $stmt );
	$num = mysqli_stmt_num_rows ( $stmt );
	if ($num == 0) {
		$query = "INSERT into user_assign (uid, cid, dateassign, new_old, emp_id) VALUES ($uid, $cid, '$dt',1, $eid) ";
		$stmt = mysqli_prepare ( $db, $query );
		mysqli_stmt_execute ( $stmt );
		mysqli_stmt_close ( $stmt );
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= "From: <noreply@acjs.co>\r\n";
		$subject = "A course has been assigned to you";
		$msg = "<html><body><br><br>Hello " . $fname . " " . $lname . ", <br /><br />A Course has been assigned to you by the system administrator. Please read the details of the course and take note of the deadline.<br /><br /><center><font size=+1 ><b>" . $cname . "</b></font></center><br /><br />Deadline: <b><u>" . $deadline_dt . "</u></b><br /><br />" . nl2br ( $coursedescription ) . "</body></html>";
		mail ( $email, $subject, stripslashes ( $msg ), $headers );
	} else {
		$query = "UPDATE user_assign SET dateassign='$dt', new_old = 1 WHERE uid=$uid AND cid=$cid";
		$stmt = mysqli_prepare ( $db, $query );
		mysqli_stmt_execute ( $stmt );
		mysqli_stmt_close ( $stmt );
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= "From: <noreply@acjs.co>\r\n";
		$subject = "A course has been assigned to you";
		$msg = "<html><body><br><br>Hello " . $fname . " " . $lname . ", <br /><br />A Course has been assigned to you by the system administrator. Please read the details of the course and take note of the deadline.<br /><br /><center><font size=+1 ><b>" . $cname . "</b></font></center><br /><br />Deadline: <b><u>" . $deadline_dt. "</u></b><br /><br />" . nl2br ( $coursedescription ) . "</body></html>";
		mail ( $email, $subject, stripslashes ( $msg ), $headers );
	}
	?>

<script language="javascript">window.location = "user_assign.php?cid=<?php echo $cid; ?>&dept=<?php echo $url_dept; ?>"</script>
