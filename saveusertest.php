<?php
session_start ();
require 'connvars.php';
?>
<?php

date_default_timezone_set ( "Asia/Kolkata" );
if ((! isset ( $_POST ['cid'] )) && (! isset ( $_SESSION ['uid'] ))) {
	?>
<script language="javascript">window.location = "allcourses.php"</script>
<?php
	exit ();
} else {
	$cid = $_POST ['cid'];
	$uid = $_SESSION ['uid'];
}
function line_br_json($text) {
	$text = str_replace ( "\\r\\n", "##!!**!!##", $text );
	$text = str_replace ( "\\t", "  ", $text );
	return $text;
}
function line_br_json2($text) {
	$text = str_replace ( "##!!**!!##", "<br />", $text );
	return $text;
}
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$query = "Select time_hr, time_min from tests where ctestid = " . $cid;
$stmt = mysqli_prepare ( $db, $query );
if (! $stmt) {
	die ( 'mysqli error: ' . mysqli_error ( $db ) );
}
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $hrs, $mins );
mysqli_stmt_fetch ( $stmt );

$seconds = (($hrs * 60) + $mins) * 60;

$hrs_remain = $_POST ['hrs'];
$mins_remain = $_POST ['mins'];
$secs_remain = ((($hrs_remain * 60) + $mins_remain) * 60) + $_POST ['secs'];

$secs_used = $seconds - $secs_remain;
$mins_used = floor ( $secs_used / 60 );
$secs_used = $secs_used % 60;
$hrs_used = ( int ) ($mins_used / 60);
$mins_used = $mins_used % 60;
date_default_timezone_set ( "Asia/Kolkata" );
$t = date ( 'd-M-Y h:i:s A' );

$test = array ();
$test ["answers"] = array ();
$vnum = 0;
while ( isset ( $_POST ['objective' . $vnum] ) || isset ( $_POST ['subans' . $vnum] ) ) {
	if (isset ( $_POST ['objective' . $vnum] )) {
		
		$test ["answers"] [$vnum] ["ans"] = array ();
		$ansopt = 0;
		$optind = 0;
		
		if (isset ( $_POST ['answer' . $vnum] )) {
			for($i = 0; $i < count ( $_POST ['answer' . $vnum] ); $i ++) {
				
				if ($_POST ['answer' . $vnum] [$i] == "0" && isset ( $_POST ['answer' . $vnum] [($i + 1)] ) && $_POST ['answer' . $vnum] [($i + 1)] == "1") {
					$test ["answers"] [$vnum] ["ans"] [$ansopt] = $_POST ['objective' . $vnum] [$optind] . "";
					$ansopt ++;
					$optind ++;
				} elseif ($_POST ['answer' . $vnum] [$i] == "0" && isset ( $_POST ['answer' . $vnum] [($i + 1)] ) && $_POST ['answer' . $vnum] [($i + 1)] == "0") {
					$optind ++;
				}
			}
		}
	} elseif (isset ( $_POST ['subans' . $vnum] )) {
		
		$test ["answers"] [$vnum] ["answer"] = $_POST ['subans' . $vnum] . "";
	}
	$vnum ++;
}
$str =  json_encode ( $test ) ;

$ans_array = json_decode ( $str, true );

$str = line_br_json(json_encode($ans_array));

$query = "Select ctestid, testjson from tests where ctestid = '" . $cid . "' ";
$stmt = mysqli_prepare ( $db, $query );
if (! $stmt) {
	die ( 'mysqli error: ' . mysqli_error ( $db ) );
}
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $ctestid, $testjson );
mysqli_stmt_fetch ( $stmt );
$ques_array = json_decode ( $testjson, true );
// print_r($ques_array);

$query = "Select fname, lname, managerfname, managerlname, manageremail, hrmanagerfname, hrmanagerlname, hrmanageremail from users where uid = " . $uid;
$stmt = mysqli_prepare ( $db, $query );
if (! $stmt) {
	die ( 'mysqli error: ' . mysqli_error ( $db ) );
}
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $fname, $lname, $managerfname, $managerlname, $manageremail, $hrmanagerfname, $hrmanagerlname, $hrmanageremail );
mysqli_stmt_fetch ( $stmt );

$query = "Select cname from courses where cid = " . $cid;
$stmt = mysqli_prepare ( $db, $query );
if (! $stmt) {
	die ( 'mysqli error: ' . mysqli_error ( $db ) );
}
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $cname );
mysqli_stmt_fetch ( $stmt );
function is_in_array($elem, $arr) {
	for($m = 0; $m < sizeof ( $arr ); $m ++) {
		if ($elem == $arr [$m])
			return true;
	}
	return false;
}

$tot_score = 0;
$total_out_of = 0;

