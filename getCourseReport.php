<?php
session_start ();
require 'connvars.php';
?>
<?php

date_default_timezone_set ( "Asia/Kolkata" );
if (! (isset ( $_SESSION ['aid'] ) && $_SESSION ['aid'] != '')) {
	
	header ( "Location: adminlogin.php" );
}
// Check for admin here.
// If not take to login page
?>
<?php

if (! isset ( $_GET ['cid'] )) {
	?>

<script language="javascript">window.location = "allreports.php"</script>

<?php
	
	exit ();
}

$cid = $_GET ['cid'];

$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$query = "Select cname, validity_duration from courses where cid = '" . $cid . "'";
$stmt0 = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt0 );
mysqli_stmt_store_result ( $stmt0 );
mysqli_stmt_bind_result ( $stmt0, $cname, $val );
while ( mysqli_stmt_fetch ( $stmt0 ) ) {
	$cname1 = $cname;
}
require_once 'Classes/PHPExcel.php'; // change if necessary
                                     // Create new PHPExcel object
$objPHPExcel = new PHPExcel ();
foreach ( range ( 'A', 'L' ) as $columnID ) {
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( $columnID )->setAutoSize ( true );
}
$F = $objPHPExcel->getActiveSheet ();
$Line = 1;

// $F->setCellValue('A' . $Line, "FIRST NAME")->setCellValue('B' . $Line, "LAST NAME")->setCellValue('C' . $Line, "DEPARTMENT")->setCellValue('D' . $Line, "POST")->setCellValue('E' . $Line, "STATUS")->setCellValue('F' . $Line, "SCORE")->setCellValue('G' . $Line, "OUT OF")->setCellValue('H' . $Line, "TEST DATE")->setCellValue('I' . $Line, "TOTAL TIME (HH:MM)")->setCellValue('J' . $Line, "TIME TAKEN (HH:MM)");
// write in the sheet
// ++$Line;
// ++$Line;
$startdate = "01-01-2014";
$datetime = new DateTime ( 'tomorrow' );
$enddate = $datetime->format ( 'd-m-Y' );
$dept = "All";
$post = "All";
if (isset ( $_GET ['startdate'] )) {
	$startdate = $_GET ['startdate'];
}
if (isset ( $_GET ['enddate'] )) {
	$enddate = $_GET ['enddate'];
}

if (isset ( $_GET ['dept'] )) {
	$dept = $_GET ['dept'];
}
if (isset ( $_GET ['post'] )) {
	$post = $_GET ['post'];
}

$query = "select uid from user_assign where STR_TO_DATE(dateassign,'%Y-%m-%d')>=STR_TO_DATE('$startdate','%d-%m-%Y') AND DATE_ADD(dateassign, INTERVAL $val DAY)<=STR_TO_DATE('$enddate','%d-%m-%Y') AND cid=$cid";

if ($dept != 'All' && $post != 'All') {
	$query = $query . "AND uid IN (SELECT uid FROM users WHERE deptid=(SELECT deptid FROM departments WHERE deptname='$dept') AND post = '$post')";
}
if ($dept != 'All' && $post == 'All') {
	$query = $query . "AND uid IN (SELECT uid FROM users WHERE deptid=(SELECT deptid FROM departments WHERE deptname='$dept'))";
}

// echo $query;
// $query = "call GetCourseReports($cid)";

