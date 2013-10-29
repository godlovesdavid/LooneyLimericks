<?php
<<<<<<< HEAD

//abstract class controller.
=======
//controller
>>>>>>> cleanpoemsondb
abstract class Controller
{
	//receive commands from URL arguments.
	abstract static function receiveCommands(array $args);
	//fetch data from model with arguments.
	abstract static function fetchData($id);
	//show data in view with fetched data.
	abstract static function showData($data);
	private static $data;
}

?>