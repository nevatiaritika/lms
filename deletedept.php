<?php
session_start();
require 'connvars.php';
?>
<?php
//Check for admin here.
//If not take to login page
if (!(isset($_SESSION['aid']) && $_SESSION['aid'] != '')) {

header ("Location: adminlogin.php");

}
?>
<?php
date_default_timezone_set("Asia/Kolkata");
if (!isset($_GET['deptid'])) {
    ?>
    <script language="javascript">window.location = "allusers.php"</script>
    <?php
    exit();
}
$deptid= $_GET['deptid'];
$db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");

	$flag = 0;
	$querytocheck = "Select uid from users where deptid = '" . $deptid . "' ";
	$stmttocheck = mysqli_prepare ( $db, $querytocheck );
	if (! $stmttocheck) {
		die ( 'mysqli error: ' . mysqli_error ( $db ) );
	}
	mysqli_stmt_execute ( $stmttocheck );
	mysqli_stmt_store_result ( $stmttocheck );
	mysqli_stmt_bind_result ( $stmttocheck, $uid);
	while ( mysqli_stmt_fetch ( $stmttocheck ) ) {
		$flag = 1;
		?>
		<script language="javascript">
			alert("This Department cannot be deleted. System contains users belonging to the department !");
			window.location = "allusers.php"
		</script>
		<?php
	}
	
	$qu = "Select cid from courserpost where deptid = '" . $deptid . "' ";
	$stm = mysqli_prepare ( $db, $qu );
	if (! $stm) {
		die ( 'mysqli error: ' . mysqli_error ( $db ) );
	}
	mysqli_stmt_execute ( $stm );
	mysqli_stmt_store_result ( $stm );
	mysqli_stmt_bind_result ( $stm, $cid);
	while ( mysqli_stmt_fetch ( $stm ) ) {
		$flag = 1;
		?>
		<script language="javascript">
			alert("This Department cannot be deleted. System contains course assigned to the department !");
			window.location = "allusers.php"
		</script>
		<?php
	}
	
	
	if($flag == 0)
	{
		$query = "delete from departments where deptid='$deptid'";
		$stmt = mysqli_prepare($db, $query);
		mysqli_stmt_execute($stmt);
	}
//Add stuff to delete user table entries
?>
<script language="javascript">window.location = "allusers.php"</script>
