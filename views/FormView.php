<?php
include 'views/View.php';

//class form view that handles poem submission form.
class FormView extends View
{
	//display data in html.
	static function display($data)
	{
		include 'FormView.html';
	}
}

?>