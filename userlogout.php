<?php
session_start();
if(isset($_SESSION['uid'])){
    unset($_SESSION['uid']);
}
?>
<script language="javascript">window.location="userlogin.php"</script>
