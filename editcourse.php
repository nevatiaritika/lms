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
if (! isset ( $_GET ['cid'] )) {
	?>
<script language="javascript">window.location = "allcourses.php"</script>
<?php
	exit ();
}
$cid = $_GET ['cid'];
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$query = "Select cid,cname,description,mandatory,validity_duration from courses where cid = '" . $cid . "'";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $cid, $cname, $description, $mandatory, $validity );
mysqli_stmt_fetch ( $stmt );
mysqli_stmt_close ( $stmt );

$deptids = array ();
$deptnames = array ();
$query = "Select deptid,deptname from departments";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $di, $dn );
while ( mysqli_stmt_fetch ( $stmt ) ) {
	array_push ( $deptids, $di );
	array_push ( $deptnames, $dn );
}
mysqli_stmt_close ( $stmt );

$posts_selected = array ();
$query = "Select deptid,post from courserpost where cid = '" . $cid . "'";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $di, $p );
while ( mysqli_stmt_fetch ( $stmt ) ) {
	array_push ( $posts_selected, $di . ";:;" . $p );
}
mysqli_stmt_close ( $stmt );
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

<title>Edit Course: <?php echo $cname; ?></title>

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
<script>
            var videoeditcomponent = "\
            <div>\
            <div class='form-group'>\
                                            <h4>Video #{{VIDEO_NUMBER}}</h4>\
                                        </div>\
                                        <div class='form-group'>\
                                            <label for='videoname{{VIDEO_NUMBER}}' class='col-sm-2 control-label'>Video Name</label>\
                                            <div class='col-sm-10'>\
                                                <input type='text' class='form-control' name='videoname{{VIDEO_NUMBER}}' id='videoname{{VIDEO_NUMBER}}' value='{{VIDEO_NAME}}'/>\
                                            </div>\
                                        </div>\
                                        <br/><br/><br/>\
                                        <div class='form-group'>\
                                            <label for='videodescription{{VIDEO_NUMBER}}' class='col-sm-2 control-label'>Description</label>\
                                            <div class='col-sm-10'>\
                                                <textarea class='form-control' rows='4' name='videodescription{{VIDEO_NUMBER}}' id='videodescription{{VIDEO_NUMBER}}' >{{VIDEO_DESCRIPTION}}</textarea>\
                                            </div>\
                                        </div>\
                                        <br/><br/><br/><br/><br/>\
                                        <div class='form-group'>\
                                            <label for='youtubeid{{VIDEO_NUMBER}}' class='col-sm-2 control-label'>Video Link</label>\
                                            <div class='col-sm-10'>\
                                                <input type='text' class='form-control' name='youtubeid{{VIDEO_NUMBER}}' id='youtubeid{{VIDEO_NUMBER}}' value='{{YOUTUBE_ID}}'/>\
                                            </div>\
                                        </div>\
                                        <br/><br/>\
                                        </div>\
                            ";
            function addvideocomponent() {
                var viddiv = $("#videosdiv");
                var vidnum = viddiv.children('div').length + 1;
                var copy = new String(videoeditcomponent);
                copy = copy.replace(/{{VIDEO_NUMBER}}/g, vidnum);
                copy = copy.replace(/{{VIDEO_NAME}}/g, "");
                copy = copy.replace(/{{VIDEO_DESCRIPTION}}/g, "");
                copy = copy.replace(/{{YOUTUBE_ID}}/g, "");
                viddiv.append(copy);
            }
        </script>
</head>
<script type="text/javascript">

window.onload = function(){
	 setTimeout(function(){
	   alert("Session Timed Out! Please Login Again");
	   window.location = "adminlogout.php";
	 }, 10800000);
	};
</script>

<body>
	<div class="container">
		<div class="pull-right">
			<button type="button" class="btn btn-success"
				onclick="window.location = 'adminhome.php'">Admin Home</button>
			<button type="button" class="btn btn-primary"
				onclick="window.location = 'adminlogout.php'">Log Out</button>
		</div>
		<div class="pull-left">
			<button type="button" class="btn btn-success"
				onclick="window.location = 'allcourses.php'">Back to Course
				Dashboard</button>
		</div>
	</div>
	<div class="container">
		<h3>Edit Course: <?php echo $cname; ?></h3>
		<br />
	</div>

	<div class="container">
		<form role="form" method="POST" action="savecourseedit.php">
			<input type="hidden" name="cid" value="<?php echo $cid; ?>" />
			<div class="container">
				<div class="row">
					<div class="col-xs-9">
						<div class="row">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Basic details of the course</h3>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<label for="coursename" class="col-sm-2 control-label">Course
											Name</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="coursename"
												id="coursename" value="<?php echo $cname; ?>" />
										</div>
										<label for="coursemandatory" class="col-sm-2 control-label">Mandatory</label>
										<div class="col-sm-1">
											<input type="checkbox" class="" name="coursemandatory"
												id="coursemandatory" value="1"
												<?php
												
