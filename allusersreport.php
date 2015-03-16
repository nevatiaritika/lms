<?php
session_start ();
require 'connvars.php';
?>
<?php

if (! (isset ( $_SESSION ['aid'] ) && $_SESSION ['aid'] != '')) {
	
	header ( "Location: adminlogin.php" );
}
// Check for admin here.
// If not take to login page
?>
<?php

date_default_timezone_set ( "Asia/Kolkata" );
// mysqli_set_charset($db, 'utf8'); //if not by default
$filename = 'allusersreport.xls';
$startdate = "01-01-2014";
$datetime = new DateTime ( 'tomorrow' );
$enddate = $datetime->format ( 'd-m-Y' );
if (isset ( $_GET ['startdate'] )) {
	$startdate = $_GET ['startdate'];
}
if (isset ( $_GET ['enddate'] )) {
	$enddate = $_GET ['enddate'];
}
$deptfilter = "All";
$postfilter = "All";
if (isset ( $_GET ['dept'] )) {
	$deptfilter = $_GET ['dept'];
}
if (isset ( $_GET ['post'] )) {
	$postfilter = $_GET ['post'];
}

require_once 'Classes/PHPExcel.php'; // change if necessary
                                     // Create new PHPExcel object
$objPHPExcel = new PHPExcel ();
foreach ( range ( 'A', 'G' ) as $columnID ) {
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( $columnID )->setAutoSize ( true );
}

$F = $objPHPExcel->getActiveSheet ();
$Line = 1;

$F->setCellValue ( "A" . $Line, "Start Date" )->setCellValue ( "B" . $Line, "$startdate" )->setCellValue ( "C" . $Line, "End Date" )->setCellValue ( "D" . $Line, "$enddate" );
$F->getStyle ( "A1:D1" )->getFont ()->setBold ( true );
$Line ++;
$Line ++;
$F->setCellValue ( "A" . $Line, "ACTIVE USERS" );
$Line ++;
$Line ++;
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$query = "select distinct uid from user_assign where uid IN ( SELECT uid FROM users WHERE active = 1)";
if ($deptfilter != "All") {
	$query = $query . " and uid IN (select uid from users where deptid = (select deptid from departments where deptname = '$deptfilter'))";
}
if ($postfilter != "All") {
	$query = $query . " and uid IN (select uid from users where post = '$postfilter')";
}

