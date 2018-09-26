<?php

	include 'application/bdd_connection.php';

	if (empty($_POST)) {

		// Validation of the query string in the URL.
		if (!array_key_exists('id' , $_GET) OR !ctype_digit($_GET['id']))
		{
			header('Location: index.php');
			exit();
		}

		// Get a post.
		$query =
		'
			SELECT
				p_id,
				p_title,
				p_content
			FROM
				posts
			WHERE
				p_id = ?
		';

		$result = $pdo -> prepare($query);
		$result -> execute(array($_GET['id']));
		$post = $result -> fetch();
	
		// Select and display the template.
		$template = 'edit_post';
		include 'layout.phtml';

	} else {

		// Edit a post.
		$query =
		'
			UPDATE
				posts
			SET
				p_title = ?,
				p_content = ?
			WHERE
				p_id = ?
		';

		$result = $pdo -> prepare($query);
		$result -> execute(array(
			$_POST['p_title'], 
			$_POST['p_content'], 
			$_POST['postId']
		));

		// Return to admin panel.
		header('Location: index.php');
		exit();
	
	}