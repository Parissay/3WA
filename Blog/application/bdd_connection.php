<?php
	// Chemin entre ma page et ma base de données
	$pdo = new PDO('mysql:host='the host';dbname='database name';charset=utf8','id','password',
		[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);