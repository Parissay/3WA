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
		// Validation of the query string in the URL
		if (!array_key_exists('id' , $_GET) OR !ctype_digit($_GET['id']))
		{
			header('Location: admin_panel.php');
			exit();
		}

		// Get post title and content
		$query = 
			'SELECT 
				p_id,
				p_title,
				p_content
			FROM posts
			WHERE p_id=?';

		// Prepare and execute the query
		$result = $pdo->prepare($query);
		$result->execute(array($_GET['id']));
		$post = $result->fetch();
		
		// Select and display the template
		$template = 'edit_post';
		include 'layout.phtml';
	}
	elseif (isset($_POST['submit'])) 
	{
		$postId = $_POST['postId'];
		$title = $_POST['title'];
		$content = $_POST['content'];

		if (empty($title) || empty($content)) 
		{
			// Echo a failure message
			echo 'empty';
		}
		else 
		{
			// Edit the post according to its id
			$query = 
				'UPDATE posts
				SET p_title=?, p_content=?
				WHERE p_id=?';

			// Prepare and execute the query
			$post = $pdo->prepare($query);
			$post->execute([$title, $content, $postId]);

			// Echo a success message
			echo "post";
		}		
	}