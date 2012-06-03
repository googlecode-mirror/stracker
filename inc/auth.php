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

function auth_validate($takeuser,$takepass){
	// Check Captcha
	if ($_POST["recaptcha_response_field"]) { // Check wether the user entered something in the field
        $resp = recaptcha_check_answer ($cfg['privatekey'],$_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]); // Check the response
		if (!$resp->is_valid) { // The captcha is invalid
			$e = g_defError(true, 1, $lang['wrongcaptcha'],$resp->error);
        }
	}else{
		g_defError(true, 0, $lang['nocaptcha']); // Return error code 0 - Captcha field was empty
	}
	// Verify username/password combination
	if($err['error'] === false)
		$db_result = mysql_query("SELECT uid,salt,password FROM users WHERE username='" . $takeuser . "'"); // Query the database for the user information
		$db_result = mysql_fetch_array($db_result);
		if($db_result['uid']){ // If a unique user ID is returned by the database
			$e = g_defError(false, 200, $lang['correctuser']); // Return the OK error code
			$realpass = hash("sha1",hash("sha512",hash("whirlpool",$takepass . $db_result['salt']))); // Encrypt the password and salt using SHA1->SHA512->Whirlpool encryption
			if($realpass == $db_result['password']){ // Compare hashed and salted passwords from the form and the DB
				$e = g_defError(false, 200, $lang['correctuser']); // Return the OK error code
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
			$e = g_defError(true, 2, $lang['wronguser']); // Return error code 2 - username does not exist
		}
	}
	return $err;
}




function auth_register($takeuser,$takepass,$takepass2,$takemail,$takemail2){
	$mail_tpl = mysql_fetch_array(mysql_query("SELECT * FROM mails WHERE active=true AND type='registration'"));
	$resp = recaptcha_check_answer ($cfg['privatekey'],$_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]); // Check captcha response
	if(!$takeuser | !$takepass | !$takepass2 | !$takemail | !$takemail2){
		$e = g_defError(true,3,$lang['fieldmissing']);
	}elseif($takepass != $takepass2){
		$e = g_defError(true,4,$lang['passmismatch']);
	}elseif($takemail != $takemail2){
		$e = g_defError(true,4,$lang['mailmismatch']);
	}elseif(mysql_num_rows(mysql_query("SELECT username LIKE $takeuser"))){
		$e = g_defError(true,6,$lang['usernameexists']);
	}elseif(!$resp->is_valid) {
		$e = g_defError(true, 1, $lang['wrongcaptcha'],$resp->error);
    }else{
		$salt = md5(substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,5));
		$encryptedpass = hash("sha1",hash("sha512",hash("whirlpool",$takepass . $salt)));
		mysql_query("INSERT INTO users SET username=$takeuser,password=$encryptedpass,salt=$salt,email=$takemail,active=no");
		$mail_tpl = mysql_fetch_array(mysql_query("SELECT * FROM mails WHERE active=true AND type='registration'"));
		$subject = str_replace("%username%",$takeuser,$mail_tpl['subject']);
		$message = str_replace("%username%",$takeuser,$mail_tpl['message']);
		$message = str_replace("%link%",$cfg['baseurl'] . "activate.php?salt=" . $salt,$message);
		mail($takemail,$subject,$message,$mail_tpl['header']);
		$e = g_defError(true, 200, $lang['reg_complete']); // OK code - registered correctly
	}
}