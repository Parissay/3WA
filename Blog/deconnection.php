<?php

	session_start();
	// Destroy the session
	session_destroy();
	// Then return to index
	header('Location: index.php');
	exit();