$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $uid );
$num = mysqli_stmt_num_rows ( $stmt );
$F->setCellValue ( "A" . $Line, "Start Date:" )->setCellValue ( "B" . $Line, "$startdate" )->setCellValue ( "C" . $Line, "End Date:" )->setCellValue ( "D" . $Line, "$enddate" );
$F->getStyle ( "A1:D1" )->getFont ()->setBold ( true );
$Line ++;
$Line ++;
$F->mergeCells ( 'A' . $Line . ":C" . $Line );
$F->setCellValue ( "A" . $Line, "Course Name: " . $cname );
$F->getStyle ( "A" . $Line )->getFont ()->setBold ( true );
$Line ++;
$Line ++;
$F->setCellValue ( "A" . $Line, "Employee ID" )->setCellValue ( "B" . $Line, "Active" )->setCellValue ( "C" . $Line, "Name" )->setCellValue ( "D" . $Line, "Surname" )->setCellValue ( "E" . $Line, "Department" )->setCellValue ( "F" . $Line, "Designation" )->setCellValue ( "G" . $Line, "Status" )->setCellValue ( "H" . $Line, "Score" )->setCellValue ( "I" . $Line, "Out of" )->setCellValue ( "J" . $Line, "Test Date" )->setCellValue ( "K" . $Line, "Total Time (HH:MM)" )->setCellValue ( "L" . $Line, "Time Taken (HH:MM)" );
$F->getStyle ( "A" . $Line . ":L" . $Line )->getFont ()->setBold ( true );
$Line ++;
while ( mysqli_stmt_fetch ( $stmt ) ) {
	
	$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
	
	$query = "SELECT users.emp_id, users.active, users.fname, users.lname, departments.deptname, users.post FROM users LEFT JOIN departments ON users.deptid=departments.deptid WHERE users.uid = $uid";
	$stmt1 = mysqli_prepare ( $db, $query );
	mysqli_stmt_execute ( $stmt1 );
	mysqli_stmt_store_result ( $stmt1 );
	mysqli_stmt_bind_result ( $stmt1, $eid, $active, $fname, $lname, $deptname, $post );
	
	while ( mysqli_stmt_fetch ( $stmt1 ) ) {
		// $F->setCellValue('A' . $Line, $fname)->setCellValue('B' . $Line, $lname)->setCellValue('C' . $Line, $deptname)->setCellValue('D' . $Line, $post);
		
		$query = "SELECT attempts.timestamp_of_test,attempts.score,attempts.time_taken_hr, attempts.time_taken_min,tests.time_hr, tests.time_min, tests.score_out_of FROM attempts LEFT JOIN tests ON attempts.cid = tests.ctestid WHERE attempts.uid=$uid AND attempts.cid=$cid";
		$stmt2 = mysqli_prepare ( $db, $query );
		mysqli_stmt_execute ( $stmt2 );
		mysqli_stmt_store_result ( $stmt2 );
		mysqli_stmt_bind_result ( $stmt2, $timestamp_of_test, $score, $time_taken_hr, $time_taken_min, $time_hr, $time_min, $score_out_of );
		$num = mysqli_stmt_num_rows ( $stmt2 );
		$F->setCellValue ( "A" . $Line, $eid );
		if ($active == 1) {
			$F->setCellValue ( "B" . $Line, "Yes" );
		} else {
			$F->setCellValue ( "B" . $Line, "No" );
		}
		$F->setCellValue ( "C" . $Line, $fname );
		$F->setCellValue ( "D" . $Line, $lname );
		$F->setCellValue ( "E" . $Line, $deptname );
		$F->setCellValue ( "F" . $Line, $post );
		
		if ($num == 0) {
			// $F->setCellValue('E' . $Line, "Incomplete")->setCellValue('F' . $Line, "")->setCellValue('G' . $Line, "")->setCellValue('H' . $Line, "")->setCellValue('I' . $Line, "")->setCellValue('J' . $Line, "");
			
			$F->mergeCells ( "G" . $Line . ":L" . $Line );
			$F->setCellValue ( "G" . $Line, "Incomplete" );
		} else {
			while ( mysqli_stmt_fetch ( $stmt2 ) ) {
				// $F->setCellValue('E' . $Line, "Complete")->setCellValue('F' . $Line, $score)->setCellValue('G' . $Line, $score_out_of)->setCellValue('H' . $Line, $timestamp_of_test)->setCellValue('I' . $Line, $time_taken_hr . ":" . $time_taken_min)->setCellValue('J' . $Line, $time_hr . ":" . $time_min);
				
				$F->setCellValue ( "G" . $Line, "Complete" );
				$F->setCellValue ( "H" . $Line, $score );
				$F->setCellValue ( "I" . $Line, $score_out_of );
				$F->setCellValue ( "J" . $Line, $timestamp_of_test );
				$F->setCellValue ( "K" . $Line, $time_taken_hr . ":" . $time_taken_min );
				$F->setCellValue ( "L" . $Line, $time_hr . ":" . $time_min );
			}
		}
		
		$Line ++;
	}
}

$filename = $cid . '_' . $cname1 . '.xls';
// Redirect output to a client’s web browser (Excel5)
header ( 'Content-Type: application/vnd.ms-excel' );
header ( 'Content-Disposition: attachment;filename="' . $filename . '"' );
header ( 'Cache-Control: max-age=0' );

$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
$objWriter->save ( 'php://output' );
exit ();
?>
