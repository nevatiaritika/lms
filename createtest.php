<?php
session_start ();
require 'connvars.php';
?>
<?php

date_default_timezone_set ( "Asia/Kolkata" );
if (! (isset ( $_SESSION ['aid'] ) && $_SESSION ['aid'] != '')) {
	
	header ( "Location: adminlogin.php" );
}
?>
<?php

if (! isset ( $_GET ['cid'] )) {
	?>
<script language="javascript">window.location = "allcourses.php"</script>
<?php
	exit ();
}
function line_br_json($text) {
	$text =str_replace ( "##!!**!!##", "\\r\\n", $text );
	return $text;
}

$cid = $_GET ['cid'];
$db = mysqli_connect ( $dburl, $dbuser, $dbpassword, $dbdatabase ) or die ( "Can't connect to database!" );
$query = "Select cid,cname,description,mandatory from courses where cid = '" . $cid . "'";
$stmt = mysqli_prepare ( $db, $query );
mysqli_stmt_execute ( $stmt );
mysqli_stmt_store_result ( $stmt );
mysqli_stmt_bind_result ( $stmt, $cid, $cname, $description, $mandatory );
mysqli_stmt_fetch ( $stmt );
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

<title>Add Test: <?php echo $cname; ?></title>

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
<script type="text/javascript">

window.onload = function(){
	 setTimeout(function(){
	   alert("Session Timed Out! Please Login Again");
	   window.location = "adminlogout.php";
	 }, 10800000);
	};
</script>