$msg = "<html><body><font size='+1'>Employee Name: " . $fname . " " . $lname . "<br /><br />Course: " . $cname . "</font><br><br><br><table style='table-layout: fixed; width: 95%' >";
$questions = "";
$answers = "";
for($i = 0; $i < sizeof ( $ques_array ['question'] ); $i ++) {
	$ch = "A";
	$answers = "<tr><td>";
	$questions = "<tr><td><i>Question " . ($i + 1) . ".</i></td><tr></tr><tr><td style='word-wrap:break-word;display:block;'>" . line_br_json2 ( $ques_array ['question'] [$i] ['text'] ). "<br>";
	$ques_score = 0;
	$ques_out_of = 0;
	if ($ques_array ['question'] [$i] ['type'] == "Objective Question") {
		$ques_out_of = sizeof ( $ques_array ['question'] [$i] ['answer'] );
		for($j = 0; $j < sizeof ( $ques_array ['question'] [$i] ['options'] ); $j ++) {
			if (is_in_array ( $ques_array ['question'] [$i] ['options'] [$j], $ans_array ['answers'] [$i] ['ans'] ) && is_in_array ( $ques_array ['question'] [$i] ['options'] [$j], $ques_array ['question'] [$i] ['answer'] )) {
				$answers = $answers . $ch . ". <strong>" . $ques_array ['question'] [$i] ['options'] [$j] . "</strong><br>";
				$ques_score ++;
			} else if (is_in_array ( $ques_array ['question'] [$i] ['options'] [$j], $ans_array ['answers'] [$i] ['ans'] ) && ! is_in_array ( $ques_array ['question'] [$i] ['options'] [$j], $ques_array ['question'] [$i] ['answer'] )) {
				$answers = $answers . $ch . ". <strong>" . $ques_array ['question'] [$i] ['options'] [$j] . "</strong><br>";
				$ques_score -= 0.5;
			} else {
				$answers = $answers . $ch . ". " . $ques_array ['question'] [$i] ['options'] [$j] . "<br>";
			}
			$ch ++;
		}
		
		$questions = $questions . " </td><td>[score: <strong>" . $ques_score . " out of " . $ques_out_of . "</strong>]</td>";
	} else {
		$answers = "Ans: <strong>" . nl2br ( $ans_array ['answers'] [$i] ['answer'] ) . "</strong>";
	}
	$tot_score = $tot_score + $ques_score;
	$total_out_of = $total_out_of + $ques_out_of;
	
	$msg = $msg . $questions . $answers . "</td></tr><tr><td height='20' ></td></tr>";
}

$msg = $msg . "</table><p><font size='+1'>Total Score = <strong>" . $tot_score . " out of " . $total_out_of . "</strong></font></p></body></html>";

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .= "From: <noreply@acjs.co>\r\n";
$subject = $cname . " " . ": Test submitted by " . $fname . " " . $lname;
$mananger_msg = "<br><br>Hello " . $managerfname . " " . $managerlname . ", <br /><br />These are the responses submitted by " . $fname . " " . $lname . "<br>NOTE: The options selected by the employee are in bold.<br/><br/><br /><h3>Test Responses---------------------------------------</h3>" . $msg;
mail ( $manageremail, $subject, stripslashes ( $mananger_msg ), $headers );
$hrmananger_msg = "<br><br>Hello " . $hrmanagerfname . " " . $hrmanagerlname . ", <br /><br/>These are the responses submitted by " . $fname . " " . $lname . "<br>NOTE: The options selected by the employee are in bold.<br/><br/><h3>Test Responses---------------------------------------</h3>" . $msg;
mail ( $hrmanageremail, $subject, stripslashes ( $hrmananger_msg ), $headers );
$query = "DELETE from attempts where uid = $uid and cid = $cid";
$stmt = mysqli_prepare ( $db, $query );

if (! $stmt) {
	die ( 'mysqli error: ' . mysqli_error ( $db ) );
}
mysqli_stmt_execute ( $stmt );
$query = "INSERT into attempts (uid,cid,timestamp_of_test,time_taken_hr, time_taken_min, time_taken_sec, ans_json, score) values ($uid, $cid, '$t', $hrs_used, $mins_used, $secs_used, '$str', '$tot_score')";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

<title>Save Test: <?php echo $cname; ?></title>

<!-- Bootstrap core CSS -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/navbar.css" rel="stylesheet">

<!-- Just for debugging purposes. Don't actually copy this line! -->
<!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
                  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
                <![endif]-->
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-9">
				<div class="row">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">
								<strong>Your answers Have been saved</strong>
							</h3>
						</div>
						<div class="panel-body">
							<div class="pull-right">
								<a class="btn btn-success" href='userdashboard.php'>Dashboard</a>&nbsp;&nbsp;
								<button type="button" class="btn btn-primary"
									onclick="window.location = 'userlogout.php'">Log Out</button>
							</div>
						</div>
					</div>
					<!-- Bootstrap core JavaScript
        ================================================== -->
					<!-- Placed at the end of the document so the pages load faster -->
					<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
					<script src="js/jquery-2.0.3.min.js"></script>
					<!-- Include all compiled plugins (below), or include individual files as needed -->
					<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>