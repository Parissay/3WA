<?php

	session_start();

	// If not connected, return the "session expired" view
	if ($_SESSION['connected'] == false)
	{
		header('Location: session_expired.php');
		exit();
	}
		
	include 'application/bdd_connection.php';

	if (empty($_POST)) 
	{
		// Get all authors identity (dropdown list)
		$query = 
			'SELECT 
				a_id,
				a_name,
				a_surname
			FROM 
				authors';

		// Prepare and execute the query
		$result = $pdo->query($query);
		$authors = $result->fetchAll();

		// Get all categories (dropdown list)
		$query = 
			'SELECT
				cat_id,
				cat_name
			FROM 
				categories';

		// Prepare and execute the query
		$result = $pdo->query($query);
		$categories = $result->fetchAll();

		// Select and display the template
		$template = 'add_post';
		include 'layout.phtml';
	}

	elseif(isset($_POST['submit']))
	{
		
		$title = $_POST['title'];
		$content = $_POST['content'];
		$author = $_POST['author'];
		$category = $_POST['category'];

		if (empty($title) || empty($content) || empty($author) || empty($category))
		{
			// Echo a failure message
			echo 'empty';
		}
		else 
		{
			// Insert the new post in posts table
			$query = 
				'INSERT INTO posts 
					(p_title,
					p_content,
					p_author_id, 
					p_category_id, 
					p_creation_date)
				VALUES 
					(?, ?, ?, ?, NOW())';

			// Prepare and execute the query
			$post = $pdo->prepare($query);
			$post -> execute([$title, $content, $author, $category]);

			// Echo a success message that redirect
			echo "post";
		}
		
	}