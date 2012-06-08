<?php
require_once "inc/general.php";

if($_GET['salt']){
	$db = g_db_conn();
	$salt = mysqli_real_escape_string($db,$_GET['salt']);
	mysqli_query($db,"UPDATE users SET active=1 WHERE salt='$salt'") or die("Activation failed, please contact the site administrator at " . $cfg['email'] . "<br />" . mysqli_error($db) . "<br />" . var_dump($e));
	mysqli_close($db);
	header("Location: login.php");
}
