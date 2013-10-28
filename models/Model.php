<?php
//abstract class model.
abstract class Model
{
	//gets data from database or wherever.
	abstract static function getData($name);
}

?>