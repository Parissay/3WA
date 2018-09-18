<?php

	include 'application/bdd_connection.php';

	$query = 
		'SELECT 
			Post.Id,
			Title,
			Contents,
			CreationTimestamp,
			Category_Id,
			FirstName,
			LastName,
			Name	
		FROM Post
		INNER JOIN Author
		ON Author.Id = Post.Author_Id
		INNER JOIN Category
		ON Category.Id = Post.Category_Id
		ORDER BY CreationTimestamp DESC';

	$result_set = $dbh->query($query);
	$articles = $result_set->fetchAll();

	$template = 'admin';

	include 'layout.phtml';