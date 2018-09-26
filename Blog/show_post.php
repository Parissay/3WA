<?php
	
	include 'application/bdd_connection.php';
	
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
			p_content,
			p_creation_date,
			a_name,
			a_surname
		FROM
			posts
		INNER JOIN
			authors
		ON
			posts.p_author_id = authors.a_id
		WHERE
			posts.p_id = ?
	';

	$result = $pdo -> prepare($query);
	$result -> execute(array($_GET['id']));
	$post = $result -> fetch();

	// Get all the comments from the post.
	$query = 
	'
		SELECT
			com_nickname,
			com_content,
			com_creation_date
		FROM
			comments
		WHERE
			com_post_id = ?
	';

	$result = $pdo -> prepare($query);
	$result -> execute(array($_GET['id']));
	$comments = $result -> fetchAll();
	
	// Select and display the template.
	$template = 'show_post';
	include 'layout.phtml';