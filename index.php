<?php
startSession();
includeThings();
passArgsToController(getArgsFromURL());

// starts user session.
function startSession()
{
	if (! isset($_SESSION))
		session_start();
}

// include things like configuration constants.
function includeThings()
{
	include 'config/config.php';
	global $authenticate;
	$authenticate['user'] = $USER;
	$authenticate['pass'] = $PASSWORD;
	$authenticate['host'] = $HOST;
	$authenticate['dbname'] = $DBNAME;
	foreach (glob("controllers/*.php") as $filename)
		include $filename;
}

// gets the arguments from URL.
function getArgsFromURL()
{
	parse_str($_SERVER['QUERY_STRING'], $args);
	return $args;
}

// passes arguments to requeseted controller.
function passArgsToController($args)
{
	reset($args);
	if (key($args) == '')
		$controllername = 'MainController';
	else
		$controllername = ucfirst(key($args)) . 'Controller';
	$controllername::receiveCommands($args);
}

// global variable for authentication
$authenticate;

?>