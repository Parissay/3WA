<?php

	include 'application/bdd_connection.php';
	
	// Validation of the query string in the URL.
	if (!array_key_exists('id' , $_GET) OR !ctype_digit($_GET['id']))
	{
		header('Location: index.php');
		exit();
	}

	// Delete a post.
	$query =
	'
		DELETE FROM
			posts
		WHERE
			p_id = ?
	';

	$result = $pdo -> prepare($query);
	$result -> execute(array($_GET['id']));
	
	// Return to admin panel.
	header('Location: admin.php');
	exit();