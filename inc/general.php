<?php
////////////////////////////////////////////
//               General                  //
//----------------------------------------//
// This file contains all the general     //
// functions including shorthands for     //
// making the script more readable        //
// These functions are:                   //
// - g_defError                           //
// - g_req                                //
// - db_conn                              //
////////////////////////////////////////////
// Required includes:                     //
   g_req(array("config"));                //
////////////////////////////////////////////

// Include the language files, this is needed on all pages. Therefore it is put here
if(empty($_SESSION['lang'])){
	require_once($cfg['baseurl'] . "lang/" . $cfg['default_lang'] . ".php");
}else{
	require_once($cfg['baseurl'] . "lang/" . $_SESSION['lang'] . ".php");
}

// g_defError is a shorthand for general_defineError and defines the error array in a more compact way
function g_defError($is_error, $id, $string, $debug = ""){ 
	$err['error'] = $is_error; // Tells us wether this is a good or a bad error code
	$err['id'] = $id; // Gives a numeric identifier for the error
	$err['string'] = $string; // A textual representation of the error. MUST REFER TO THE $lang ARRAY!
	$err['debug'] = $debug; // Contains the error string of the function that caused the error (if applicable)
	if($is_error){
		$logged_err = "ERROR: ";
	}else{
		$logged_err = "OK: ";
	}
	$logged_err .= $id . " - " . $debug;
	error_log($logged_err);
	return $err;
}

// g_req is a shorthand general_require_once for including specific files in the /inc folder using an array
function g_req(){ 
	$files = func_get_args();
	foreach($files as $filename){ // For each file listed in the array
		require_once($cfg['baseurl'] . "inc/" . $filename . ".php"); // Require the file included, unless it already is
	}
	return;
}

function g_db_conn($database){
	$handle = mysql_connect($cfg['db_host'],$cfg['db_user'],$cfg['db_pass']) or die(g_defError(true,100,$lang['dbconnectfailed']) and return $err);
	mysql_select_db($database) or die(g_defError(true,101,$lang['dbselectfailed']) and return $err);
	return $handle;
}
?>