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
if (!isset($_GET['aid']) && !isset($_GET['deactivate'])) {
    ?>
    <script language="javascript">window.location = "allusers.php"</script>
    <?php
    exit();
}
$aid = $_GET['aid'];
$deactivate = $_GET['deactivate'];
$db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");
if ($deactivate == 1) {
    $query = "UPDATE admin set active=0 where aid=$aid";
} elseif ($deactivate == 0) {
    $query = "UPDATE admin set active=1 where aid=$aid";
}
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_execute($stmt);
?>
<script language="javascript">window.location = "allusers.php"</script>
