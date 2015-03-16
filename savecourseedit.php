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
if (!isset($_POST['cid'])) {
    ?>
    <script language="javascript">window.location = "allcourses.php"</script>
<?php
    exit();
} else {
    $cid = $_POST['cid'];
}
if (!isset($_POST['coursename'])) {
    echo '<script language="javascript">window.location="editcourse.php?cid=' . $cid . '
"</script>';
    exit();
}
$db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");
$cname = mysqli_real_escape_string($db, $_POST['coursename']);
$description = mysqli_real_escape_string($db, $_POST['coursedescription']);
$validity = mysqli_real_escape_string($db, $_POST['validity']);
$db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");
if (!isset($_POST['coursemandatory'])) {
    $query = "UPDATE courses SET cname='$cname',description='$description',mandatory=0,validity_duration='$validity' WHERE cid=$cid";
} else {
    $query = "UPDATE courses SET cname='$cname',description='$description',mandatory=1,validity_duration='$validity' WHERE cid=$cid";
}

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if (isset($_POST['posts'])) {
    $posts = $_POST['posts'];
    $query = "DELETE from courserpost where cid=$cid";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_execute($stmt);
    for ($n = 0; $n < sizeof($posts); $n++) {
        list($deptid, $post) = explode(";:;", $posts[$n]);
        $query = "INSERT into courserpost (cid,deptid,post) values ($cid,$deptid,'$post')";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_execute($stmt);
    }
}

$query = "DELETE from videos where cid=$cid";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_execute($stmt);
$vnum = 0;
for ($n = 1; isset($_POST['videoname' . $n]); $n++) {
    if (!($_POST['videoname' . $n]) == "" && !($_POST['youtubeid' . $n]) == "") {
        $vname = $_POST['videoname' . $n];
        $vdescription = $_POST['videodescription' . $n];
        $vyoutubeid = $_POST['youtubeid' . $n];
        $vnum++;
        $query = "INSERT into videos (cid,vnum,title,description,youtubeid) values ($cid,$vnum,'$vname', '$vdescription', '$vyoutubeid')";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_execute($stmt);
    }
}
?>
<script language="javascript">window.location = "allcourses.php"</script>
