<?php

	include 'application/bdd_connection.php';

	if (empty($_POST)) {

		// Get all authors.
		$query =
		'
			SELECT
				a_id,
				a_name,
				a_surname
			FROM
				authors
		';

		$result = $pdo -> query($query);
		$authors = $result -> fetchAll();

		// Get all categories.
		$query =
		'
			SELECT
				cat_id,
				cat_name
			FROM
				categories
		';

		$result = $pdo -> query($query);
		$categories = $result -> fetchAll();

		// Select and display the template.
		$template = 'add_post';
		include 'layout.phtml';

	} else {

		// Add a post
		$query =
		'
			INSERT INTO
				posts (
					p_title,
					p_content,
					p_author_id,
					p_category_id,
					p_creation_date
				)
			VALUES (?, ?, ?, ?, NOW())
		';

		$result = $pdo -> prepare($query);
		$result -> execute([$_POST['title'], $_POST['content'], $_POST['author'], $_POST['category']]);

		// Return to admin panel.
		header('Location: index.php');
		exit();
	
	}