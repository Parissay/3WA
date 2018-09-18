<?php
	
	include 'application/bdd_connection.php';
	
	if (!array_key_exists('Id' , $_GET) OR !ctype_digit($_GET['Id'])){
			header('Location: index.php');
			exit();
		}
	$query = '
		SELECT 
			Post.Id,
			Title,
			Contents,
			CreationTimestamp,
			Author.Id,
			FirstName,
			LastName			
		FROM Post
		INNER JOIN Author
		ON Author.Id = Post.Author_Id
		WHERE Post.Id=?
		';

	$temp = $dbh->prepare($query);
	$temp->execute(array($_GET['Id']));
	$article = $temp->fetch();
	$query2 = $dbh->prepare('
		SELECT 
			Comment.Id,
			NickName,
			Comment.Contents AS com,
			Comment.CreationTimestamp,
			Comment.Post_Id,
			Post.Id			
		FROM Comment
		INNER JOIN Post
		ON Comment.Post_Id = Post.Id
		WHERE Post.Id=?
		');

	$query2->execute(array($_GET['Id']));

	$comments = $query2->fetchAll();

	$template = 'show_post';

	include 'layout.phtml';