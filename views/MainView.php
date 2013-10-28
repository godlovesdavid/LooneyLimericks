<?php
include 'views/View.php';

//main view that shows poems.
class MainView extends View
{
	
	//use html to show data passed by controller.
	static function display($data)
	{
		include 'MainView.html';
	}
}

?>