if ($mandatory == 1) {
													echo "checked=''";
												}
												?> />
										</div>
									</div>
									<br />
									<br />
									<div class="form-group">
										<label for="coursedescription" class="col-sm-2 control-label">Description</label>
										<div class="col-sm-10">
											<textarea class="form-control" rows="4"
												name="coursedescription" id="coursedescription"><?php echo $description; ?></textarea>
										</div>
									</div>
									<br /> <br />
									<br /> <br />
									<br>
									<div class="form-group">
										<label for="validity" class="col-sm-2 control-label">Validity</label>
										<div class="col-sm-3">
											<input type="number" class="form-control" name="validity"
												placeholder="Number of days" id="validity"
												value="<?php echo $validity; ?>" required="" />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Videos</h3>
								</div>
								<div class="panel-body">
									<div id="videosdiv">
                                            <?php
																																												$query = "Select vnum, title,description,youtubeid from videos where cid = '" . $cid . "' order by vnum";
																																												$stmt = mysqli_prepare ( $db, $query );
																																												mysqli_stmt_execute ( $stmt );
																																												mysqli_stmt_store_result ( $stmt );
																																												mysqli_stmt_bind_result ( $stmt, $vnum, $title, $vdescription, $youtubeid );
																																												while ( mysqli_stmt_fetch ( $stmt ) ) {
																																													?>
                                                <div>
											<div class="form-group">
												<h4>Video #<?php echo $vnum; ?></h4>
											</div>
											<div class="form-group">
												<label for="videoname<?php echo $vnum; ?>"
													class="col-sm-2 control-label">Video Name</label>
												<div class="col-sm-10">
													<input type="text" class="form-control"
														name="videoname<?php echo $vnum; ?>"
														id="videoname<?php echo $vnum; ?>"
														value="<?php echo $title; ?>" />
												</div>
											</div>
											<br />
											<br />
											<br />
											<div class="form-group">
												<label for="videodescription<?php echo $vnum; ?>"
													class="col-sm-2 control-label">Description</label>
												<div class="col-sm-10">
													<textarea class="form-control" rows="4"
														name="videodescription<?php echo $vnum; ?>"
														id="videodescription<?php echo $vnum; ?>"><?php echo $vdescription; ?></textarea>
												</div>
											</div>
											<br />
											<br />
											<br />
											<br />
											<br />
											<div class="form-group">
												<label for="youtubeid<?php echo $vnum; ?>"
													class="col-sm-2 control-label">Video Link</label>
												<div class="col-sm-10">
													<input type="text" class="form-control"
														name="youtubeid<?php echo $vnum; ?>"
														id="youtubeid<?php echo $vnum; ?>"
														value="<?php echo $youtubeid; ?>" />
												</div>
											</div>
											<br />
											<br />

										</div>
                                                <?php
																																												}
																																												mysqli_stmt_close ( $stmt );
																																												?>

                                        </div>
									<p class="text-info">Info: Leave the Video link field empty to
										delete a listing</p>

									<a class="btn btn-primary pull-right"
										onclick="addvideocomponent();">Add Video</a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">
									<strong>Add Test</strong>
								</h3>
							</div>
							<div class="panel-body">
								Create or Edit a Test by adding or editting question(s) and
								answer(s)<br>
								<br>
								<p>
									<a class="btn btn-primary"
										href="createtest.php?cid=<?php echo $cid?>">&nbsp&nbsp Add
										&nbsp&nbsp</a>
								</p>
							</div>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">
									<strong>Assign Course</strong>
								</h3>
							</div>
							<div class="panel-body">
								Assign course to Department, Designation or Users<br>
								<br>
								<p>
									<a class="btn btn-primary"
										href="user_assign.php?cid=<?php echo $cid?>">&nbsp&nbsp Assign
										&nbsp&nbsp</a>
								</p>
							</div>
						</div>
					</div>

				</div>
				<div class="row">
					<button type="submit" class="btn btn-success">Submit</button>
					<a class="btn btn-primary" href='allcourses.php'>Cancel</a>
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
</body>

</html>
