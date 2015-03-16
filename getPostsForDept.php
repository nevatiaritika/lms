<?php

session_start();
require 'connvars.php';
?>
<?php
date_default_timezone_set("Asia/Kolkata");
//Check for admin here.
//If not take to login page
if (!(isset($_SESSION['aid']) && $_SESSION['aid'] != '')) {

    header("Location: adminlogin.php");
}
?>
<?php

if (!isset($_GET['department'])) {
    echo "";
}
$dept = $_GET['department'];

$db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");
$query = "Select deptid from departments where deptname='$dept'";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $did);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$arr = Array();

$db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");
$query = "Select post from posts where deptid=$did";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $post);
while (mysqli_stmt_fetch($stmt)) {
    $arr[] = $post;
}
mysqli_stmt_close($stmt);

echo json_encode($arr);
?>