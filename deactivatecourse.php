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
if (!isset($_GET['cid']) || !isset($_GET['deactivate'])) {
    ?>
    <script language="javascript">window.location = "allcourses.php"</script>
    <?php
    exit();
}
$cid = $_GET['cid'];
$deactivate = $_GET['deactivate'];

$db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");
if ($deactivate == 1) {
    $query = "update courses set active=0 where cid=$cid";
} elseif (deactivate == 0) {
    $query = "update courses set active=1 where cid=$cid";
}
//echo $query;
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>
<script language="javascript">window.location = "allcourses.php"</script>
