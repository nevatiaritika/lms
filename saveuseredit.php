<?php
session_start();
require 'connvars.php';
?>
<?php
date_default_timezone_set("Asia/Kolkata");
if (!(isset($_SESSION['aid']) && $_SESSION['aid'] != '')) {

header ("Location: adminlogin.php");

}
//Check for admin here.
//If not take to login page
?>
<?php

if (!isset($_POST['uid'])) {
?>
    <script language="javascript">window.location="allusers.php"</script>
<?php   exit();
}
$eid = $_POST['employeeid'];
$uid = $_POST['uid'];
$fname = $_POST['userfname'];
$lname = $_POST['userlname'];
$email = $_POST['useremail'];
$managerfname = $_POST['managerfname'];
$managerlname = $_POST['managerlname'];
$manageremail = $_POST['manageremail'];
$hrmanagerfname = $_POST['hrmanagerfname'];
$hrmanagerlname = $_POST['hrmanagerlname'];
$hrmanageremail = $_POST['hrmanageremail'];
//Add the above 6 to database

list($deptid,$post) = explode(";:;", $_POST['userpost']);

$db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");
$query = "UPDATE users set emp_id='$eid',fname='$fname',lname='$lname',email='$email',deptid='$deptid',post='$post',managerfname='$managerfname',managerlname='$managerlname',manageremail='$manageremail',hrmanagerfname='$hrmanagerfname',hrmanagerlname='$hrmanagerlname',hrmanageremail='$hrmanageremail' where uid=$uid";
//echo $query;
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
//header("Location: allusers.php");
?>
<script language="javascript">window.location="allusers.php"</script>
