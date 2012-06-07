<?php
	// This is the page where new user registrations take place
	require_once("inc/general.php");
	g_req("auth");
	
	if(isset($_SESSION['uid'])){header("Location: home.php");} //Redirect to home page if already logged in
	if(isset($_POST['takeuser'])){auth_register($_POST['takeuser'],$_POST['takepass'],$_POST['takepass2'],$_POST['takemail'],$_POST['takemail2']);} // Register the user
	
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
					<div id="registerbox">
						<form action="register.php" method="post">
							<input class="field" type="text" size=22 name="takeuser" id="takeuser" /><br />
							<input class="field" type="text" size=22 name="takemail" id="takemail" /><br />
							<input class="field" type="text" size=22 name="takemail2" id="takemail2" /><br />
							<input class="field" type="password" size=22 name="takepass" id="takepass" /><br />
							<input class="field" type="password" size=22 name="takepass2" id=takepass2" /><br />' .
							recaptcha_get_html($cfg['publickey'], $error) . '<br />
							<input class="btn" type="submit" name="submit" id="submit" value="' . $lang['register'] . '" />&nbsp;
							<input class="btn" type="button" name="register" id="register" value="' . $lang['register'] . '" onclick="window.location = \'register.php\'" />
						</form>
					</div>
				</body>
			</html>
			';
	echo $out; // Print HTML
	// End of document
?>