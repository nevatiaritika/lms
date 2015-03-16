<?php
session_start ();
require 'connvars.php';
?>
<?php
date_default_timezone_set("Asia/Kolkata");
if (! isset ( $_SESSION ['uid'] )) {
	?>
<script language="javascript">window.location = "userlogin.php"</script>
<?php
} else {
	$uid = $_SESSION ['uid'];
}
if (! isset ( $_GET ['cid'] )) {
	?>
<script language="javascript">window.location = "userDashboard.php"</script>



<?php
} else {
	$cid = $_GET ['cid'];
}
date_default_timezone_set("Asia/Kolkata");
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$query = "Select cname,description from courses where cid = '" . $cid . "'";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $cname, $coursedescription );
mysqli_stmt_fetch ( $stmt );
mysqli_stmt_close ( $stmt );

$query = "Update user_assign set new_old=0 where uid='" . $uid . "' and cid = '" . $cid . "'";
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

<title>Course Videos</title>

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
<script type="text/javascript">

window.onload = function(){
	 setTimeout(function(){
	   alert("Session Timed Out! Please Login Again");
	   window.location = "userlogout.php";
	 }, 10800000);
	};
</script>

<body>
	<input type="hidden" name="course_id" id="course_id"
		value="<?php echo $cid; ?>" />
	<div class="container">
	<div class="pull-left">
	<a class="btn btn-primary" href='userdashboard.php'>Back to Dashboard</a>
	</div>
		<div class="pull-right">
			<h4 style="display: inline;">Welcome, <?php echo $_SESSION['uname']; ?></h4>&nbsp;&nbsp;&nbsp;
			<button type="button" class="btn btn-primary"
				onclick="window.location = 'userlogout.php'">Log Out</button>
		</div>
	</div>

	<div class="container">
		<h3>Course: <?php echo $cname; ?></h3>
		<br />
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-9">
                    <?php
																				$query = "Select vnum,title,description,youtubeid from videos where cid = " . $cid . " order by vnum";
																				$stmt = mysqli_prepare ( $db, $query );
																				mysqli_stmt_execute ( $stmt );
																				mysqli_stmt_store_result ( $stmt );
																				mysqli_stmt_bind_result ( $stmt, $vnum, $title, $description, $youtubeid );
																				$counter = 0;
																				while ( mysqli_stmt_fetch ( $stmt ) ) {
																					$counter = 1;
																					?>
                        <div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo "<strong>Video " . $vnum . ":</strong>&nbsp;&nbsp;&nbsp;&nbsp;" . $title; ?></h3>
					</div>
					<div class="panel-body">
						<p>
                                    Description: <?php echo $description; ?>
                                </p>
						<br />
						<div id="videodiv<?php echo $vnum; ?>"
							class="container videocontainer">
                                    <?php
																					if (! stristr ( $youtubeid, "youtube.com" ) == false) {
																						$parts = parse_url ( $youtubeid );
																						parse_str ( $parts ['query'], $query );
																						$yid = $query ['v'];
																						?>
                                        <iframe
								id="ytplayer<?php echo $vnum; ?>" type="text/html" width="640"
								height="390"
								src="http://www.youtube.com/embed/<?php echo $yid; ?>?rel=0"
								frameborder="0"></iframe>
                                        <?php
																					}
																					?>

                                </div>
                                <?php if (!stristr($youtubeid, "youtube.com") == false) { ?>
                                    <br />
						<button class="btn btn-success"
							onclick="$('#videodiv<?php echo $vnum; ?>').slideToggle();">View
							/ Hide Video</button>
                                    <?php
																					} else {
																						?>
                                    <a class="btn btn-primary"
							href="<?php echo $youtubeid; ?>" target="blank">Go to Video</a>
                                        <?php
																					}
																					?>
                            </div>
				</div> 									
                        <?php
																				}
																				if ($counter == 0) {
																					?>
                        <div class="panel panel-default">
					<div class="panel-heading">				
                                <?php echo "<strong>No Videos Available</strong>"; ?>
                            </div>
				</div>
                    <?php
																				
}
																				?>


                </div>
			<div class="col-xs-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Test Status</h3>
					</div>
					<div class="panel-body">
						<br />

                            <?php
																												$query = "Select score_out_of from tests where ctestid = " . $cid;
																												$stmt = mysqli_prepare ( $db, $query );
																												if (! $stmt) {
																													die ( 'mysqli error: ' . mysqli_error ( $db ) );
																												}
																												mysqli_stmt_execute ( $stmt );
																												mysqli_stmt_store_result ( $stmt );
																												mysqli_stmt_bind_result ( $stmt, $score_out_of );
																												
																												$cnt = 0;
																												while ( mysqli_stmt_fetch ( $stmt ) ) {
																													$cnt ++;
																												}
																												
																												if ($cnt > 0) {
																													$query = "Select uid, cid, timestamp_of_test, time_taken_hr, time_taken_min, time_taken_sec, score from attempts where cid = " . $cid . " and uid = " . $uid;
																													$stmt = mysqli_prepare ( $db, $query );
																													if (! $stmt) {
																														die ( 'mysqli error: ' . mysqli_error ( $db ) );
																													}
																													mysqli_stmt_execute ( $stmt );
																													mysqli_stmt_store_result ( $stmt );
																													mysqli_stmt_bind_result ( $stmt, $sagaruid, $sagarcid, $timestamp_of_test, $time_taken_hr, $time_taken_min, $time_taken_sec, $score );
																													mysqli_stmt_fetch ( $stmt );
																													
																													if (($sagaruid == $uid) && ($sagarcid == $cid)) {
																														$query = "Select score_out_of from tests where ctestid = " . $cid;
																														$stmt = mysqli_prepare ( $db, $query );
																														if (! $stmt) {
																															die ( 'mysqli error: ' . mysqli_error ( $db ) );
																														}
																														mysqli_stmt_execute ( $stmt );
																														mysqli_stmt_store_result ( $stmt );
																														mysqli_stmt_bind_result ( $stmt, $score_out_of );
																														mysqli_stmt_fetch ( $stmt );
																														?>
                                    <p>
							<strong>Submitted On:</strong> <br><?php echo $timestamp_of_test; ?>
                                    </p>
						<p>
							<strong>Score:</strong> <?php echo $score . " out of " . $score_out_of; ?>
                                    </p>
                                    <?php
																													} else {
																														?>
                                    <button class="btn btn-success"
							onclick="takeTest();">Take Test</button>
                                    <?php
																													}
																												} else {
																													echo "<p>No test available</p>";
																												}
																												?>
                        </div>
				</div>
			</div>
		</div>
	</div>
	<!-- /container -->

        <?php
								$query = "Select time_hr, time_min from tests where ctestid = '" . $cid . "'";
								$stmt = mysqli_prepare ( $db, $query );
								mysqli_stmt_execute ( $stmt );
								mysqli_stmt_store_result ( $stmt );
								mysqli_stmt_bind_result ( $stmt, $time_hr, $time_min );
								mysqli_stmt_fetch ( $stmt );
								mysqli_stmt_close ( $stmt );
								
								$tm = $time_hr . " Hrs " . $time_min . " Mins";
								?>

        <!-- Bootstrap core JavaScript
        ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/jquery-2.0.3.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script>
                                $(".videocontainer").hide();
                                function takeTest() {

                                    var ans = confirm("Course: <?php echo $cname; ?>\nTest Duration:  <?php echo $tm; ?> \n\nYou can attempt the test only 1 time. \n\nClick 'OK' to Begin.");
                                    if (ans) {
                                        window.location = "usertest.php?cid=<?php echo $cid; ?>";
                                    }
                                }
        </script>
</body>

</html>
