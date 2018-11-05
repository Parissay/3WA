<?php
	
	session_start();

	if(isset($_POST['submit']))
	{		
		$postId = $_POST['postId'];
		$nickname = $_POST['nickname'];
		$content = $_POST['content'];

		if (empty($nickname) || empty($content))
		{
			// Echo a failure message
			echo 'empty';
		}
		else 
		{
			include 'application/bdd_connection.php';

			// Insert user comment in comments table
			$query = 
				'INSERT INTO comments 
					(com_post_id, 
					com_nickname, 
					com_content, 
					com_creation_date)
				VALUES 
					(?, ?, ?, NOW())';

			// Prepare and execute the query
			$result = $pdo->prepare($query);
			$result -> execute(array($postId, $nickname, $content));

			// Echo a success message
			echo "comment";
		}
	}