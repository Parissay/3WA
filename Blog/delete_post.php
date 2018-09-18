<?php

	include 'application/bdd_connection.php';

	$query = '
		SELECT *
		FROM Post
		';

	$abracadabra = $_GET['Id'];

	$article = $dbh->prepare('
		DELETE FROM Post
		WHERE Id=?
		');

	$article->execute(array($abracadabra));

	header('Location: admin.php');
	
	exit();