<script>
            var subeditcomponent = "<div>\
                                                                                <div id='subForm{{form_number}}'><br>\
                                                                                        <div class='form-group'>\
                                                                                                <label for='subquest{{form_number}}' id='sublabel{{form_number}}' class='col-sm-2 control-label'>Sujective Q. {{quest_number}} </label>\
                                                                                                <div class='col-sm-10'>\
                                                                                                        <textarea class='form-control' rows='3' name='subquest{{form_number}}' id='subquest{{form_number}}' required>subquest{{form_number}}</textarea><br>\
                                                                                                        <button type='button' class='btn btn-danger' id='delsub{{form_number}}' onClick='deleteSub(this.id)'>Delete Question</button><br><br>\
                                                                                                </div>\
                                                                                        </div>\
                                                                                </div>\
                                                                          </div>";
            var objeditcomponent = "<div>\
                                                                                        <div id='objForm{{form_number}}'><br>\
                                                                                                <div class='form-group'>\
                                                                                                        <label for='objquest{{form_number}}' id='objlabel{{form_number}}' class='col-sm-2 control-label'>Objective Q. {{quest_number}} </label>\
                                                                                                        <div class='col-sm-10'>\
                                                                                                                <textarea rows='3' class='form-control' name='objquest{{form_number}}' id='objquest{{form_number}}' required></textarea><br>\
                                                                                                        </div>\
                                                                                                </div>\
                                                                                                <div class='form-group'>\
                                                                                                        <label for='labelo{{form_number}}' class='col-sm-2 control-label'>Options</label>\
                                                                                                        <div class='col-sm-10'>\
                                                                                                                <div name='labelo{{form_number}}' id='labelo{{form_number}}'>\
                                                                                                                        <div id='optionsdiv{{form_number}}'>\
                                                                                                                                <div id='divopt{{form_number}}0'>\
                                                                                                                                        <input type='hidden' name='answer{{form_number}}[]' id='answer{{form_number}}[]' value='0' />\
                                                                                                                                        <div class='form-group'>\
                                                                                                                                        <div class='row'>\
                                                                                                                                        <div class='col-sm-1'>\
                                                                                                                                        <input type='checkbox' name='answer{{form_number}}[]' id='answer{{form_number}}[]' value='1' ></div>\
                                                                                                                                        <div class='col-sm-9'><input type='text' class='form-control' name='objective{{form_number}}[]' id='objective{{form_number}}[]' required ></div>\
                                                                                                                                        <div class='col-sm-1'><button type='button' class='btn btn-danger' id='deleteoption{{form_number}}0' onClick='deleteoption(this.id)' >Delete</button></div>\
                                                                                                                                        </div>\
                                                                                                                                        </div>\
                                                                                                                                </div>\
                                                                                                                                <div id='divopt{{form_number}}1'>\
                                                                                                                                        <input type='hidden' name='answer{{form_number}}[]' id='answer{{form_number}}[]' value='0' />\
                                                                                                                                        <div class='form-group'>\
                                                                                                                                        <div class='row'>\
                                                                                                                                        <div class='col-sm-1'>\
                                                                                                                                        <input type='checkbox' name='answer{{form_number}}[]' id='answer{{form_number}}[]' value='1' ></div>\
                                                                                                                                        <div class='col-sm-9'><input type='text' class='form-control' name='objective{{form_number}}[]' id='objective{{form_number}}[]' required ></div>\
                                                                                                                                        <div class='col-sm-1'><button type='button' class='btn btn-danger' id='deleteoption{{form_number}}1' onClick='deleteoption(this.id)' >Delete</button></div>\
                                                                                                                                        </div>\
                                                                                                                                        </div>\
                                                                                                                                </div>\
                                                                                                                                <div id='divopt{{form_number}}2'>\
                                                                                                                                        <input type='hidden' name='answer{{form_number}}[]' id='answer{{form_number}}[]' value='0' />\
                                                                                                                                        <div class='form-group'>\
                                                                                                                                        <div class='row'>\
                                                                                                                                        <div class='col-sm-1'>\
                                                                                                                                        <input type='checkbox' name='answer{{form_number}}[]' id='answer{{form_number}}[]' value='1' ></div>\
                                                                                                                                        <div class='col-sm-9'><input type='text' class='form-control' name='objective{{form_number}}[]' id='objective{{form_number}}[]' required ></div>\
                                                                                                                                        <div class='col-sm-1'><button type='button' class='btn btn-danger' id='deleteoption{{form_number}}2' onClick='deleteoption(this.id)' >Delete</button></div>\
                                                                                                                                        </div>\
                                                                                                                                        </div>\
                                                                                                                                </div>\
                                                                                                                                <div id='divopt{{form_number}}3'>\
                                                                                                                                        <input type='hidden' name='answer{{form_number}}[]' id='answer{{form_number}}[]' value='0' />\
                                                                                                                                        <div class='form-group'>\
                                                                                                                                        <div class='row'>\
                                                                                                                                        <div class='col-sm-1'>\
                                                                                                                                        <input type='checkbox' name='answer{{form_number}}[]' id='answer{{form_number}}[]' value='1' ></div>\
                                                                                                                                        <div class='col-sm-9'><input type='text' class='form-control' name='objective{{form_number}}[]' id='objective{{form_number}}[]' required ></div>\
                                                                                                                                        <div class='col-sm-1'><button type='button' class='btn btn-danger' id='deleteoption{{form_number}}3' onClick='deleteoption(this.id)' >Delete</button></div>\
                                                                                                                                        </div>\
                                                                                                                                        </div>\
                                                                                                                                </div>\
                                                                                                                        </div><br>\
                                                                                                                        <button type='button' class='btn btn-primary' id='addoptbut{{form_number}}' onClick='addtestopt(this.id)'>Add Option</button>&nbsp;<button type='button' class='btn btn-danger' id='delbut{{form_number}}' onClick='deleteObj(this.id)'>Delete Question</button><br><br>\
                                                                                                                </div>\
                                                                                                        </div>\
                                                                                                </div>\
                                                                                        </div>\
                                                                                </div>";

            function addtest() {
                var testdiv = $("#tablesdiv");
                var testnum = parseInt(document.getElementById("numques").value) + 1;
                var check = document.getElementById("myList").value;
                if (check == "Subjective Question")
                {
                    var copy = new String(subeditcomponent);
                    copy = copy.replace(/{{form_number}}/g, (testnum - 1));
                    copy = copy.replace(/{{quest_number}}/g, (testnum));
                    testdiv.append(copy);
                }
                else
                {
                    var copy = new String(objeditcomponent);
                    copy = copy.replace(/{{form_number}}/g, (testnum - 1));
                    copy = copy.replace(/{{quest_number}}/g, (testnum));
                    testdiv.append(copy);
                }
                document.getElementById("numques").value = "" + testnum;
            }

            function addtestopt(count) {
                var char = (count + "").charAt((count + "").length - 1);
                var vrows = document.getElementsByName("objective" + char + "[]");
                var no = vrows.length;

                count = count.replace("addoptbut", "");
                var viddivopt = $("#optionsdiv" + count);
                var vidnumopt = viddivopt.children('div').length + 1;
                viddivopt.append("<div  id='divopt" + char + no + "'>\<input type='hidden' name='answer" + char + "[]' id='answer" + char + "[]' value='0' /><div class='form-group'>\<div class='row'><div class='col-sm-1'>\<input type='checkbox' name='answer" + char + "[]' id='answer" + char + "[]' value='1'></div>\<div class='col-sm-9'><input type='text' class='form-control' name='objective" + char + "[]' id='objective" + char + "[]' required></div>\<div class='col-sm-1'><button type='button' class='btn btn-danger' id='deleteoption" + char + no + "' onClick='deleteoption(this.id)' >Delete</button><br><br></div>\</div>\</div>\</div>");
            }

            function deleteoption(count)
            {
                var copy = new String(count);
                copy = copy.replace("deleteoption", "divopt");

                var elem = document.getElementById(copy);
                elem.parentNode.removeChild(elem);
                reduceid(count);
                return false;

            }

            function reduceid(count)
            {
                count = count.replace("deleteoption", "");
                question = count.charAt(0);
                option = parseInt(count.charAt(1) + "");
                noption = option + 1;

                var viddivopt = $("#optionsdiv" + question);
                var vidnumopt = viddivopt.children('div').length;
                //document.write(vidnumopt);
                for (var i = option; i < vidnumopt; i++) {
                    document.getElementById("divopt" + question + noption).setAttribute("id", "divopt" + question + option);
                    document.getElementById("deleteoption" + question + noption).setAttribute("id", "deleteoption" + question + option);
                    option++;
                    noption++;

                }
            }

            function deleteSub(count)
            {
                count = count.replace("delsub", "");

                var elem = document.getElementById("subForm" + count);
                elem.parentNode.removeChild(elem);
                decreaseid(count);
                return false;
            }

            function deleteObj(count)
            {
                count = count.replace("delbut", "");

                var elem = document.getElementById("objForm" + count);
                elem.parentNode.removeChild(elem);
                decreaseid(count);
                return false;
            }

            function IsInDocument(el) {

                var html = document.getElementsByTagName('body')[0];
                while (el) {
                    if (el === html) {
                        return true;
                    }
                    el = el.parentNode;
                }
                return false;
            }
            function decreaseid(count)
            {
                var dec = count;
                count++;

                while (IsInDocument(document.getElementById("objForm" + count)) || IsInDocument(document.getElementById("subForm" + count)))
                {
                    if (IsInDocument(document.getElementById("objForm" + count)))
                    {
                        document.getElementById("objlabel" + count).setAttribute("id", "objlabel" + dec);
                        document.getElementById('objlabel' + dec).innerHTML = 'Objective Q. ' + count;
                        document.getElementById("objForm" + count).setAttribute("name", "objForm" + dec);
                        document.getElementById("objquest" + count).setAttribute("name", "objquest" + dec);
                        if (IsInDocument(document.getElementById("answer" + count + "[]"))) {
                            document.getElementById("answer" + count + "[]").setAttribute("name", "answer" + dec + "[]");
                        }
                        if (IsInDocument(document.getElementById("objective" + count + "[]"))) {
                            document.getElementById("objective" + count + "[]").setAttribute("name", "objective" + dec + "[]");
                        }
                        document.getElementById("optionsdiv" + count).setAttribute("name", "optionsdiv" + dec);
                        document.getElementById("addoptbut" + count).setAttribute("name", "addoptbut" + dec);
                        document.getElementById("delbut" + count).setAttribute("name", "delbut" + dec);

                        document.getElementById("objForm" + count).setAttribute("id", "objForm" + dec);
                        document.getElementById("objquest" + count).setAttribute("id", "objquest" + dec);
                        if (IsInDocument(document.getElementById("answer" + count + "[]"))) {
                            document.getElementById("answer" + count + "[]").setAttribute("id", "answer" + dec + "[]");
                        }
                        if (IsInDocument(document.getElementById("objective" + count + "[]"))) {
                            document.getElementById("objective" + count + "[]").setAttribute("id", "objective" + dec + "[]");
                        }
                        document.getElementById("optionsdiv" + count).setAttribute("id", "optionsdiv" + dec);
                        document.getElementById("addoptbut" + count).setAttribute("id", "addoptbut" + dec);
                        document.getElementById("delbut" + count).setAttribute("id", "delbut" + dec);
                    }
                    else if (IsInDocument(document.getElementById("subForm" + count)))
                    {
                        document.getElementById("sublabel" + count).setAttribute("id", "sublabel" + dec);
                        document.getElementById('sublabel' + dec).innerHTML = 'Subjective Q. ' + count;
                        document.getElementById("subForm" + count).setAttribute("name", "subForm" + dec);
                        document.getElementById("subquest" + count).setAttribute("name", "subquest" + dec);
                        document.getElementById("delsub" + count).setAttribute("name", "delsub" + dec);
                        document.getElementById("subForm" + count).setAttribute("id", "subForm" + dec);
                        document.getElementById("subquest" + count).setAttribute("id", "subquest" + dec);
                        document.getElementById("delsub" + count).setAttribute("id", "delsub" + dec);
                    }
                    dec = count;
                    count++;
                }
                document.getElementById("numques").value = "" + dec;

            }

        </script>
