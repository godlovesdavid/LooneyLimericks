<?php
//controller
abstract class Controller
{
	abstract static function receiveCommands(array $args);
	abstract static function fetchData($id);
	abstract static function showData($data);
	private static $data;
}

?>