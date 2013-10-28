<?php
//entry model
class EntryModel
{
	static function getData($id)
	{
		global $authenticate;
		$connection = mysqli_connect("localhost", $authenticate['user'], 
			$authenticate['pass'], "poems");
		if (isset($id['check']))
		{
			$data = self::checkForm();
			return $data;
		}
		else if (isset($id['form']))
			return '';
		else if ($id == 'featured')
			$data['requestedPoems'][] = mysqli_fetch_assoc(
				mysqli_query($connection, "SELECT * FROM featured"));
		else if ($id == 'random')
			$data['requestedPoems'][] = mysqli_fetch_assoc(
				mysqli_query($connection, "SELECT * FROM random"));
		else if ($id == 'top10')
			while ($row = mysqli_fetch_assoc(
				mysqli_query($connection, "SELECT * FROM top10")))
				$data['requestedPoems'][] = $row;
		else if ($id == 'newest')
			$data['requestedPoems'][] = mysqli_fetch_assoc(
				mysqli_query($connection, "SELECT * FROM newest"));
		else if ($id == '')
			$data['requestedPoems'][] = mysqli_fetch_assoc(
				mysqli_query($connection, "SELECT * FROM featured"));
		else
			$data['requestedPoems'][] = mysqli_fetch_assoc(
				mysqli_query($connection, "SELECT * FROM all WHERE id=" . $id));
		return $data;
	}
	static function checkForm()
	{
		list($a1, $a2, $b1, $b2, $a3) = explode("\n", $_POST['content']);
		if (! EntryModel::doesRhyme($a1, $a2) ||
			 ! EntryModel::doesRhyme($a1, $a3) || ! EntryModel::doesRhyme($b1, 
				$b2))
			$result = "Rhyming Scheme error";
		else
		{
			if (self::enterData($_POST))
				$result = "successfully added";
			else
				$result = "insert into database error";
		}
		return $result;
	}
	static function enterData($data)
	{
		global $authenticate;
		$id = time(); // use unix timestamp for id
		$title = addslashes($data['title']);
		$author = addslashes($data['author']);
		$date = date("m\.d\.y");
		$content = addslashes($data['content']);
		$rating = 0; //initial rating
		return mysqli_query(
			mysqli_connect("localhost", $authenticate['user'], 
				$authenticate['pass'], "poems"), 
			"INSERT INTO total VALUES ('$id', '$title', '$author', '$date', '$content', '$rating')");
	}
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