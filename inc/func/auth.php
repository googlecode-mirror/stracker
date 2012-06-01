<?php
//This file will contain functions for user authentication and signup
function _auth($takeuser,$takepass){ // PASSWORD MUST BE ENCRYPTED USING SHA1->BASE64->WHIRLPOOL ALREADY
	// Check Captcha
	if ($_POST["recaptcha_response_field"]) { // Check wether the user entered something in the field
        $resp = recaptcha_check_answer ($cfg['privatekey'],
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]); // Check the response
										
		if ($resp->is_valid) { // The captcha is valid
            echo "You got it!";
        } else { // The captcha is invalid
            $err['more'] = $resp->error; // Throw exact error into the function output
			$err['error'] = true; // An error ocurred
			$err['id'] = 1; // Error ID is 1 - Wrong captcha
			$err['string'] = $lang['wrongcaptcha']; // "You entered the wrong answer in the captcha test."
        }
	}else{
		// Return error code 0 - Captcha field was empty
		$err['error'] = true;
		$err['id'] = 0;
		$err['string'] = $lang['nocaptcha']; // "You didn't enter anything in the captcha test."
	}
	// Verify username/password combination
	if($err['error'] === false)
		$db_handle = mysql_connect($cfg['db_host'],$cfg['db_user'],$cfg['db_host']) or die(mysql_error()); // Connect to MySQL server or die and print error
		mysql_select_db($cfg['db_host']) or die(mysql_error()); // Select the database containing the STracker info
		$db_result = mysql_query("SELECT uid,salt,password FROM users WHERE username='" . $takeuser . "'",$db_handle); // Query the database for the user information
		$db_result = mysql_fetch_array($db_result)
		if($db_result['uid']){ // If a unique user ID is returned by the database
			// Return the OK error code
			$err['error'] = false;
			$err['id'] = 200;
			$err['string'] = $lang['correctuser']; // "You have entered an existing username"
			$realpass = hash("sha1",hash("aes",hash("whirlpool",$takepass . $db_result['salt'])));
			if($realpass == $db_result['password']){
				if($_SESSION['prevpage']){ // Check wether the user is trying to access a restricted page before sent to login
					$redirect_loc = $_SESSION['prevpage']; // Redirect to previous page
				}else{
					$redirect_loc = $cfg['baseurl'] . "home.php"; // Redirect to home page
				}
				header("302 Page moved temporarily"); // Set the "Page moved" header
				header("Location: " . $redirect_loc); // Set the location header
			}
		}else{
			// Return error code 2 - user/password combination incorrect
			$err['error'] = true;
			$err['id'] = 2;
			$err['string'] = $lang['userpwnotexist']; // "The username and password combination you entered is incorrect"
		}
	}
	return $err;
}