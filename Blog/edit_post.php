<?php

	include 'application/bdd_connection.php';

	if (empty($_POST)) {
		if (!array_key_exists('Id' , $_GET) OR !ctype_digit($_GET['Id'])){
			header('Location: index.php');
			exit();
		}

		$query1 = '
			SELECT 
				Id,
				Title,
				Contents
			FROM Post
			WHERE Id=?
			';
		$temp1 = $dbh->prepare($query1);
		$temp1->execute(array($_GET['Id']));
		$post = $temp1->fetch();

		$template = 'edit_post';
		include 'layout.phtml';

	} else {

		$query2 = '
			UPDATE Post
			SET Title=?, Contents=?
			WHERE Id=?
			';
		$article = $dbh->prepare($query2);
		$article->execute([$_POST['Title'], $_POST['Contents'], $_POST['articleName']]);

		header('Location: index.php');

		exit();
	
	}