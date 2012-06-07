<?php
// This is the configuration file.
// You don't have to fill this out manually, but in case you end up breaking the settings
// You can open this file to edit them.
	
$cfg = array(
'baseurl' => '/', // Defines the root folder of STracker. Usually also the webroot.
'publickey' => '123456789', // The public key used for the captcha turing tests
'privatekey' => '123456789', // The private key used for the captcha turing tests
'default_lang' => 'en_UK', // Default language file for new users
'db_host' => 'localhost', // This defines the IP address or hostname for the MySQL database
'db_user' => 'stracker', // This is the username STracker will use to connect to the database
'db_pass' => 'stracker', // And this is the password
'db_name' => 'stracker', // This defines the name of the database that contains all the STracker information
'default_theme' => 'default', // The theme to be loaded if no user is logged in or no theme is set
'title' => 'My Tracker', // The title of the website for page head
'description' => 'This is an awesome private tracker', // The meta description of the website
'keywords' => '' // Meta keywords for SEO
);