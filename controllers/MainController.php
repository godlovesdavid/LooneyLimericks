<?php
//main controller that shows poems.
class MainController extends Controller
{
	//receive commands from URL.
	public static function receiveCommands(array $args) // args is url args
	{
		if (isset($args['action']))
			self::fetchData($args['action']);
		else if (isset($args['e']))
			self::fetchData($args['e']);
		else
			self::fetchData('');
		self::showData(self::$data);
	}
	
	//fetch data from entry model with URL args.
	public static function fetchData($id)
	{
		include 'models/EntryModel.php';
		self::$data = EntryModel::getData($id);
	}
	
	//show data in main view from fetched data.
	public static function showData($data)
	{
		if (isset(self::$data['rated']))
		{
			include 'views/RateView.php';
			RateView::display($data);
		}
		else
		{
			include 'views/MainView.php';
			MainView::display($data);
		}
	}
	private static $data;
}

?>