$query = $query. " ORDER BY emp_id";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $UID );
$uids = Array ();
while ( mysqli_stmt_fetch ( $stmt ) ) {
	$uids [] = $UID;
}
for($usercount = 0; $usercount < sizeof ( $uids ); $usercount ++) {
	$uid = $uids [$usercount];
	$uidcopy = $uid;
	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
	
	$query = "Select users.emp_id, users.fname,users.lname,departments.deptname,users.post from users inner join departments on users.deptid = departments.deptid where uid = '" . $uid . "'";
	$stmt = mysqli_prepare ( $db, $query );
	mysqli_stmt_execute ( $stmt );
	mysqli_stmt_store_result ( $stmt );
	mysqli_stmt_bind_result ( $stmt, $eid, $fname, $lname, $department, $post);
	mysqli_stmt_fetch ( $stmt );
	
	// echo $filename;
	
	$F->mergeCells ( 'A' . $Line . ":B" . $Line );
	$F->setCellValue ( "A" . $Line, "User Name: " . $fname . " " . $lname );
	$F->getStyle ( "A" . $Line )->getFont ()->setBold ( true );
	$F->mergeCells ( 'C' . $Line . ":F" . $Line );
	$F->setCellValue ( "C" . $Line, "Department: " . $department );
	$F->setCellValue ( "G" . $Line, "Post: " . $post );
	$F->setCellValue ( "I" . $Line, "Employee ID: " . $eid );
	$F->getStyle ( "A" . $Line . ":K" . $Line )->getFont ()->setBold ( true );
	$Line ++;
	$Line ++;
	
	$F->setCellValue ( "A" . $Line, "Course Name" )->setCellValue ( "B" . $Line, "Start Date" )->setCellValue ( "C" . $Line, "End Date" )->setCellValue ( "D" . $Line, "Active" )->setCellValue ( "E" . $Line, "Mandatory" )->setCellValue ( "F" . $Line, "Status" )->setCellValue ( "G" . $Line, "Test Date" )->setCellValue ( "H" . $Line, "Time Taken(HH:MM)" )->setCellValue ( "I" . $Line, "Score" )->setCellValue ( "J" . $Line, "Total Time(HH:MM)" )->setCellValue ( "K" . $Line, "Out Of" );
	$F->getStyle ( "A" . $Line . ":K" . $Line )->getFont ()->setBold ( true );
	$Line ++;
	
	if (isset ( $_GET ['startdate'] )) {
		$startdate = $_GET ['startdate'];
	}
	if (isset ( $_GET ['enddate'] )) {
		$enddate = $_GET ['enddate'];
	}
	
	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
	$query = "SELECT cid, dateassign FROM user_assign WHERE uid=$uid AND STR_TO_DATE(dateassign,'%Y-%m-%d')>=STR_TO_DATE('$startdate','%d-%m-%Y') ORDER BY STR_TO_DATE(dateassign,'%Y-%m-%d') DESC";
	
	$stmt = mysqli_prepare ( $db, $query );
	mysqli_stmt_execute ( $stmt );
	mysqli_stmt_store_result ( $stmt );
	mysqli_stmt_bind_result ( $stmt, $cid, $dateassign );
	$num = mysqli_stmt_num_rows ( $stmt );
	while ( mysqli_stmt_fetch ( $stmt ) ) {
		
		$query = "SELECT cname, active, mandatory,validity_duration FROM courses WHERE cid=$cid";
		$stmt1 = mysqli_prepare ( $db, $query );
		mysqli_stmt_execute ( $stmt1 );
		mysqli_stmt_store_result ( $stmt1 );
		mysqli_stmt_bind_result ( $stmt1, $cname, $active, $mandatory, $validity );
		
		while ( mysqli_stmt_fetch ( $stmt1 ) ) {
			$date1 = strtotime ( $dateassign . " +" . $validity . " days" );
			$date2 = strtotime ( $enddate );
			if($date1 <= $date2)			
			{
				if ($active == 1)
					$active = "Yes";
				else
					$active = "No";
				
				if ($mandatory == 1)
					$mandatory = "Yes";
				else
					$mandatory = "No";
				$finishdt = strtotime ( date ( "d-m-Y", strtotime ( $dateassign ) ) . " +" . $validity . " day" );
				$F->setCellValue ( "A" . $Line, $cname );
				$F->setCellValue ( "B" . $Line, date ( "d-m-Y", strtotime ( $dateassign )));
				$F->setCellValue ( "C" . $Line, date ( "d-m-Y", $finishdt ) );
				$F->setCellValue ( "D" . $Line, $active );
				$F->setCellValue ( "E" . $Line, $mandatory );
				
				$query = "SELECT timestamp_of_test, time_taken_hr, time_taken_min, score FROM attempts WHERE cid=$cid AND uid=$uid";
				$stmt3 = mysqli_prepare ( $db, $query );
				mysqli_stmt_execute ( $stmt3 );
				mysqli_stmt_store_result ( $stmt3 );
				mysqli_stmt_bind_result ( $stmt3, $timestamp_of_test, $time_taken_hr, $time_taken_min, $score );
				$num = mysqli_stmt_num_rows ( $stmt3 );
				if ($num == 0) {
					$F->setCellValue ( "F" . $Line, "Incomplete" );
					$F->setCellValue ( "G" . $Line, "" );
					$F->setCellValue ( "H" . $Line, "" );
					$F->setCellValue ( "I" . $Line, "" );
				} else {
					while ( mysqli_stmt_fetch ( $stmt3 ) ) {
						$F->setCellValue ( "F" . $Line, "Complete" );
						$F->setCellValue ( "G" . $Line, $timestamp_of_test );
						$F->setCellValue ( "H" . $Line, $time_taken_hr . ":" . $time_taken_min );
						$F->setCellValue ( "I" . $Line, $score );
					}
				}
				
				$query = "SELECT time_hr, time_min, score_out_of FROM tests WHERE ctestid=$cid";
				$stmt4 = mysqli_prepare ( $db, $query );
				mysqli_stmt_execute ( $stmt4 );
				mysqli_stmt_store_result ( $stmt4 );
				mysqli_stmt_bind_result ( $stmt4, $time_hr, $time_min, $score_out_of );
				$num = mysqli_stmt_num_rows ( $stmt4 );
				if ($num == 0) {
					
					$F->setCellValue ( "J" . $Line, "" );
					$F->setCellValue ( "K" . $Line, "" );
				} else {
					while ( mysqli_stmt_fetch ( $stmt4 ) ) {
						
						$F->setCellValue ( "J" . $Line, $time_hr . ":" . $time_min );
						$F->setCellValue ( "K" . $Line, $score_out_of );
					}
				}
				$Line ++;
			}
		}
	}
	
	$F->mergeCells ( 'A' . $Line . ":K" . ($Line + 2) );
	$Line ++;
	// $F->mergeCells('A' . $Line . ":K" . $Line);
	$Line ++;
	// $F->mergeCells('A' . $Line . ":K" . $Line);
	$Line ++;
}
$Line ++;
$Line ++;
$F->setCellValue ( "A" . $Line, "INACTIVE USERS" );
$Line ++;
$Line ++;


