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
if (!isset($_GET['uid'])) {
    ?>
    <script language="javascript">window.location = "allusers.php"</script>
    <?php
    exit();
}
$uid = $_GET['uid'];
$db = mysqli_connect($dburl, $dbuser, $dbpassword, $dbdatabase) or die("Can't connect to database!");
$query = "Select uid,emp_id,fname,lname,email,deptid,post,managerfname, managerlname, manageremail, hrmanagerfname, hrmanagerlname, hrmanageremail from users where uid = '" . $uid . "'";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $uid, $eid, $fname, $lname, $email, $deptid, $post,$managerfname, $managerlname, $manageremail, $hrmanagerfname, $hrmanagerlname, $hrmanageremail);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$userpost = $deptid . ";:;" . $post;

$posts_value = array();
$posts_text = array();
$depts = array();

$query = "Select deptid,deptname from departments";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $di, $dn);
while (mysqli_stmt_fetch($stmt)) {
    $depts[$di] = $dn;
}
mysqli_stmt_close($stmt);

$query = "Select deptid,post from posts order by deptid";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $di, $p);
while (mysqli_stmt_fetch($stmt)) {
    array_push($posts_value, $di . ";:;" . $p);
    array_push($posts_text, $depts[$di] . " - " . $p);
}
mysqli_stmt_close($stmt);
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

        <title>Edit User: <?php echo $fname; ?></title>

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

    </script>
	<script>
	function letternumber(e)
	{
		var key;
		var keychar;

		if (window.event)
		   key = window.event.keyCode;
		else if (e)
		   key = e.which;
		else
		   return true;
		keychar = String.fromCharCode(key);
		keychar = keychar.toLowerCase();

		// control keys
		if ((key==null) || (key==0) || (key==8) || 
			(key==9) || (key==13) || (key==27) )
		   return true;

		// alphas and numbers
		else if ((("abcdefghijklmnopqrstuvwxyz0123456789").indexOf(keychar) > -1))
			return true;
		else
			return false;
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
            <button type="button" class="btn btn-success" onclick="window.location = 'adminhome.php'">Admin Home</button>
            <button type="button" class="btn btn-primary" onclick="window.location = 'adminlogout.php'">Log Out</button>
        </div>
        <div class="pull-left">
                <button type="button" class="btn btn-success" onclick="window.location = 'allusers.php'">Back to All Users</button>
        </div>
    </div>
    <div class="container">
        <h3>Edit User: <?php echo $fname . " " . $lname; ?></h3>
        <br/>
    </div>

    <div class="container">
        <form role="form" method="POST" action="saveuseredit.php">
            <input type="hidden" name="uid" value="<?php echo $uid; ?>"/>
            <div class="container">
                <div class="row">
                    <div class="col-xs-9">
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Basic details of the User</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="employeeid" class="col-sm-2 control-label">Employee ID</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" onKeyPress="return letternumber(event)" name="employeeid" id="employeeid" value="<?php echo $eid; ?>" placeholder="Employee ID" required/>
                                        </div>
                                    </div>
                                    <br/><br/>
									<div class="form-group">
                                        <label for="userfname" class="col-sm-2 control-label">First Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="userfname" id="userfname" value="<?php echo $fname; ?>" placeholder="First Name"/>
                                        </div>
                                    </div>
                                    <br/><br/>
                                    <div class="form-group">
                                        <label for="userlname" class="col-sm-2 control-label">Last Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="userlname" id="userlname" value="<?php echo $lname; ?>" placeholder="Last Name"/>
                                        </div>
                                    </div>
                                    <br/><br/>
                                    <div class="form-group">
                                        <label for="useremail" class="col-sm-2 control-label">User Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" readonly = "readonly" class="form-control" name="useremail" id="useremail" value="<?php echo $email; ?>" placeholder="Email"/>
                                        </div>
                                    </div>
                                    <br/><br/>
                                    <div class="form-group">
                                        <label for="userpost" class="col-sm-2 control-label">User Post</label>
                                        <div class="col-sm-10">

                                            <select class="form-control" name="userpost" id="userpost">
                                                <?php
                                                for ($n = 0; $n < sizeof($posts_value); $n++) {
                                                    ?>
                                                    <option value='<?php echo $posts_value[$n]; ?>' <?php if ($userpost == $posts_value[$n]) {
                                                    echo "selected";
                                                } ?>><?php echo $posts_text[$n]; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" style="padding-left:0px">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">User's Manager</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="managerfname" class="col-sm-4 control-label">First Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="managerfname" id="managerfname" value="<?php echo $managerfname; ?>" placeholder="Manager's First Name" required/>
                                            </div>
                                        </div>
                                        <br/><br/>
                                        <div class="form-group">
                                            <label for="managerlname" class="col-sm-4 control-label">Last Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="managerlname" id="managerlname" value="<?php echo $managerlname; ?>" placeholder="Manager's Last Name"/>
                                            </div>
                                        </div>
                                        <br/><br/>
                                        <div class="form-group">
                                            <label for="manageremail" class="col-sm-4 control-label">Email</label>
                                            <div class="col-sm-8">
                                                <input type="email" class="form-control" name="manageremail" id="manageremail" value="<?php echo $manageremail; ?>" placeholder="Manager's Email" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding-right:0px">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">User's HR Manager</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="hrmanagerfname" class="col-sm-4 control-label">First Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="hrmanagerfname" id="hrmanagerfname" value="<?php echo $hrmanagerfname; ?>" placeholder="HR Manager's First Name" required/>
                                            </div>
                                        </div>
                                        <br/><br/>
                                        <div class="form-group">
                                            <label for="hrmanagerlname" class="col-sm-4 control-label">Last Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="hrmanagerlname" id="hrmanagerlname" value="<?php echo $hrmanagerlname; ?>" placeholder="HR Manager's Last Name"/>
                                            </div>
                                        </div>
                                        <br/><br/>
                                        <div class="form-group">
                                            <label for="hrmanageremail" class="col-sm-4 control-label">Email</label>
                                            <div class="col-sm-8">
                                                <input type="email" class="form-control" name="hrmanageremail" id="hrmanageremail" value="<?php echo $hrmanageremail; ?>" placeholder="HR Manager's Email" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-success">Submit</button>
                    &nbsp;
                    <a class="btn btn-primary" href='allusers.php'>Cancel</a>
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
