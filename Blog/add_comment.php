<?php

	include 'application/bdd_connection.php';

	// Add a coment to a post.
	$query =
	'
		INSERT INTO
			comments (
				com_post_id,
				com_nickname,
				com_content,
				com_creation_date
			)
		VALUES
			(?, ?, ?, NOW())';

	$result = $pdo -> prepare($query);
	$result -> execute(array($_POST['post'], $_POST['nickname'], $_POST['content']));

	// Return to the post.
	header('Location: show_post.php?id='.$_POST['post']);
	exit();