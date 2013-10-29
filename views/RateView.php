<?php
include 'views/View.php';

// rate view that shows new rating after newly rated.
class RateView extends View
{
	
	// display simple html thanking user for rating.
	static function display($data)
	{
		print 
			'<div class="item">Thanks for rating!<div id="rating_' . $data['id'] .
				 '">' . $data['stars'] . '<div class="votes"><p>' .
				 sprintf("%.2f", $data['rating']) . ' out of 5 (' .
				 $data['votes'] . ' total votes)</p></div>';
	}
}