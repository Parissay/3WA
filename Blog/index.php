<?php

	include 'application/bdd_connection.php';

	// Get all the posts and sort in descending order.
	$query = 
	'
		SELECT 
			p_id,
			p_title,
			p_content,
			p_creation_date,
			p_category_id,
			a_name,
			a_surname
		FROM 
			posts
		INNER JOIN 
			authors
		ON 
			posts.p_author_id = authors.a_id
		ORDER BY 
			p_creation_date DESC
	';

	$result = $pdo -> query($query);
	$posts = $result -> fetchAll();

	// Select and display the template.
	$template = 'index';
	include 'layout.phtml';