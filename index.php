<?php
startSession();
includeThings();
passArgsToController(getArgsFromURL());
function startSession()
{
	if (! isset($_SESSION))
		session_start();
}
function includeThings()
{
	global $authenticate;
	include 'config/config.php';
	$authenticate['user'] = $USER;
	$authenticate['pass'] = $PASSWORD;
	foreach (glob("controllers/*.php") as $filename)
		include $filename;
}
function getArgsFromURL()
{
	parse_str($_SERVER['QUERY_STRING'], $args);
	return $args;
}
function passArgsToController($args)
{
	reset($args);
	if (key($args) == '')
		$controllername = 'MainController';
	else
		$controllername = ucfirst(key($args)) . 'Controller';
	$controllername::receiveCommands($args);
}

$authenticate;

?>