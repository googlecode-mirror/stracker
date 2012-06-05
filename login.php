<?php
	// This is the page on which the user will land if not logged in
	// Contains only the login formula
	require_once("inc/general.php");
	g_req("auth");
	
	if(isset($_SESSION['uid'])){header("Location: home.php");} //Redirect to home page if already logged in
	if(isset($_POST['takeuser'])){auth_validate($_POST['takeuser'],$_POST['takepass']);} //Validate the user information if any and redirect
	
	// Begin HTML
	$out .= '
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
			<html>
				<head>
					<title>' . $cfg['title'] . '</title>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<meta name="description" content="' . $cfg['description'] . '" />
					<meta name="keywords" content="' . $cfg['keywords'] . '" />
					<link rel="stylesheet" type="text/css" href="styles/' . $cfg['default_theme'] . '/login.css" />
				</head>
				<body>
					<div id="loginbox">
						<form action="login.php" method="post">
							<input class="field" type="text" size=22 name="takeuser" id="takeuser" />&nbsp;
							<input class="field" type="password" size=22 name="takepass" id="takepass" /><br />
							<input class="btn" type="submit" name="submit" id="submit" value="' . $lang['login'] . '" />&nbsp;
							<input class="btn" type="button" name="register" id="register" value="' . $lang['register'] . '" onclick="window.location = \'register.php\'" />
						</form>
					</div>
				</body>
			</html>
			';
	echo $out; // Print HTML
	// End of document
?>