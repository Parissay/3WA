<?php

	session_start();
	
	// If not connected, return the "session expired" view
	if ($_SESSION['connected'] == false)
	{
		header('Location: session_expired.php');
		exit();
	}

	// If connected
	else 
	{
		include 'application/bdd_connection.php';
		
		// Validation of the query string in the URL
		if (!array_key_exists('id', $_GET) OR !ctype_digit($_GET['id']))
		{
			header('Location: index.php');
			exit();
		}	
		// If the user tries to delete a prohibited post
		if (($_GET['id']) <= 5) 
		{
			header('Location: forbidden.php');
			exit();
		}
		else 
		{			
			// Delete the post
			$query =
				'DELETE FROM posts
				WHERE p_id = ?';

			// Prepare and execute the query
			$result = $pdo -> prepare($query);
			$result -> execute(array($_GET['id']));
			
			// Then, return to admin panel
			header('Location: admin_panel.php');
			exit();
		}
	}