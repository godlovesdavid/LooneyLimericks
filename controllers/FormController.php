<?php

//form controller that handles poem submission form and events.
class FormController extends Controller
{
	//receive commands from URL
	static function receiveCommands(array $args)
	{
		if (isset($args['action']))
			self::fetchData($args['action']);
		else
			self::fetchData('none');
		self::showData(self::$data);
	}
	
	//gets data from entry model
	static function fetchData($id)
	{
		include 'models/EntryModel.php';
		self::$data = EntryModel::getData($id);
	}
	
	//shows data in form view from entry model
	static function showData($data)
	{
		include 'views/FormView.php';
		FormView::display($data);
	}
	private static $data;
}

?>