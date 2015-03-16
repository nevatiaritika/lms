<?php
session_start ();
require 'connvars.php';
?>
<?php
// Check for admin here.
// If not take to login page
if (! (isset ( $_SESSION ['aid'] ) && $_SESSION ['aid'] != '')) {
	
	header ( "Location: adminlogin.php" );
}
?>
<?php

date_default_timezone_set ( "Asia/Kolkata" );
function line_br_json($text) {
	$text = str_replace ( "\\r\\n", "##!!**!!##", $text );
	$text = str_replace ( "\\t", "  ", $text );
	return $text;
}

if (! isset ( $_POST ['cid'] )) {
	?>
<script language="javascript">window.location = "allcourses.php"</script>
<?php
	exit ();
} else {
	$cid = $_POST ['cid'];
}

$test = array ();
$test ["question"] = array ();
$time_hr = $_POST ['hours'];
$time_min = $_POST ['minutes'];
$vnum = 0;
$tot_score = 0;
/*
 * Array ( [cid] => 20 [objective0] => this is an objective question [answer0] => Array ( [0] => 0 [1] => 1 [2] => 0 [3] => 0 [4] => 1 [5] => 0 [6] => 0 ) [objective00] => obj1 [objective01] => obj2 [objective02] => obj3 [objective03] => obj4 [objective04] => obj5 [subquest1] => this is a subjective question [subans1] => This is the answer for subjective test......!!! $test["question"]=array(); $test["question"][0]["text"]="this is a objective question"; $test["question"][0]["type"]="Objective Test"; $test["question"][0]["marks"]=100; $test["question"][0]["options"]=array(); $test["question"][0]["options"][0]="obj1"; $test["question"][0]["options"][1]="obj2"; $test["question"][0]["options"][2]="obj3"; $test["question"][0]["options"][3]="obj4"; $test["question"][0]["options"][4]="obj5"; $test["question"][0]["answer"]=array(); $test["question"][0]["answer"][0]="obj1"; $test["question"][0]["answer"][1]="obj3"; $test["question"][1]["text"]="this is a subjective question"; $test["question"][1]["type"]="Subjective Test"; $test["question"][1]["marks"]=200; $test["question"][1]["answer"]="This is the answer for subjective test......!!!"; $str = json_encode($test);
 */

while ( isset ( $_POST ['objquest' . $vnum] ) || isset ( $_POST ['subquest' . $vnum] ) ) {
	if (isset ( $_POST ['objquest' . $vnum] )) {
		$test ["question"] [$vnum] ["type"] = "Objective Question";
		$test ["question"] [$vnum] ["text"] = $_POST ['objquest' . $vnum];
		
		$test ["question"] [$vnum] ["options"] = array ();
		
		for($j = 0; isset ( $_POST ['objective' . $vnum] [$j] ); $j ++) {
			$test ["question"] [$vnum] ["options"] [$j] = $_POST ['objective' . $vnum] [$j] . "";
		}
		
		$test ["question"] [$vnum] ["answer"] = array ();
		$ansopt = 0;
		$optind = 0;
		
		if (isset ( $_POST ['answer' . $vnum] )) {
			for($i = 0; $i < count ( $_POST ['answer' . $vnum] ); $i ++) {
				
				if ($_POST ['answer' . $vnum] [$i] == "0" && isset ( $_POST ['answer' . $vnum] [($i + 1)] ) && $_POST ['answer' . $vnum] [($i + 1)] == "1") {
					$test ["question"] [$vnum] ["answer"] [$ansopt] = $_POST ['objective' . $vnum] [$optind] . "";
					$ansopt ++;
					$optind ++;
					$tot_score ++;
				} elseif ($_POST ['answer' . $vnum] [$i] == "0" && isset ( $_POST ['answer' . $vnum] [($i + 1)] ) && $_POST ['answer' . $vnum] [($i + 1)] == "0") {
					$optind ++;
				}
			}
		}
	} elseif (isset ( $_POST ['subquest' . $vnum] )) {
		
		$test ["question"] [$vnum] ["type"] = "Subjective Question";
		$test ["question"] [$vnum] ["text"] = $_POST ['subquest' . $vnum] . "";
	}
	$vnum ++;
}

$str = line_br_json ( json_encode ( $test ) );
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$query = "DELETE from tests where ctestid=$cid";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
$query = "INSERT into tests (ctestid,time_hr, time_min,testjson,score_out_of) values ($cid,$time_hr, $time_min, '$str',$tot_score)";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );

?>

<script language="javascript">window.location = "editcourse.php?cid="+<?php echo $cid; ?></script>