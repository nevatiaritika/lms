<?php
session_start();
require 'connvars.php';
?>
<?php
if (!(isset($_SESSION['aid']) && $_SESSION['aid'] != '')) {

header ("Location: adminlogin.php");

}
//Check for admin here.
//If not take to login page
?>
<?php
date_default_timezone_set("Asia/Kolkata");
if (!isset($_POST['aid'])) {
?>
    <script language="javascript">window.location="allusers.php"</script>
<?php   exit();
}
$aid = $_POST['aid'];
$fname = $_POST['userfname'];
$lname = $_POST['userlname'];
$email = $_POST['useremail'];
//Add the above 6 to database

$db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");
$query = "UPDATE admin set fname='$fname',lname='$lname',email='$email' where aid=$aid";
//echo $query;
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
//header("Location: allusers.php");
?>
<script language="javascript">window.location="allusers.php"</script>
