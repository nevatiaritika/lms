<?php
session_start();
if(isset($_SESSION['aid'])){
    unset($_SESSION['aid']);
}
?>
<script language="javascript">window.location="adminlogin.php"</script>
