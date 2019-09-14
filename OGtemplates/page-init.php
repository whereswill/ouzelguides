<?php 
//Include Classes, functions, and Db
include $_SERVER['DOCUMENT_ROOT'].'/ASEngine/AS.php';
include $_SERVER['DOCUMENT_ROOT'].'/OGEngine/OG.php';

//include Composers autoloader for installed packages
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

//Set the page for navigation
$page = basename($_SERVER['PHP_SELF']);

//Set previous page
if (isset($_SERVER['HTTP_REFERER'])) {
		$previous = $_SERVER['HTTP_REFERER'];
} else {
		$previous = '/index.php';
}


///The following takes the previous page and sets it as a target 
///The previous page must declare itself as a target
///The current page must have a button to return to target if the target exists
///Set the target, button name, and number of clicks
if (isset($_GET['target'])) {
	ASSession::set("target", $previous);
	ASSession::set("target_clicks", 1);
	ASSession::set("return_btn", $_GET['target']);
}

$target = false;
if (null !== ASSession::get("target")) {
	///If on the target page, unset session, otherwise add a click OR Check if user has clicked of course
	if (ASSession::get("target") == basename($_SERVER['PHP_SELF']) || ASSession::get("target_clicks") == 10) {
		ASSession::destroy('target');
		ASSession::destroy('target_clicks');
		ASSession::destroy('return_btn');
		$target = false;
	} else {
		$targetclicks = ASSession::get("target_clicks") + 1;
		ASSession::set("target_clicks", $targetclicks);
		$target = true;
	}
}

		// echo '<pre>';
		// var_dump($_SESSION);
		// echo '</pre>';
		//exit;

//get current visitor
if(!$login->isLoggedIn()) {
	header("Location: /login.php");
	exit();
}
$visitor = new ASUser(ASSession::get("user_id"));
$visitorInfo = $visitor->getInfo();
$visitor_id = $visitorInfo['user_id'];
// echo '<pre>';
// echo $visitor_id;
// echo '</pre>';
// exit;

// Set current date and time for updates
$date = new DateTime(); //this returns the current date time
$datetime = $date->format("Y-m-d H:i:s");

?>
