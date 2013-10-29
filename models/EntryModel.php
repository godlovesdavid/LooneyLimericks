<?php
//entry model
class EntryModel
{
	//gets data from arguments.
	static function getData($id)
	{
		if ($id == 'rate')
			$data = self::rate();
		
		if ($id == 'checkform')
			$data['checkresult'] = self::checkFormAndSubmitPoem();
		if ($id == 'none')
			$data['checkresult'] = '';
		
		if (is_numeric($id))
			$data = self::getEntry($id);
		if ($id == '' || $id == 'featured')
			$data = self::getFeatured();
		if ($id == 'random')
			$data = self::getRandom();
		if ($id == 'top10')
			$data = self::getTop10();
		if ($id == 'newest10')
			$data = self::getNewest10();
		if ($id == 'newest')
			$data = self::getNewest();
		$data['links'] = self::getLinks();
		return $data;
	}
	
	//connect to database.
	static function connectToDB()
	{
		global $authenticate;
		$connection = mysqli_connect($authenticate['host'], 
			$authenticate['user'], $authenticate['pass'], 
			$authenticate['dbname']);
		return $connection;
	}
	
	// append star info so that view can draw.
	static function appendRating($poem)
	{
		if (! isset($poem['rating']))
		{
			if ($poem['votes'] == 0)
				$poem['rating'] = 0;
			else
				$poem['rating'] = $poem['value'] / $poem['votes'];
		}
		$poem['stars'] = '';
		for ($i = 1; $i <= 5; $i ++) // calculate what a star looks like
		{
			if ($poem['rating'] >= $i) // 1 full star
				$class = "star_" . $i . " norating_stars rating_voted";
			else
			{
				if ($poem['rating'] > $i - 1 + .74) // close enough, give it the
				                                    // full star
					$class = "star_" . $i . " norating_stars rating_voted";
				else if ($poem['rating'] > $i - 1 + .24 &&
					 $poem['rating'] < $i - 1 + .75) // between
					                                // .25
					                                // and
					                                // .74,
					                                // inclusive
					$class = "star_" . $i . " norating_stars rating_halfvoted";
				else
					$class = "star_" . $i . " norating_stars rating_blank";
			}
			$poem['stars'] .= '<div class="' . $class . '"></div>';
		}
		return $poem;
	}
	
	//rate action call that saves a user rating.
	static function rate()
	{
		header("Pragma: nocache");
		header("Cache-Control: no-cache");
		$id_sent = preg_replace("/[^0-9]/", "", $_REQUEST['id']);
		$vote_sent = preg_replace("/[^0-9]/", "", $_REQUEST['stars']);
		$connection = self::connectToDB();
		$numbers = mysqli_fetch_assoc(
			mysqli_query($connection, 
				"select votes, value from poems where id=" . $id_sent));
		$sum = $vote_sent + $numbers['value'];
		($sum == 0? $added = 0 : $added = $numbers['votes'] + 1);
		if ($vote_sent <= 5 && $vote_sent >= 1)
			mysqli_query($connection, 
				"update poems set votes='" . $added . "', value='" . $sum .
					 "' where id=" . $id_sent);
		$data = mysqli_fetch_assoc(
			mysqli_query($connection, 
				"select votes, value, id from poems where id=" . $id_sent));
		$data['rated'] = 'true';
		return self::appendRating($data);
	}
	
	//get list of poem entry links in html.
	static function getLinks()
	{
		$result = mysqli_query(self::connectToDB(), 
			"select * from poems order by timestamp");
		$links = '';
		while ($row = mysqli_fetch_assoc($result))
			$links .= '<a href="index.php?main&e=' . $row['id'] . '">' .
				 $row['title'] . ' by ' . $row['author'] . '</a><br/>';
		return $links;
	}
	
	//get featured poem.
	static function getFeatured()
	{
		$data['header'] = 'Featured Poem';
		$data['requestedPoems'][] = self::appendRating(
			mysqli_fetch_assoc(
				mysqli_query(self::connectToDB(), 
					"select * from poems where featured=1")));
		return $data;
	}
	
	//get random poem.
	static function getRandom()
	{
		$data['header'] = 'Random Poem';
		$data['requestedPoems'][] = self::appendRating(
			mysqli_fetch_assoc(
				mysqli_query(self::connectToDB(), 
					"select * from poems order by rand()")));
		return $data;
	}
	
	//get top 10 poems.
	static function getTop10()
	{
		$data['header'] = 'Top 10 Poems';
		$result = mysqli_query(self::connectToDB(), 
			"select * from poems order by (value / votes) desc limit 10");
		while ($row = mysqli_fetch_assoc($result))
			$data['requestedPoems'][] = self::appendRating($row);
		return $data;
	}
	
	//get newest poem.
	static function getNewest()
	{
		$data['header'] = 'Newest Poem';
		$data['requestedPoems'][] = self::appendRating(
			mysqli_fetch_assoc(
				mysqli_query(self::connectToDB(), 
					"select * from poems order by timestamp desc")));
		return $data;
	}
	
	//get newest 10 poems.
	static function getNewest10()
	{
		$data['header'] = 'Newest 10 Poems';
		$result = mysqli_query(self::connectToDB(), 
			"select * from poems order by timestamp desc limit 10");
		while ($row = mysqli_fetch_assoc($result))
			$data['requestedPoems'][] = self::appendRating($row);
		return $data;
	}
	
	//get requested poem.
	static function getEntry($id)
	{
		$data['header'] = '';
		$data['requestedPoems'][] = self::appendRating(
			mysqli_fetch_assoc(
				mysqli_query(self::connectToDB(), 
					"select * from poems where id=" . $id)));
		return $data;
	}
	
	//check form after submission, and submit it.
	static function checkFormAndSubmitPoem()
	{
		list($a1, $a2, $b1, $b2, $a3) = explode("\n", $_POST['content']);
		if (! EntryModel::doesRhyme($a1, $a2) ||
			 ! EntryModel::doesRhyme($a1, $a3) || ! EntryModel::doesRhyme($b1, 
				$b2))
			return "Rhyming Scheme error";
		else // enter data
		{
			if (self::enterData($_POST))
				return "Successfully added poem";
			else
				return "Insert into database error";
		}
	}
	
	//append real escape string to poem and adds it to db.
	static function enterData($data)
	{
		$id = null; // mysql will auto increment
		global $authenticate;
		$connection = self::connectToDB();
		// must add slashes
		$title = mysqli_real_escape_string($connection, $data['title']);
		$author = mysqli_real_escape_string($connection, $data['author']);
		$timestamp = time();
		$content = mysqli_real_escape_string($connection, $data['content']);
		$value = 0.00; // initial value
		
		if (mysqli_query($connection, 
			"INSERT INTO poems VALUES ('$id', '$title', '$author', '$timestamp', '$content', 0, 0, '$value')"))
			return true;
		else
			print mysqli_error($connection);
		return false;
	}
	
	//check if 2 lines rhyme.
	static function doesRhyme($line1, $line2)
	{
		$words1 = preg_split('/\s+/', 
			trim(preg_replace("/[^A-Za-z' ]/", ' ', $line1)));
		$lastWord1 = $words1[count($words1) - 1];
		$words2 = preg_split('/\s+/', 
			trim(preg_replace("/[^A-Za-z' ]/", ' ', $line2)));
		$lastWord2 = $words2[count($words2) - 1];
		return substr(metaphone($lastWord1), - 1) ===
			 substr(metaphone($lastWord2), - 1);
	}
}

?>