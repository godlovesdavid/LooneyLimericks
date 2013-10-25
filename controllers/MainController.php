<?php
class MainController extends Controller
{
	public static function receiveCommands(array $args) // args is url args
	{
		// receives e as one of four values: top10, random, featured, newest,
		// or some entry id
		if (! isset($args['e']))
			$args['e'] = '';
		self::fetchData($args['e']);
		self::showData(self::$data);
	}
	public static function fetchData($id)
	{
		include 'models/EntryModel.php';
		self::$data = EntryModel::getData($id);
	}
	public static function showData($data)
	{
		include 'views/MainView.php';
		MainView::display($data);
	}
	private static $data;
}

?>