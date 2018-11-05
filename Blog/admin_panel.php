<?php

	session_start();
	
	// If not connected, return the "session expired" view
	if ($_SESSION['connected'] !== true)
	{
		header('Location: session_expired.php');
		exit();
	}

	// If connected
	else 
	{
		include 'application/bdd_connection.php';
		
		// Get all the posts and sort in descending order
		$query = 
			'SELECT 
				p_id,
				p_title,
				p_content,
				p_creation_date,
				p_category_id,
				a_name,
				a_surname,
				cat_name
			FROM posts
			INNER JOIN authors
			ON authors.a_id = posts.p_author_id
			INNER JOIN categories
			ON categories.cat_id = posts.p_category_id
			ORDER BY p_creation_date DESC';

		// Prepare and execute the query
		$result = $pdo->query($query);
		$posts = $result->fetchAll();
		
		// Select and display the template
		$template = 'admin_panel';
		include 'layout.phtml';
	}