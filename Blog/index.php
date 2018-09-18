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
			LastName
		FROM Post
		INNER JOIN Author
		ON Author.Id = Post.Author_Id
		ORDER BY CreationTimestamp DESC';

	$result_set = $dbh -> query($query);
	$result_set -> execute();
	$articles = $result_set -> fetchAll();

	$template = 'index';

	include 'layout.phtml';