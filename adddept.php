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
if (isset($_GET['dept'])) {

    $dept = $_GET['dept'];
    if($dept==""){
?>
       <script language="javascript">window.location="allusers.php"</script>
<?php        exit();
    }

    $db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");
	
	$query = "select deptname from departments where deptname = '$dept'";
	$stmt = mysqli_prepare ( $db, $query );
	if (! $stmt) {
		die ( 'mysqli error: ' . mysqli_error ( $db ) );
	}
	mysqli_stmt_execute ( $stmt );
	mysqli_stmt_store_result ( $stmt );
	mysqli_stmt_bind_result ( $stmt, $check);
	mysqli_stmt_fetch ( $stmt );
	
	if($check==$dept)
	{?>
		<script> alert("The department <?php echo $dept;?> already exists !");</script>
	<?php	
	}
	else
    {
		$query = "INSERT into departments(deptname) values ('$dept')";
		$stmt = mysqli_prepare($db, $query);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
?>
    <script language="javascript">window.location="allusers.php"</script>
<?php
    exit();
}else{
?>
    <script language="javascript">window.location="allusers.php"</script>
<?php
}
?>
