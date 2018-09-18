<?php

	include 'application/bdd_connection.php';

	$query = '
		INSERT INTO Comment (Post_Id, NickName, Contents, CreationTimestamp)
		VALUES (?, ?, ?, NOW())';
	$temp = $dbh->prepare($query);
	$temp->execute(array($_POST['Post'], $_POST['NickName'], $_POST['Contents']));

	header('Location: show_post.php?Id='.$_POST['Post']);
	exit();