<?php
class FormController
{
//receive commands
	static function receiveCommands(array $args)
	{
		// receives either no args or check as key
		self::fetchData($args);
		self::showData(self::$data);
	}
	static function fetchData($id)
	{
		include 'models/EntryModel.php';
		self::$data = EntryModel::getData($id);
	}
	static function showData($data)
	{
		include 'views/FormView.php';
		FormView::display($data);
	}
	private static $data;
}

?>