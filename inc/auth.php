<?php
////////////////////////////////////////////
//                 Auth                   //
//----------------------------------------//
// This file contains the following auth  //
// system functions for user checking and //
// registration such as:                  //
// - auth_validate                        //
// - auth_register                        //
////////////////////////////////////////////
// Required includes:                     //
   g_req(array("general"));               //
////////////////////////////////////////////

function _auth_validate($takeuser,$takepass){
	// Check Captcha
	if ($_POST["recaptcha_response_field"]) { // Check wether the user entered something in the field
        $resp = recaptcha_check_answer ($cfg['privatekey'],$_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]); // Check the response
		if (!$resp->is_valid) { // The captcha is invalid
			g_defError(true, 1, $lang['wrongcaptcha'],$resp->error);
        }
	}else{
		g_defError(true, 0, $lang['nocaptcha']); // Return error code 0 - Captcha field was empty
	}
	// Verify username/password combination
	if($err['error'] === false)
		$db_handle = mysql_connect($cfg['db_host'],$cfg['db_user'],$cfg['db_host']) or die(mysql_error()); // Connect to MySQL server or die and print error
		mysql_select_db($cfg['db_host']) or die(mysql_error()); // Select the database containing the STracker info
		$db_result = mysql_query("SELECT uid,salt,password FROM users WHERE username='" . $takeuser . "'",$db_handle); // Query the database for the user information
		$db_result = mysql_fetch_array($db_result)
		if($db_result['uid']){ // If a unique user ID is returned by the database
			g_defError(false, 200, $lang['correctuser']); // Return the OK error code
			$realpass = hash("sha1",hash("sha512",hash("whirlpool",$takepass . $db_result['salt']))); // Encrypt the password and salt using SHA1->SHA512->Whirlpool encryption
			if($realpass == $db_result['password']){ // Compare hashed and salted passwords from the form and the DB
				g_defError(false, 200, $lang['correctuser']); // Return the OK error code
				$_SESSION['uid'] = $db_result['uid']; // Set the session variable so we can check which user is logged in
				if($_SESSION['prevpage']){ // Check wether the user is trying to access a restricted page before sent to login
					$redirect_loc = $_SESSION['prevpage']; // Redirect to previous page
				}else{
					$redirect_loc = $cfg['baseurl'] . "home.php"; // Redirect to home page
				}
				header("302 Page moved temporarily"); // Set the "Page moved" header
				header("Location: " . $redirect_loc); // Set the location header
			}
		}else{
			g_defError(true, 2, $lang['wronguser']); // Return error code 2 - username does not exist
		}
	}
	return $err;
}