<?php
<<<<<<< HEAD
include 'views/View.php';

//main view that shows poems.
class MainView extends View
=======
//main view
class MainView
>>>>>>> cleanpoemsondb
{
	
	//use html to show data passed by controller.
	static function display($data)
	{
		include 'MainView.html';
	}
}

?>