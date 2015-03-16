<?php
session_start ();
require 'connvars.php';
?><?php

date_default_timezone_set ( "Asia/Kolkata" );
if (! isset ( $_SESSION ['uid'] )) {
	?>
<script language="javascript">window.location="userlogin.php"</script>
<?php
} else {
	$uid = $_SESSION ['uid'];
}
if (! isset ( $_GET ['cid'] )) {
	?>
<script language="javascript">window.location = "allcourses.php"</script>
<?php
	exit ();
}
$cid = $_GET ['cid'];
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$query = "Select cname from courses where cid = '" . $cid . "' ";
$stmt = mysqli_prepare ( $db, $query );
if (! $stmt) {
	die ( 'mysqli error: ' . mysqli_error ( $db ) );
}
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $cname );
mysqli_stmt_fetch ( $stmt );
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

<title>USER TEST</title>

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
		<h3>Test: <?php echo $cname; ?> <b>(Do not refresh the page. Your
				answers will be lost)</b>
		</h3>
		<br />
	</div>
	<div class="container">
		<form id="form1" role="form" method="POST" action="saveusertest.php">
                <?php
																function line_br_json($text) {
																	$text = str_replace ( "##!!**!!##", "\\r\\n", $text );
																	return $text;
																}
																
																$query = "Select ctestid, time_hr, time_min, testjson from tests where ctestid = '" . $cid . "' ";
																$stmt = mysqli_prepare ( $db, $query );
																if (! $stmt) {
																	die ( 'mysqli error: ' . mysqli_error ( $db ) );
																}
																mysqli_stmt_execute ( $stmt );
																mysqli_stmt_store_result ( $stmt );
																mysqli_stmt_bind_result ( $stmt, $ctestid, $time_hr, $time_min, $testjson );
																mysqli_stmt_fetch ( $stmt );
																$jsondec = json_decode ( line_br_json ( $testjson ), true );
																$questind = 0;
																?>
                <input type="hidden" name="cid"
				value="<?php echo $cid; ?>" /> <input type="hidden" name="numques"
				id="numques" value="<?php echo sizeof($jsondec ["question"]); ?>" />
			<div class="container">
				<div class="row">
					<div class="col-xs-9">
						<div class="row">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">
										<strong>TEST</strong>
									</h3>
								</div>
								<div class="panel-body">
									<div id="tablesdiv">
                                            <?php
																																												for($quest = 0; $quest < sizeof ( $jsondec ["question"] ); $quest ++) {
																																													if (isset ( $jsondec ["question"] [$questind] )) {
																																														?>
                                                    <div>
                                                        <?php
																																														if ($jsondec ["question"] [$questind] ["type"] == "Subjective Question") {
																																															?>
                                                            <div
												id='subForm<?php echo $questind; ?>'>
												<br>
												<div class='form-group'>
													<label for='subquest<?php echo $questind; ?>'
														id='sublabel<?php echo $questind; ?>'
														class='col-sm-2 control-label'>Sujective Q. <?php echo ($quest + 1); ?> </label>
													<div class='col-sm-10' style="width: 800px;">

														<p style="word-wrap: break-word; display: block;"><?php echo nl2br($jsondec["question"][$questind]["text"]); ?></p>
														<textarea name='subans<?php echo $questind; ?>'
															id='subans<?php echo $questind; ?>' class='form-control'
															rows="3"></textarea>
														<br> <br>
													</div>
												</div>
											</div>
                                                            <?php
																																														} else {
																																															?>
                                                            <div
												id='objForm<?php echo $questind; ?>'>
												<br>
												<div class='form-group'>
													<div class='form-group'>
														<label for='objquest<?php echo $questind; ?>'
															id='objlabel<?php echo $questind; ?>'
															class='col-sm-2 control-label'>Objective Q. <?php echo ($quest + 1); ?> </label>
														<div class='col-sm-10' style="width: 800px;">

															<p style="word-wrap: break-word; display: block;"><?php echo nl2br($jsondec["question"][$questind]["text"]); ?></p>
														</div>
													</div>
													<div class='form-group'>
														<label for='labelo<?php echo $questind; ?>'
															class='col-sm-2 control-label'>Options</label>
														<div class='col-sm-10'>
															<div name='labelo<?php echo $questind; ?>'
																id='labelo<?php echo $questind; ?>'>
																<div id='optionsdiv<?php echo $questind; ?>'
																	name='optionsdiv<?php echo $questind; ?>'>
                                                                                    <?php
																																															if (isset ( $jsondec ["question"] [$questind] ["options"] )) {
																																																for($opt = 0; $opt < sizeof ( $jsondec ["question"] [$questind] ["options"] ); $opt ++) {
																																																	?>
                                                                                            <div
																		id='divopt<?php echo $questind . "" . $opt; ?>'>
																		<input type='hidden'
																			name='answer<?php echo $questind; ?>[]'
																			id='answer<?php echo $questind; ?>[]' value='0' /><input
																			type='checkbox' id='answer<?php echo $questind; ?>[]'
																			name='answer<?php echo $questind; ?>[]' value='1'>&nbsp;&nbsp;<?php echo $jsondec["question"][$questind]["options"][$opt] ?><input
																			type='hidden' class='form-control'
																			name='objective<?php echo $questind . ""; ?>[]'
																			id='objective<?php echo $questind . ""; ?>[]'
																			value='<?php echo $jsondec["question"][$questind]["options"][$opt] ?>'><br>
																	</div>		
                                                                                            <?php
																																																}
																																															}
																																															?>
                                                                                </div>
																<br>
															</div>
														</div>
													</div>
												</div>
											</div>
                                                            <?php
																																														}
																																														?>
                                                    </div>
                                                    <?php
																																														$questind ++;
																																													} else {
																																														$quest --;
																																														$questind ++;
																																													}
																																												}
																																												mysqli_stmt_close ( $stmt );
																																												?>	
                                        </div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div data-spy="affix">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">
										<strong>Time Left</strong>
									</h3>
								</div>
								<div class="panel-body">
									<div class="center-block">
										<h4 id="timerdisplay"></h4>
										<input type='hidden' name='hrs' id='hrs' value='0'> <input
											type='hidden' name='mins' id='mins' value='0'> <input
											type='hidden' name='secs' id='secs' value='0'>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<button type="submit"
						onClick='return confirm("Are you sure you want to submit the test?\n\nNo changes can be made after submission.")'
						class="btn btn-success">&nbsp;&nbsp;Submit&nbsp;&nbsp;</button>
				</div>
			</div>
		</form>
	</div>

	<!-- /container -->
	<!-- Bootstrap core JavaScript
        ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/jquery-2.0.3.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script>
    var hours = <?php echo $time_hr; ?>; //Set hours here
    var minutes = <?php echo $time_min; ?>;; // Set minutes here

    //DO not modify anything below
    var mins = hours * 60 + minutes;
    var secs = mins * 60;
    var currentSeconds = 0;
    var currentMinutes = 0;
    window.onload = function() {
        setTimeout('Decrement()', 1000);
    }

    function Decrement() {
        currentMinutes = Math.floor(secs / 60);
        currentSeconds = secs % 60;
        if (currentSeconds <= 9)
            currentSeconds = "0" + currentSeconds;
        secs--;
        temphours = currentMinutes / 60;
        temphours = parseInt("" + temphours);
        tempmins = currentMinutes % 60;
        document.getElementById("timerdisplay").innerHTML = temphours + " Hrs, " + tempmins + " Min, " + currentSeconds + " Sec"; //Set the element id you need the time put into.
		document.getElementById("hrs").value = temphours;
		document.getElementById("mins").value = tempmins;
		document.getElementById("secs").value = currentSeconds;
        if (secs !== -1) {
            setTimeout('Decrement()', 1000);
        } else {
            alert("Time Up!! Your responses have been saved.");
        	document.getElementById('form1').submit();
        }
    }
	
	
        </script>
</body>

</html>
