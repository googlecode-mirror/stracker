<?php
require_once "inc/general.php";

if($_GET['salt']){
	$db = g_db_conn();
	$salt = stripslashes(mysqli_real_escape_string($db,$_GET['salt']));
	mysqli_query($db,"UPDATE users SET activated=1 WHERE salt='$salt' AND activated=0;") or die("Activation failed, please contact the site administrator at " . $cfg['email']);
	header("Location: login.php");
}
