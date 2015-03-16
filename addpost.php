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
if (isset($_GET['post']) && isset($_GET['deptid'])) {

    $post = $_GET['post'];
    if($post==""){
?>
       <script language="javascript">window.location="allusers.php"</script>
<?php        exit();
    }
    $deptid = $_GET['deptid'];

    $db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");
	
	$query = "select post from posts where deptid = $deptid AND post = '$post'";
	$stmt = mysqli_prepare ( $db, $query );
	if (! $stmt) {
		die ( 'mysqli error: ' . mysqli_error ( $db ) );
	}
	mysqli_stmt_execute ( $stmt );
	mysqli_stmt_store_result ( $stmt );
	mysqli_stmt_bind_result ( $stmt, $check);
	mysqli_stmt_fetch ( $stmt );
	
	if($check==$post)
	{?>
		<script> alert("The post <?php echo $post;?> already exists !");</script>
	<?php	
	}
	else
    {
		$query = "INSERT into posts (deptid, post) values ($deptid,'$post')";
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
