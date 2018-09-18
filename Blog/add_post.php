<?php

	include 'application/bdd_connection.php';

	if (empty($_POST)) {

		$query1 = '
		SELECT 
			Author.Id,
			Author.FirstName,
			Author.LastName
		FROM Author	
		';
		$temp1 = $dbh->query($query1);
		$author = $temp1->fetchAll();

		$query2 = '
		SELECT
			Category.Id,
			Category.Name
		FROM Category
		';
		$temp2 = $dbh->query($query2);
		$categories = $temp2->fetchAll();

		$template = 'add_post';
		include 'layout.phtml';

	} else {

		$query3 = '
			INSERT INTO Post (Title, Contents, Author_Id, Category_Id, CreationTimestamp)
			VALUES (?, ?, ?, ?, NOW())
			';
		$article = $dbh->prepare($query3);
		$article->execute([$_POST['Title'], $_POST['Contents'], $_POST['Author_Id'], $_POST['Category_Id']]);

		header('Location: index.php');

		exit();
	
	}