$query = "select distinct uid from user_assign where uid IN ( SELECT uid FROM users WHERE active = 0)";
if ($deptfilter != "All") {
	$query = $query . " and uid IN (select uid from users where deptid = (select deptid from departments where deptname = '$deptfilter'))";
}
if ($postfilter != "All") {
	$query = $query . " and uid IN (select uid from users where post = '$postfilter')";
}

$query = $query. " ORDER BY emp_id";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $UID );
$uids = Array ();
while ( mysqli_stmt_fetch ( $stmt ) ) {
	$uids [] = $UID;
}
for($usercount = 0; $usercount < sizeof ( $uids ); $usercount ++) {
	$uid = $uids [$usercount];
	$uidcopy = $uid;
	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );

	$query = "Select users.emp_id, users.fname,users.lname,departments.deptname,users.post from users inner join departments on users.deptid = departments.deptid where uid = '" . $uid . "'";
	$stmt = mysqli_prepare ( $db, $query );
	mysqli_stmt_execute ( $stmt );
	mysqli_stmt_store_result ( $stmt );
	mysqli_stmt_bind_result ( $stmt, $eid, $fname, $lname, $department, $post);
	mysqli_stmt_fetch ( $stmt );

	// echo $filename;

	$F->mergeCells ( 'A' . $Line . ":B" . $Line );
	$F->setCellValue ( "A" . $Line, "User Name: " . $fname . " " . $lname );
	$F->getStyle ( "A" . $Line )->getFont ()->setBold ( true );
	$F->mergeCells ( 'C' . $Line . ":F" . $Line );
	$F->setCellValue ( "C" . $Line, "Department: " . $department );
	$F->setCellValue ( "G" . $Line, "Post: " . $post );
	$F->setCellValue ( "I" . $Line, "Employee ID: " . $eid );
	$F->getStyle ( "A" . $Line . ":K" . $Line )->getFont ()->setBold ( true );
	$Line ++;
	$Line ++;

	$F->setCellValue ( "A" . $Line, "Course Name" )->setCellValue ( "B" . $Line, "Start Date" )->setCellValue ( "C" . $Line, "End Date" )->setCellValue ( "D" . $Line, "Active" )->setCellValue ( "E" . $Line, "Mandatory" )->setCellValue ( "F" . $Line, "Status" )->setCellValue ( "G" . $Line, "Test Date" )->setCellValue ( "H" . $Line, "Time Taken(HH:MM)" )->setCellValue ( "I" . $Line, "Score" )->setCellValue ( "J" . $Line, "Total Time(HH:MM)" )->setCellValue ( "K" . $Line, "Out Of" );
	$F->getStyle ( "A" . $Line . ":K" . $Line )->getFont ()->setBold ( true );
	$Line ++;

	if (isset ( $_GET ['startdate'] )) {
		$startdate = $_GET ['startdate'];
	}
	if (isset ( $_GET ['enddate'] )) {
		$enddate = $_GET ['enddate'];
	}

	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
	$query = "SELECT cid, dateassign FROM user_assign WHERE uid=$uid AND STR_TO_DATE(dateassign,'%Y-%m-%d')>=STR_TO_DATE('$startdate','%d-%m-%Y') ORDER BY STR_TO_DATE(dateassign,'%Y-%m-%d') DESC";

	$stmt = mysqli_prepare ( $db, $query );
	mysqli_stmt_execute ( $stmt );
	mysqli_stmt_store_result ( $stmt );
	mysqli_stmt_bind_result ( $stmt, $cid, $dateassign );
	$num = mysqli_stmt_num_rows ( $stmt );
	while ( mysqli_stmt_fetch ( $stmt ) ) {

		$query = "SELECT cname, active, mandatory,validity_duration FROM courses WHERE cid=$cid";
		$stmt1 = mysqli_prepare ( $db, $query );
		mysqli_stmt_execute ( $stmt1 );
		mysqli_stmt_store_result ( $stmt1 );
		mysqli_stmt_bind_result ( $stmt1, $cname, $active, $mandatory, $validity );

		while ( mysqli_stmt_fetch ( $stmt1 ) ) {
			$date1 = strtotime ( $dateassign . " +" . $validity . " days" );
			$date2 = strtotime ( $enddate );
			if($date1 <= $date2)
			{
				if ($active == 1)
					$active = "Yes";
				else
					$active = "No";

				if ($mandatory == 1)
					$mandatory = "Yes";
				else
					$mandatory = "No";
				$finishdt = strtotime ( date ( "d-m-Y", strtotime ( $dateassign ) ) . " +" . $validity . " day" );
				$F->setCellValue ( "A" . $Line, $cname );
				$F->setCellValue ( "B" . $Line, date ( "d-m-Y", strtotime ( $dateassign )));
				$F->setCellValue ( "C" . $Line, date ( "d-m-Y", $finishdt ) );
				$F->setCellValue ( "D" . $Line, $active );
				$F->setCellValue ( "E" . $Line, $mandatory );

				$query = "SELECT timestamp_of_test, time_taken_hr, time_taken_min, score FROM attempts WHERE cid=$cid AND uid=$uid";
				$stmt3 = mysqli_prepare ( $db, $query );
				mysqli_stmt_execute ( $stmt3 );
				mysqli_stmt_store_result ( $stmt3 );
				mysqli_stmt_bind_result ( $stmt3, $timestamp_of_test, $time_taken_hr, $time_taken_min, $score );
				$num = mysqli_stmt_num_rows ( $stmt3 );
				if ($num == 0) {
					$F->setCellValue ( "F" . $Line, "Incomplete" );
					$F->setCellValue ( "G" . $Line, "" );
					$F->setCellValue ( "H" . $Line, "" );
					$F->setCellValue ( "I" . $Line, "" );
				} else {
					while ( mysqli_stmt_fetch ( $stmt3 ) ) {
						$F->setCellValue ( "F" . $Line, "Complete" );
						$F->setCellValue ( "G" . $Line, $timestamp_of_test );
						$F->setCellValue ( "H" . $Line, $time_taken_hr . ":" . $time_taken_min );
						$F->setCellValue ( "I" . $Line, $score );
					}
				}

				$query = "SELECT time_hr, time_min, score_out_of FROM tests WHERE ctestid=$cid";
				$stmt4 = mysqli_prepare ( $db, $query );
				mysqli_stmt_execute ( $stmt4 );
				mysqli_stmt_store_result ( $stmt4 );
				mysqli_stmt_bind_result ( $stmt4, $time_hr, $time_min, $score_out_of );
				$num = mysqli_stmt_num_rows ( $stmt4 );
				if ($num == 0) {
						
					$F->setCellValue ( "J" . $Line, "" );
					$F->setCellValue ( "K" . $Line, "" );
				} else {
					while ( mysqli_stmt_fetch ( $stmt4 ) ) {

						$F->setCellValue ( "J" . $Line, $time_hr . ":" . $time_min );
						$F->setCellValue ( "K" . $Line, $score_out_of );
					}
				}
				$Line ++;
			}
		}
	}

	$F->mergeCells ( 'A' . $Line . ":K" . ($Line + 2) );
	$Line ++;
	// $F->mergeCells('A' . $Line . ":K" . $Line);
	$Line ++;
	// $F->mergeCells('A' . $Line . ":K" . $Line);
	$Line ++;
}

// Redirect output to a client’s web browser (Excel5)
header ( 'Content-Type: application/vnd.ms-excel' );
header ( 'Content-Disposition: attachment;filename="' . $filename . '"' );
header ( 'Cache-Control: max-age=0' );

$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
$objWriter->save ( 'php://output' );
exit ();
?>
