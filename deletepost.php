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
date_default_timezone_set("Asia/Kolkata");
if (! isset ( $_GET ['post'] )) {
	?>
<script language="javascript">window.location = "allusers.php"</script>
<?php
	exit ();
}
$post = $_GET ['post'];
$deptid = $_GET ['deptid'];
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$querytocheck = "Select uid from users where deptid = '" . $deptid . "' and post = '" . $post . "'";
$stmttocheck = mysqli_prepare ( $db, $querytocheck );
mysqli_stmt_execute ( $stmttocheck );
mysqli_stmt_store_result ( $stmttocheck );
mysqli_stmt_bind_result ( $stmttocheck, $uid );
while ( mysqli_stmt_fetch ( $stmttocheck ) ) {
	$flag = 1;
	?>
<script language="javascript">
			alert("This Designation cannot be deleted. System contains users belonging to the Designation !");
			window.location = "allusers.php"
		</script>
<?php
}

if ($flag == 0) {
	$query = "delete from posts where post='$post'";
	$stmt = mysqli_prepare ( $db, $query );
	mysqli_stmt_execute ( $stmt );
}
?>
<script language="javascript">window.location = "allusers.php"</script>