</head>

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
				onclick="window.location = 'allcourses.php'">Back to All Courses</button>
		</div>
	</div>
	<div class="container">
		<h3>Add Test</h3>
		<br />
	</div>
	<div class="container">
		<form role="form" method="POST" action="savetestedit.php">
                <?php
																$query = "Select ctestid, testjson, time_hr, time_min from tests where ctestid = '" . $cid . "' ";
																$stmt = mysqli_prepare ( $db, $query );
																if (! $stmt) {
																	die ( 'mysqli error: ' . mysqli_error ( $db ) );
																}
																mysqli_stmt_execute ( $stmt );
																mysqli_stmt_store_result ( $stmt );
																mysqli_stmt_bind_result ( $stmt, $ctestid, $testjson, $time_hr, $time_min );
																mysqli_stmt_fetch ( $stmt );
																$jsondec = json_decode (line_br_json($testjson), true);
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
										<strong>TEST : <?php echo $cname; ?></strong>
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
														class='col-sm-2 control-label'>Subjective Q. <?php echo ($questind + 1); ?> </label>
													<div class='col-sm-10'>
														<textarea rows="3" class='form-control'
															name='subquest<?php echo $questind; ?>'
															id='subquest<?php echo $questind; ?>'><?php echo $jsondec["question"][$questind]["text"]; ?></textarea>
														<br>
														<button type='button' class='btn btn-danger'
															id='delsub<?php echo $questind; ?>'
															onClick='deleteSub(this.id)'>Delete Question</button>
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
															class='col-sm-2 control-label'>Objective Q. <?php echo ($questind + 1); ?> </label>
														<div class='col-sm-10'>
															<textarea rows="3" class='form-control'
																name='objquest<?php echo $questind; ?>'
																id='objquest<?php echo $questind; ?>'><?php echo $jsondec["question"][$questind]["text"]; ?></textarea>
															<br>
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
                                                                                                <?php
																																																	if (in_array ( $jsondec ["question"] [$questind] ["options"] [$opt], $jsondec ["question"] [$questind] ["answer"] )) {
																																																		?>

                                                                                                    <input
																			type='hidden' name='answer<?php echo $questind; ?>[]'
																			id='answer<?php echo $questind; ?>[]' value='0' />
																		<div class="form-group">
																			<div class="row">
																				<div class="col-sm-1">
																					<input type='checkbox'
																						id='answer<?php echo $questind; ?>[]'
																						name='answer<?php echo $questind; ?>[]'
																						<?php echo "checked"; ?> value='1'>
																				</div>
																				<div class="col-sm-9">
																					<input type='text' class='form-control'
																						name='objective<?php echo $questind . ""; ?>[]'
																						id='objective<?php echo $questind . ""; ?>[]'
																						value='<?php echo $jsondec["question"][$questind]["options"][$opt] ?>'>
																				</div>
																				<div class="col-sm-1">
																					<button type='button' class='btn btn-danger'
																						id='deleteoption<?php echo $questind . "" . $opt; ?>'
																						onClick='deleteoption(this.id)'>Delete</button>
																				</div>
																			</div>
																		</div>
                                                                                                    <?php
																																																	} else {
																																																		?>
                                                                                                    <input
																			type='hidden' name='answer<?php echo $questind; ?>[]'
																			id='answer<?php echo $questind; ?>[]' value='0' />
																		<div class="form-group">
																			<div class="row">
																				<div class="col-sm-1">
																					<input type='checkbox'
																						id='answer<?php echo $questind; ?>[]'
																						name='answer<?php echo $questind; ?>[]' value='1'>
																				</div>
																				<div class="col-sm-9">
																					<input type='text' class='form-control'
																						name='objective<?php echo $questind . ""; ?>[]'
																						id='objective<?php echo $questind . ""; ?>[]'
																						value='<?php echo $jsondec["question"][$questind]["options"][$opt] ?>'>
																				</div>
																				<div class="col-sm-1">
																					<button type='button' class='btn btn-danger'
																						id='deleteoption<?php echo $questind . "" . $opt; ?>'
																						onClick='deleteoption(this.id)'>Delete</button>
																				</div>
																			</div>
																		</div>
                                                                                                    <?php
																																																	}
																																																	?>
                                                                                            </div>		
                                                                                            <?php
																																																}
																																															}
																																															?>
                                                                                </div>
																<br>
																<button type='button'
																	id='addoptbut<?php echo $questind; ?>'
																	class='btn btn-primary' onClick='addtestopt(this.id)'>Add
																	Option</button>
																&nbsp;
																<button type='button' class='btn btn-danger'
																	id='delbut<?php echo $questind; ?>'
																	onClick='deleteObj(this.id)'>Delete Question</button>
																<br> <br>
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

									<br> <select class="form-control" id="myList">
										<option>Subjective Question</option>
										<option>Objective Question</option>
									</select> <br> <a class="btn btn-primary pull-right"
										onclick="addtest();">Add Question</a>

								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">
											<strong>Set Time Limit</strong>
										</h3>
									</div>
									<div class="panel-body">
										<div class="form-group form-horizontal">
											<label for="hours" class="col-sm-4 control-label">Hours</label>
											<div class="col-sm-8">
												<select class="form-control" id="hours" name="hours">
                                                    <?php
																																																				
																																																				if (isset ( $time_hr )) {
																																																					if ($time_hr == 0)
																																																						echo '<option value="0" selected="selected" >0</option>';
																																																					else
																																																						echo '<option value="0">0</option>';
																																																					
																																																					if ($time_hr == 1)
																																																						echo '<option value="1" selected="selected" >1</option>';
																																																					else
																																																						echo '<option value="1">1</option>';
																																																					
																																																					if ($time_hr == 2)
																																																						echo '<option value="2" selected="selected" >2</option>';
																																																					else
																																																						echo '<option value="2">2</option>';
																																																				} else {
																																																					?>
                                                        <option
														value="0">0</option>
													<option value="1" selected="selected">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
                                                      <?php }?>
                                                    </select>
											</div>
										</div>
										<br /> <br />
										<div class="form-group form-horizontal">
											<label for="minutes" class="col-sm-4 control-label">Minutes</label>
											<div class="col-sm-8">
												<select class="form-control" id="minutes" name="minutes">
                                                    <?php
																																																				if (isset ( $time_min )) {
																																																					if ($time_min == 10)
																																																						echo '<option value="10" selected="selected">10</option>';
																																																					else
																																																						echo '<option value="10">10</option>';
																																																					
																																																					if ($time_min == 15)
																																																						echo '<option value="15" selected="selected">15</option>';
																																																					else
																																																						echo '<option value="15">15</option>';
																																																					
																																																					if ($time_min == 20)
																																																						echo '<option value="20" selected="selected">20</option>';
																																																					else
																																																						echo '<option value="20">20</option>';
																																																					
																																																					if ($time_min == 25)
																																																						echo '<option value="25" selected="selected">25</option>';
																																																					else
																																																						echo '<option value="25">25</option>';
																																																					
																																																					if ($time_min == 30)
																																																						echo '<option value="30" selected="selected">30</option>';
																																																					else
																																																						echo '<option value="30">30</option>';
																																																					
																																																					if ($time_min == 35)
																																																						echo '<option value="35" selected="selected">35</option>';
																																																					else
																																																						echo '<option value="35">35</option>';
																																																					
																																																					if ($time_min == 40)
																																																						echo '<option value="40" selected="selected">40</option>';
																																																					else
																																																						echo '<option value="40">40</option>';
																																																					
																																																					if ($time_min == 45)
																																																						echo '<option value="45" selected="selected">45</option>';
																																																					else
																																																						echo '<option value="45">45</option>';
																																																					
																																																					if ($time_min == 50)
																																																						echo '<option value="50" selected="selected">50</option>';
																																																					else
																																																						echo '<option value="50">50</option>';
																																																					
																																																					if ($time_min == 55)
																																																						echo '<option value="55" selected="selected">55</option>';
																																																					else
																																																						echo '<option value="55">55</option>';
																																																				} else {
																																																					?>
                                                        <option
														value="10">10</option>
													<option value="15">15</option>
													<option value="20">20</option>
													<option value="25">25</option>
													<option value="30" selected="selected">30</option>
													<option value="35">35</option>
													<option value="40">40</option>
													<option value="45">45</option>
													<option value="50">50</option>
													<option value="55">55</option>
                                                        <?php
																																																				}
																																																				?>
                                                    </select>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">Info</h3>
									</div>
									<div class="panel-body">Tick the checkbox to set an option as
										answer</div>
								</div>
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
